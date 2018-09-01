<?php

namespace App\Http\Controllers\Camps;

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
      $results[] = [ 'id'=>$query->id, 'post_code'=>$query->postcode, 'text'=>$query->suburb.' '.$query->postcode ];
    }
    return response()->json($results);
  }

  public function getCoordinate($id)
  {
    $result = DB::table('postcodes')->where('id', $id)->get();
    if (count($result)) {
      return response()->json($result[0]);
    }
    return null;
  }

  function calcDistance($latitude1, $longitude1, $latitude2, $longitude2)
  {
    $diff = $longitude1 - $longitude2;
    $dist = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($diff)));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $dist_unit = 111.325; // Kilometers per degree latitude constant
    $dist = $dist * $dist_unit;
    return (round($dist));
  }

  //Calculates distance in KM between postcodes
  function getDistance($postcode1,$postcode2, $suburb1 = '', $suburb2 = '')
  {
    //Get lat and lon for postcode 1
    $extra = "";
    if ($suburb1 != '') {
      $extra = " and suburb = '$suburb1'";
    }
    $loc1 = DB::table('postcodes')
                ->where('lat', '<>', 0)
                ->where('lon', '<>', 0)
                ->where('postcode', $postcode1)
                ->where('suburb', $suburb1)
                ->get();

    $extra = "";
    if ($suburb2 != '') {
      $extra = " and suburb = '$suburb2'";
    }

    $loc2 = DB::table('postcodes')
                ->where('lat', '<>', 0)
                ->where('lon', '<>', 0)
                ->where('postcode', $postcode2)
                ->where('suburb', $suburb2)
                ->get();

    if ($loc1.length != 0 && $loc2.length != 0) {
      //proceed
      $dist = calcDistance($loc1[0].lat, $loc1[0].lon, $loc2[0].lat, $loc2[0].lon);
      if (is_numeric($dist)) {
        return $dist;
      } else {
        return "Unknown";
      }
    } else {
      return "Unknown";
    }
  }
}
