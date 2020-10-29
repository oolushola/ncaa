<?php

namespace App\Http\Controllers;
use App\foreignAmoHolder;
use App\foreignamo;
use App\Http\Requests;
use App\Http\Requests\foreignAmoHolderRequest;
use Illuminate\Http\HttpResponse;
use Illuminate\Http\Request;
use Auth;

class foreignAmoHolderController extends Controller
{
   public function index(){
      if(Auth::check() && Auth::user()->role){
          $foreignamoholderlists = foreignAmoHolder::ORDERBY('foreign_amo_holder', 'ASC')->PAGINATE(15);
          return view('v1.ncaa.gop.foreign-amo-holder.create', compact('foreignamoholderlists'));    
      }
      else{
          return redirect()->route('login');
      }
  }
  public function store(foreignAmoHolderRequest $request){
      if(Auth::check() && Auth::user()->role){
          $check = foreignAmoHolder::WHERE('foreign_amo_holder', $request->get('foreign_amo_holder'))->exists();
        if($check){
           return 'exists';
        }
        else{
           foreignAmoHolder::CREATE($request->all());
              return "save";
        }
      }
      else{
          return redirect()->route('login');
      }
  }
  public function edit($id){
      if(Auth::check() && Auth::user()->role){
          $recid = foreignAmoHolder::findOrFail(base64_decode($id));
          $foreignamoholderlists = foreignAmoHolder::ORDERBY('foreign_amo_holder', 'ASC')->PAGINATE(15);
          return view('v1.ncaa.gop.foreign-amo-holder.create', compact('foreignamoholderlists','recid'));
      }
      return redirect()->route('login');
  }
  public function update(foreignAmoHolderRequest $request, $id){
      if(Auth::check() && Auth::user()->role){
          $check = foreignAmoHolder::WHERE('foreign_amo_holder', $request->get('foreign_amo_holder'))->WHERE('id', '<>', $id)->exists();
          if ($check>0){
              return "exists";
          }else{
              $foreignamoholder = foreignAmoHolder::findOrFail($id);
              $foreignamoholder->update($request->all());
              return "updated";
          }
      }
      else{
          return redirect()->route('login');
      }
  }

  public function destroy($id)
  {
      if(Auth::check() && Auth::user()->role){
          $checkForeignAmoHolder = foreignamo::WHERE('amo_holder', $id)->exists();
          if($checkForeignAmoHolder)
          {
             return 'cant_delete';
          }
          $recid = foreignAmoHolder::findOrFail($id);
          $recid->DELETE();
          return 'deleted';
      }
      else {
        return redirect()->route('login');
      }
  }
}
