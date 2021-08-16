<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\TeamMembers;
use App\Http\Requests;
use Illuminate\Http\HttpResponse;
use Validator;


class TeamMembersController extends Controller
{
    public function index(){
        $teamMemberListings = TeamMembers::ORDERBY('last_name', 'ASC')->PAGINATE(15);
        return view('v1.ncaa.gop.team-members.create', compact('teamMemberListings'));
    }

    public function store(Request $request){
        TeamMembers::CREATE([
            'title' => $request->title,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'full_name' => $request->title.' '.$request->first_name.' '.$request->last_name,
            'created_by' => $request->created_by
        ]);
        return 'saved';
    }
    public function edit($id){
        $teamMemberListings = TeamMembers::ORDERBY('last_name', 'ASC')->PAGINATE(15);
        $recid = TeamMembers::findOrFail($id);
        return view('v1.ncaa.gop.team-members.create', compact('teamMemberListings', 'recid'));
    }

    public function update(Request $request, $id){
        $recid = TeamMembers::findOrFail($id);
        $recid->title = $request->title;
        $recid->first_name = $request->first_name;
        $recid->last_name = $request->last_name;
        $recid->full_name = $request->title.' '.$request->first_name.' '.$request->last_name;
        $recid->save();
        return 'updated';
    }
    public function destroy($id) {
        $recid = TeamMembers::findOrFail($id);
        $recid->DELETE();
        return 'deleted';
    }
}