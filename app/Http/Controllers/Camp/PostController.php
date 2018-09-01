<?php

namespace App\Http\Controllers\Camp;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Http\Requests;
use App\Http\Responses;

class PostController extends Controller
{
  public function getPostCodes(Request $request)
  {
    $term = $request->get('term');
    $results = array();
    $queries = DB::table('postcodes')
              ->where('postcode', 'LIKE', '%'.$term.'%')
              ->orWhere('suburb', 'LIKE', '%'.$term.'%')
              ->take(10)->get();
    foreach ($queries as $query) {
      $results[] = [ 'id'=>$query->id, 'post_code'=>$query->postcode, 'suburb'=>$query->suburb, 'state'=>$query->state, 'text'=>$query->suburb.' '.$query->postcode ];
    }
    return response()->json($results);
  }

  public function getPostData($id)
  {
    $result = DB::table('postcodes')->where('id', $id)->get();
    if (count($result)) {
      return response()->json($result[0]);
    }
    return null;
  }

  function getDistance($latitude1, $longitude1, $latitude2, $longitude2)
  {
    $diff = $longitude1 - $longitude2;
    $dist = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($diff)));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $dist_unit = 111.325; // Kilometers per degree latitude constant
    $dist = $dist * $dist_unit;
    return (round($dist));
  }
}
