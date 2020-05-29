@extends('v1.ncaa.design-layout')

@section('title') UPDATE Foreign AMO @stop

@section('main')
    <div class="page-header">
        <h3 class="page-title">
            AMO - Update
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{URL('amo-foreign')}}">AMO Foreign</a></li>
            <li class="breadcrumb-item active" aria-current="page">Update AMO</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-5 grid-margin">
            <div class="card">
            <div class="card-body">
                <form name="frmForeignAmo" id="frmForeignAmo" method="POST" action="{{URL('/amo-foreign', $recid->id)}}">
                {!! csrf_field() !!} {!! method_field('PATCH') !!}
                    <div class="form-group">
                        <label class="labelholder">AMO Holder *</label>
                        <select class="form-control" name="amo_holder" id="amo_holder">
                            <option value="">Choose Amo Holder</option>
                            @foreach($foreignamoholderlist as $foreignamo)
                                @if($foreignamo->id == $recid->amo_holder)
                                <option value="{{$foreignamo->id}}" selected>{{$foreignamo->foreign_amo_holder}}</option>
                                @else
                                <option value="{{$foreignamo->id}}">{{$foreignamo->foreign_amo_holder}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="labelholder">Country *</label>
                        <select class="form-control" name="regional_country_id" id="regional_country_id">
                            <option value="">Choose Country</option>
                            @foreach($countrylists as $countries)
                                @if($recid->regional_country_id == $countries->regional_country_id)
                                    <option value="{{$countries->regional_country_id}}" selected>{{$countries->country}}</option>
                                @else
                                    <option value="{{$countries->regional_country_id}}">{{$countries->country}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="labelholder">MOE Reference</label>
                        <input type="text" class="form-control" name="moe_reference" id="moe_reference" value="{!! $recid->moe_reference !!}">
                    </div>
                    <div class="form-group">
                        <label class="labelholder">Approvals</label>
                        <textarea class="form-control" name="approvals" id="approvals" placeholder="Approvals" >{!! $recid->approvals !!}</textarea>
                    </div>
                    <div class="form-group">
                    <label for="ratings" class="labelholder">Ratings/Capabilities (optional)</label>
                        <textarea type="text" class="form-control textarea" placeholder="" name="ratings_capabilities" id="ratings_capabilities">{!! $recid->ratings_capabilities !!}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="c-of-a status" class="labelholder">AMO Number *</label>
                        <input type="text" name="amo_number" id="amo_number" class="form-control" value="{!! $recid->amo_number !!}">                    
                    </div>
                    <div class="form-group">
                        <label for="amo" class="labelholder">Upload AMO </label>
                        <input type="file" name="amo" id="file" style="font-size:12px; display:inline-block" disabled>
                        <i class="mdi mdi-lock-open-outline" id="unlockAmo" title="Unlock AMO Foreign upload"></i>

                        <input type="hidden" name="filecheck" id="filecheck" value="0">
                        <input type="hidden" name="ftype" id="ftype" value="pdf">
                        <input type="hidden" id="amoFileChecker" value="0">
                    </div>
                    
                    <div class="form-group">
                        <?php
                             $converdatetotime = strtotime($recid->expiry); 
                             $choosendate = date('Y-m-d', $converdatetotime);
                        ?>
                        <label class="labelholder">Expiry *</label>
                        <input type="date" class="form-control" id="expiry" name="expiry" value="{!! $choosendate !!}">
                    </div>

                    <div id="loader"></div>
                    <input type="hidden" name="name" value="{{Auth::user()->name}}">
                    <input type="hidden" name="module" value="amo-foreign">
                    <input type="hidden" name="actual"value="{{$recid->amo_holder}}">
                    <input type="hidden" name="record_id" value="{{$recid->id}}">                    
                            
                    <button type="submit" class="btn btn-gradient-primary mr-2" id="updateForeignAmo">UPDATE</button>
                    <button class="btn btn-light">Cancel</button>
                </form>
            </div>
            </div>
        </div>
        <!-- partials -->
        @include('v1.ncaa.amo.foreign.partials._listings')
        
    </div>

@stop



@section('scripts')
<script type="text/javascript" src="{{URL::asset('js/jquery.form.js')}}"></script>
  <script type="text/javascript" src="{{URL::asset('js/mediavalidate.js')}}"></script>
  <script type="text/javascript" src="{{URL::asset('js/amo/amo-foreign.js')}}"></script>
  <script type="text/javascript" src="{{URL::asset('js/tinymce/tinymce.min.js')}}"></script>  
  <script type="text/javascript">
    tinyMCE.init({
    mode:'exact',
    force_br_newlines:true,
    force_p_newlines:true, 
    forced_root_block : '',selector :'.textarea'
    });
  </script>
@stop