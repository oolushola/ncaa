<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\aoc;
use App\generalAviation;
use Auth;
use App\updateHistory;
use App\travelAgency;
use App\atol;

class atolController extends Controller
{
    public function index() {
        $aoclists = aoc::SELECT('id', 'aoc_holder')->ORDERBY('aoc_holder', 'ASC')->GET();
        $generalaviations = generalAviation::SELECT('id', 'general_aviation_name')->ORDERBY('general_aviation_name', 'ASC')->GET();
        $travelAgencies = travelAgency::SELECT('id', 'travel_agency_name')->ORDERBY('travel_agency_name', 'ASC')->GET();

        $atolListings = atol::paginate(15);

        return view('v1.ncaa.economics-licence.atol.create', compact('aoclists', 'generalaviations', 'travelAgencies', 'atolListings'));
    }

    public function store(Request $request) {
        $check = atol::WHERE('operator_type', $request->operator_type)->WHERE('licence_no', $request->licence_no)->exists();
        if($check) {
            return 'exists';
        }
        else{
            $atolRecord = atol::CREATE($request->all());
            $id = $atolRecord->id;
    		$recid=atol::findOrFail($id);
            if($request->hasFile('file')){
                $atol_certificate = $request->file('file');
                $name = "atolcertificate-".str_slug($request->operator_type).'.'.$atol_certificate->getClientOriginalExtension();
                $destination_path = public_path('confidentials/economic-licence/atol/');
                $atolPath = $destination_path."/".$name;
                $atol_certificate->move($destination_path, $name);
                $recid->atol_certificate = $name;
                $recid->save();
            }
        }

        return 'saved';
       
    }

    public function edit($id) {
        $aoclists = aoc::SELECT('id', 'aoc_holder')->ORDERBY('aoc_holder', 'ASC')->GET();
        $generalaviations = generalAviation::SELECT('id', 'general_aviation_name')->ORDERBY('general_aviation_name', 'ASC')->GET();
        $travelAgencies = travelAgency::SELECT('id', 'travel_agency_name')->ORDERBY('travel_agency_name', 'ASC')->GET();

        $atolListings = atol::paginate(15);

        $recid = atol::findOrFail($id);
        return view('v1.ncaa.economics-licence.atol.create', compact('aoclists', 'generalaviations', 'travelAgencies', 'atolListings', 'recid'));
    }

    public function update(Request $request, $id) {
        $check = atol::WHERE('operator_type', $request->operator_type)->WHERE('licence_no', $request->licence_no)->WHERE('id', '<>', $id)->exists();
        if($check) {
            return 'exists';
        }
        else{
            $recid = atol::findOrFail($id);
            $atolRecord = $recid->UPDATE($request->all());
            if($request->hasFile('file')){
                $atol_certificate = $request->file('file');
                $name = "atolcertificate-".str_slug($request->operator_type).'.'.$atol_certificate->getClientOriginalExtension();
                $destination_path = public_path('confidentials/economic-licence/atol/');
                $atolPath = $destination_path."/".$name;
                $atol_certificate->move($destination_path, $name);
                $recid->atol_certificate = $name;
                $recid->save();
            }
        }

        return 'updated';
    }

    public function show() {
        $atollisting = atol::ORDERBY('operator_type', 'ASC')->GET();
        return view('v1.ncaa.economics-licence.atol.show', compact('atollisting'));
    }
}
