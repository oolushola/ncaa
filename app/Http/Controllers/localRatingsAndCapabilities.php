<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\localamo;
use App\aircrafttype;
use App\aircraftMaker;
use Illuminate\Support\Facades\DB;


class localRatingsAndCapabilities extends Controller
{
    public function assign($id) {

       $recid = localamo::findOrFail($id);
       $aircraftMakerList = aircraftMaker::ORDERBY('aircraft_maker', 'ASC')->GET();
       $panelquery = 'SELECT a.*, b.aircraft_type FROM tbl_ncaa_local_amo_ratings a JOIN tbl_ncaa_aircraft_types b ON a.aircraft_type_id = b.id WHERE local_amo_id = '.$id.'';
        
       $panelLists = DB::SELECT(DB::RAW($panelquery));
       return view('v1.ncaa.amo.local.ratingsandcapabilities',
            compact(
                'recid',
                'aircraftMakerList',
                'panelLists'
            ) 
       );
    }

    public function listaircrafttypes(Request $request) {
        $aircraft_maker_id = $request->get('aircraft_maker_id');
        $local_amo_id = $request->id;

        $query = 'SELECT * FROM tbl_ncaa_aircraft_types WHERE aircraft_maker_id = '.$aircraft_maker_id.' AND id NOT IN(SELECT aircraft_type_id FROM tbl_ncaa_local_amo_ratings WHERE aircraft_maker_id = '.$aircraft_maker_id.' and local_amo_id = '.$local_amo_id.')';
        $aircraftTypeList = DB::SELECT(DB::RAW($query));
        
            $result ='';        

            foreach($aircraftTypeList as $aircraftType) {
                $result.='<div class="col-md-3" style="font-size:11px; margin-top:10px; font-family:tahoma">
                <input type="checkbox" name="aircraft_type[]" value='.$aircraftType->id.'>
                '.$aircraftType->aircraft_type.'</div>';
            }

        return $result;
    }

    public function storeRatings(Request $request){
        $local_amo_id = $request->id;
        $aircraft_maker_id = $request->aircraft_maker_id;
        $aircraftType = $request->aircraft_type;

        foreach($aircraftType as $aircraft_type_id) {
            $query = 'INSERT INTO tbl_ncaa_local_amo_ratings (local_amo_id, aircraft_maker_id, aircraft_type_id) VALUES ('.$local_amo_id.', '.$aircraft_maker_id.', '.$aircraft_type_id.')';
            DB::INSERT(DB::RAW($query));
        }
        return 'saved';
    }

    public function deleteLocalRatings(Request $request) {
        $aircraft_type = $request->aircraft_type;
        foreach($aircraft_type as $aircraft_type_id) {
            $query = 'DELETE FROM tbl_ncaa_local_amo_ratings WHERE id = '.$aircraft_type_id.'';
            DB::DELETE(DB::RAW($query));
        }
        return 'deleted';
    }
}
