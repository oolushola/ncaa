<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\updateHistory;
use App\CertificationTracker;
use Illuminate\Support\Facades\DB;
use App\Cpm;
use App\TeamMembers;
use App\aircrafttype;
use App\ApplicantName;
use App\CertificationType;

class CertificationTrackerController extends Controller
{
    public function index() 
    {
        if (Auth::check()) {
            $cpms = Cpm::GET();
            $teamMembers = TeamMembers::GET();
            $aircraftTypes = aircrafttype::GET();
            $certificationTrackers = CertificationTracker::PAGINATE(15);
            $applicantNames = ApplicantName::ORDERBY('applicant_name', 'ASC')->GET();
            $certificationTypes = CertificationType::ORDERBY('certification_type', 'ASC')->GET();
            return view('v1.ncaa.certification-tracker.create', compact('cpms', 'teamMembers', 'aircraftTypes', 'certificationTrackers', 'applicantNames', 'certificationTypes'));
        }
        else {
            return redirect()->route('login');
        }
    }

    public function store(Request $request) {
        if(Auth::check()) {
            $this->validate($request, [
                'certification_no' => 'required|string',
                'date_assigned' => 'required|string',
                'applicant_name' => 'required|string',
                'certification_type' => 'required|string',
                'cpm' => 'required|string',
                'team_member' => 'required|string',
                'start_date' => 'required|string',
                'completion_date' => 'required|string',
                'status' => 'required|string',
                'aircraft_type' => 'required|string',
            ]);

            $check = CertificationTracker::WHERE('certification_no', $request->certification_no)->exists();
            if($check)
            {
                return 'exists';
            }   
            else {
                CertificationTracker::CREATE($request->all());
                // $id = $recid->id;
                // if($request->hasFile('dacl_certificate')){
                //     $this->uploader($request, $request->dacl_no, 'dacl_certificate', 'dacl/', 'confidentials/foreign-airline/dacl',  $recid, 'dacl_certificate');
                // }
                // $recid->save();
                return 'saved';
            }
        }
        else {
            return redirect()->back();
        }
    }

    public function uploader($request, $file, $fileName, $namePrefix, $dir, $obj, $saveTo) {
        $fileName = $request->file($fileName);
        $name = "$namePrefix".str_slug($file).'.'.$fileName->getClientOriginalExtension();
        $destination_path = public_path($dir);
        $path = $destination_path."/".$name;
        $fileName->move($destination_path, $name);
        $obj->$saveTo = $name;
    }

    public function edit($id) {
        if (Auth::check()) {
            $recid = CertificationTracker::findOrFail($id);
            $cpms = Cpm::GET();
            $teamMembers = TeamMembers::GET();
            $aircraftTypes = aircrafttype::GET();
            $certificationTrackers = CertificationTracker::PAGINATE(15);
            $applicantNames = ApplicantName::ORDERBY('applicant_name', 'ASC')->GET();
            $certificationTypes = CertificationType::ORDERBY('certification_type', 'ASC')->GET();
            return view('v1.ncaa.certification-tracker.create', compact('cpms', 'teamMembers', 'aircraftTypes', 'certificationTrackers', 'recid', 'applicantNames', 'certificationTypes'));
        }
        else {
            return redirect()->route('login');
        }
    }

    public function update(Request $request, $id) {
        if(Auth::check()) {
            $this->validate($request, [
                'certification_no' => 'required|string',
                'date_assigned' => 'required|string',
                'applicant_name' => 'required|string',
                'certification_type' => 'required|string',
                'cpm' => 'required|string',
                'team_member' => 'required|string',
                'start_date' => 'required|string',
                'completion_date' => 'required|string',
                'status' => 'required|string',
                'aircraft_type' => 'required|string',
            ]);

            // if($request->hasFile('dacl_certificate')){
            //     $this->validate($request, [
            //         'dacl_certificate' => 'required|max:2048',
            //     ]);
            // }

            $check = CertificationTracker::WHERE('certification_no', $request->certification_no)->WHERE('id', '<>', $id)->exists();
            if($check)
            {
                return 'exists';
            }   
            else {
                $recid = CertificationTracker::findOrFail($id);
                $recid->UPDATE($request->all());
                // if($request->hasFile('dacl_certificate')){
                //     $this->uploader($request, $request->dacl_no, 'dacl_certificate', 'dacl/', 'confidentials/foreign-airline/dacl', $recid, 'dacl_certificate');
                // }
                updateHistory::CREATE([
                    'name' => Auth::user()->name, 'module' => 'certification-tracker', 'record_id' => $id, 'actual' => $recid->certification_no
                ]);
                $recid->save();
                return 'updated';
            }
        }
        return redirect()->route('login');
    }

    public function show() 
    {
        $certificationTrackerListings = CertificationTracker::GET();
        $checkfordaclupdates = updateHistory::WHERE('module', 'certification-tracker')->ORDERBY('updated_at', 'DESC')->LIMIT(1)->GET();

        return view('v1.ncaa.certification-tracker.show', compact('certificationTrackerListings', 'checkfordaclupdates'));
    }

    public function destroy(Request $request, $id) {
        $recid = CertificationTracker::findOrFail($id);
        $recid->DELETE();
        updateHistory::CREATE([
            'name' => Auth::user()->name, 'module' => 'certification-tracker', 'record_id' => $id, 'actual' => 'DELETED:'.$recid->certification_no
        ]);
        return 'deleted';
    }
}