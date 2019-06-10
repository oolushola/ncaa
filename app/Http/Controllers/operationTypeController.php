<?php

namespace App\Http\Controllers;

use App\operations;
use App\Http\Requests;
use App\Http\Requests\operationsRequest;
use Illuminate\Http\HttpResponse;
use Illuminate\Http\Request;
use Auth;

class operationTypeController extends Controller
{
    public function index(){
        if(Auth::check() && Auth::user()->role){
            $operationLists = operations::LATEST('created_at')->GET();
            return view('v1.ncaa.gop.operation-type.create', compact('operationLists'));
        }
        return redirect()->route('login');
    }
    public function store(operationsRequest $request){
        if(Auth::check() && Auth::user()->role){
            $check = operations::WHERE('operation_type', $request->get('operation_type'))->exists();
            if($check){
                return 'exists';
            }
            else{
                operations::CREATE($request->all());
                return 'saved';
            }
        }
        return redirect()->route('login');
    }
    public function edit($id){
        if(Auth::check() && Auth::user()->role){
            $recid = operations::findOrFail(base64_decode($id));
            $operationLists = operations::LATEST('created_at')->GET();
            return view('v1.ncaa.gop.operation-type.edit', compact('operationLists', 'recid'));
        }
        return redirect()->route('login');

    }
    public function update(operationsRequest $request, $id){
        if(Auth::check() && Auth::user()->role){
            $check = operations::WHERE('operation_type', $request->get('operation_type'))->WHERE('id', '<>', $id)->exists();
            if($check){
                return 'exists';
            }
            else{
                $recid = operations::findOrFail($id);
                $recid->UPDATE($request->all());
                return 'updated';
            }
        }
        return redirect()->route('login');
    }
}
