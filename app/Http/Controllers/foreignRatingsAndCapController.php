<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\aircrafttype;
use App\aircraftMaker;
use App\foreignamo;
use Illuminate\Support\Facades\DB;
use App\foreignAmoHolder;


class foreignRatingsAndCapController extends Controller
{
    public function assign($id){
        $aircraftMakerlists = aircraftMaker::ORDERBY('aircraft_maker', 'ASC')->GET();
        $aircrafttypelists = aircrafttype::ORDERBY('aircraft_type', 'ASC')->GET();
        
        $panelquery = 'SELECT a.*, b.aircraft_type FROM tbl_ncaa_foreign_amo_ratings a JOIN tbl_ncaa_aircraft_types b ON a.aircraft_type_id = b.id WHERE foreign_amo_id = '.$id.'';
        
        $panelLists = DB::SELECT(DB::RAW($panelquery));

        $recid = foreignamo::findOrFail($id);
        $amo_holder_id = $recid->amo_holder;
        $amoholderName = foreignAmoHolder::findOrFail($amo_holder_id);

        // $getforeignHolderName = 
        return view('v1.ncaa.amo.foreign.ratingsandcapabilities', compact(
            'aircraftMakerlists',
            'aircrafttypelists',
            'recid',
            'panelLists',
            'amoholderName'
        ));
    }
    public function listaircrafttypes(Request $request) {
        $aircraft_maker_id = $request->get('aircraft_maker_id');
        $foreign_amo_id = $request->id;
        // $aircraftTypeList = aircrafttype::WHERE('aircraft_maker_id', $aircraft_maker_id)->ORDERBY('aircraft_type', 'ASC')->GET();

        $query = 'SELECT * FROM tbl_ncaa_aircraft_types WHERE aircraft_maker_id = '.$aircraft_maker_id.' AND id NOT IN(SELECT aircraft_type_id FROM tbl_ncaa_foreign_amo_ratings WHERE aircraft_maker_id = '.$aircraft_maker_id.' and foreign_amo_id = '.$foreign_amo_id.')';
        $aircraftTypeList = DB::SELECT(DB::RAW($query));
        
            $result ='';        

            foreach($aircraftTypeList as $aircraftType) {
                $result.='<div class="col-md-3" style="font-size:11px; margin-top:10px; font-family:tahoma">
                <input type="checkbox" name="aircraft_type[]" value='.$aircraftType->id.'>
                '.$aircraftType->aircraft_type.'</div>';
            }

        return $result;
    }

    public function storeassign(Request $request){
        $foreign_amo_id = $request->id;
        $aircraft_maker_id = $request->aircraft_maker_id;
        $aircraftType = $request->aircraft_type;

        foreach($aircraftType as $aircraft_type_id) {
            $query = 'INSERT INTO tbl_ncaa_foreign_amo_ratings (foreign_amo_id, aircraft_maker_id, aircraft_type_id) VALUES ('.$foreign_amo_id.', '.$aircraft_maker_id.', '.$aircraft_type_id.')';
            DB::INSERT(DB::RAW($query));
        }
        return 'saved';
    }

    public function deleteForeignRatings(Request $request) {
        $aircraft_type = $request->aircraft_type;
        foreach($aircraft_type as $aircraft_type_id) {
            $query = 'DELETE FROM tbl_ncaa_foreign_amo_ratings WHERE id = '.$aircraft_type_id.'';
            DB::DELETE(DB::RAW($query));
        }
        return 'deleted';
    }
}
