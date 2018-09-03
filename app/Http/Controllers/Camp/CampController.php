<?php

namespace App\Http\Controllers\Camp;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Camp\PostController;
use App\Models\ParentDetail;
use App\Models\ChildDetail;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CampController extends Controller
{
  private $postController;
  public function __construct()
  {
    $this->postController = new PostController();
  }

  public function getLocationCamps(Request $request)
  {
    $post_id = $request->get('post_id');
    $post = $this->postController->getPostData($post_id);
    $closet_locations = array();
    $count = 0;
    if ($post) {
      $locations = DB::table('locations')->get();
      foreach($locations as $location) {
        $lat2 = $location->address_latitude;
        $lon2 = $location->address_longitude;
        $dist = $this->postController->getDistance($post->getData()->lat, $post->getData()->lon, $lat2, $lon2);

        if ($dist > 50 || $count >= 5) {
          continue ;
        }
        $count++;
        $location->dist = $dist;
        $closet_locations[] = $location;
      }
    }

    usort($closet_locations, function($first,$second){
      return $first->dist > $second->dist;
    });

    $i = 0;
    $camps = array();
    foreach($closet_locations as $location) {
      $camp_data = DB::table('locations')
                  ->join('camps', 'locations.id', '=', 'camps.venue_id')
                  ->join('ages', 'camps.age_id', '=', 'ages.id')
                  ->join('workshops', 'camps.workshop_id', '=', 'workshops.id')
                  ->leftJoin('enrolments', 'enrolments.camp_id', '=', 'camps.id')
                  ->select('locations.name',
                            'camps.*',
                            DB::raw('DATEDIFF(camps.start_date, camps.end_date) as days'),
                            DB::raw('TIME_FORMAT(camps.start_time, "%h:%i%p") as startTime'),
                            DB::raw('TIME_FORMAT(camps.end_time, "%h:%i%p") as endTime'),
                            DB::raw('TIME_FORMAT(camps.kids_arrive_time, "%h:%i%p") as arriveTime'),
                            DB::raw('TIME_FORMAT(camps.kids_depart_time, "%h:%i%p") as departTime'),
                            'ages.age_desc as ages',
                            'workshops.name as topic',
                            'workshops.short_desc as topicDesc',
                            'workshops.image as topicImage',
                            DB::raw('COUNT(enrolments.id) as sold'))
                  ->where('locations.id', $location->id)
                  ->groupBy('camps.id')
                  ->get();
      $camp_data = array_filter($camp_data->toArray(), function($item){
        date_default_timezone_set("Australia/Sydney");
        $today = date('Y-m-d');
        $now_time = date('H');

        if ($item->start_date > $today) {
          return true;
        } else if ($item->start_date == $today && $now_time <= 8) {
          return true;
        }
        return false;
      });
      if (count($camp_data)) {
        $camps[] = array(
          'location' => $location,
          'camps' => $camp_data
        );
      }
    }
    if (!$camps || !count($camps)) {
      // return redirect('no-camps')->with('postcode', $post->getData()->postcode);
      return response()->json(null);
    } else {
      return response()->json($camps);
    }
  }

  public function getCampDetails(Request $request)
  {
    $id = $request->get('camp_id');
    $camp = DB::table('camps')
                  ->join('locations', 'locations.id', '=', 'camps.venue_id')
                  ->join('ages', 'camps.age_id', '=', 'ages.id')
                  ->join('workshops', 'camps.workshop_id', '=', 'workshops.id')
                  ->leftJoin('enrolments', 'enrolments.camp_id', '=', 'camps.id')
                  ->select('locations.name',
                            'camps.*',
                            DB::raw('DATEDIFF(camps.start_date, camps.end_date) as days'),
                            DB::raw('TIME_FORMAT(camps.start_time, "%h:%i%p") as startTime'),
                            DB::raw('TIME_FORMAT(camps.end_time, "%h:%i%p") as endTime'),
                            DB::raw('TIME_FORMAT(camps.kids_arrive_time, "%h:%i%p") as arriveTime'),
                            DB::raw('TIME_FORMAT(camps.kids_depart_time, "%h:%i%p") as departTime'),
                            'ages.age_desc as ages',
                            'workshops.name as topic',
                            'workshops.content',
                            'workshops.short_desc',
                            'workshops.long_desc',
                            'workshops.why_attend',
                            'workshops.image',
                            DB::raw('COUNT(enrolments.id) as sold'))
                  ->where('camps.id', $id)
                  ->groupBy('camps.id')
                  ->get();
    return response()->json($camp->toArray()[0]);
  }

  public function saveParentDetails(Request $request)
  {
    $pData = ParentDetail::where('email', $request->get('email'));
    $isExist = $pData->count();

    if ($isExist) {
      $pData->update([
        'first_name'=>$request->get('first_name'),
        'last_name'=>$request->get('last_name'),
        'email'=>$request->get('email'),
        'phone'=>$request->get('phone'),
        'postcode'=>$request->get('postcode'),
        'emergency_contact'=>$request->get('emergency_contact'),
        'heard_about'=>$request->get('heard_about'),
        'photos_permitted'=>$request->get('photos_permitted') ? $request->get('photos_permitted') : '',
      ]);
    } else {
      $pDetail = new ParentDetail;
      $pDetail->first_name = $request->get('first_name');
      $pDetail->last_name = $request->get('last_name');
      $pDetail->email = $request->get('email');
      $pDetail->phone = $request->get('phone');
      $pDetail->postcode = $request->get('postcode');
      $pDetail->emergency_contact = $request->get('emergency_contact');
      $pDetail->heard_about = $request->get('heard_about');
      $pDetail->photos_permitted = $request->get('photos_permitted');
      $pDetail->access_code = str_random(10);
      $pDetail->save();
    }

    $parent = ParentDetail::where('email', $request->get('email'));
    if ($parent->count()) {
      $parent = $parent->get()[0];
      return response()->json($parent->toArray());
    } else {
      return response()->json(['id'=>0]);
    }
  }

  public function saveChildDetails(Request $request)
  {
    $child = ChildDetail::where('first_name', $request->get('first_name'))
                            ->where('parent_id', '=', $request->get('parent_id'));
    $isExist = $child->count();
    //$input = array_filter($request->all(), 'strlen');
    $birthday = Carbon::create($request->get('birth_year'), $request->get('birth_month'), $request->get('birth_day'));

    if ($isExist) {
      $child->update([
        'first_name'=>$request->get('first_name'),
        'last_name'=>$request->get('last_name'),
        'date_of_birth'=>$birthday,
        'school'=>$request->get('school'),
        'allergies'=>$request->get('allergies') ?: '',
        'learning_difficulties'=>$request->get('learning_difficulties') ?: '',
        'parent_id'=>$request->get('parent_id'),
        'is_active'=>1
      ]);
    } else {
      $sDetail = new ChildDetail;
      $sDetail->first_name = $request->get('first_name');
      $sDetail->last_name = $request->get('last_name');
      $sDetail->date_of_birth = $birthday;
      $sDetail->school = $request->get('school');
      $sDetail->allergies = $request->get('allergies') ?: '';
      $sDetail->learning_difficulties = $request->get('learning_difficulties') ?: '';
      $sDetail->parent_id = $request->get('parent_id');
      $sDetail->is_active = 1;
      $sDetail->save();
    }

    $child = ChildDetail::where('first_name', $request->get('first_name'))
                          ->where('parent_id', '=', $request->get('parent_id'))
                          ->where('is_active', '=', 1);
    if ($child->count()) {
      $child = $child->get()[0];
      return response()->json($child->toArray());
    } else {
      return response()->json(['id'=>0]);
    }
  }

  public function getChildren(Request $request)
  {
    $children = ChildDetail::where('parent_id', $request->get('parent_id'))
                            ->where('is_active', 1)
                            ->get();
    if (count($children) > 0) {
      return response()->json(['status'=>'success', 'children'=>$children->toArray()]);
    } else {
      return response()->json(['status'=>'error']);
    }
  }

  public function deactiveChild(Request $request)
  {
    $children = ChildDetail::where('id', $request->get('id'));
    if ($children->count() > 0) {
      $children->update(['is_active' => 0]);
    }
    return response()->json(['status'=>'success']);
  }

  public function toNoCampPage(Request $request)
  {
    $postcode = $request->get('postcode');
    return redirect('https://url.learncode.com.au/no-camps-in-area/?lead-postcode='.$postcode);
  }
}
