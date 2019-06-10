@extends('v1.ncaa.design-layout')

@section('title') Assign Operation Specification to AOC Holder @stop

@section('main')
<form method="POST" name="frmAssignOperationToAoc" id="frmAssignOperationToAoc">
    {{csrf_field()}}
    <div class="page-header">
        <h3 class="page-title">
            Assign Operation Specs to AOC Holder
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">AOC</a></li>
            <li class="breadcrumb-item active" aria-current="page">Opreration Spec</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">AOC Holders</h4>
                    <p class="card-description" style="font-size:11px; font-weight:bold; color:green">Please choose an AOC holder before assigning an Operation Specs</p>
                    <div class="form-group">
                        <select class="form-control" id="aoc_holder_id" name="aoc_holder_id">
                        <option value="">Select an AOC Holder</option>
                        @foreach($aocHolderLists as $aoc)
                                <option value="{{$aoc->id}}">{{$aoc->aoc_holder}}</option>
                        @endforeach
                        </select>
                        <div id="loader"></div>
                        <input type="hidden" id="checkboxValidator" name="checkboxValidator" value="0">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" id="contentLoader">
        <div class="col-md-5 grid-margin">
            <div class="card" style="border-radius:0">
            <div class="card-body" style="padding:0;">
                <table class="table table-bordered" width="100%">
                    <thead>
                        <tr class="table-info">
                            <td colspan="4" style="font-size:12px; font-weight:bold">All available listings of Operation Specification</td>
                        </tr>
                        <tr class="table-warning">
                            <td style="font-size:12px; font-weight:bold">#</td>
                            <td><input type="checkbox" id="selectAllLeftchkbx"></td>
                            <td style="font-size:12px; font-weight:bold" colspan="4" id="selectAllLeftTxt">Select all available operation specification listings</td>
                        </tr>
                        
                    </thead>
                    <tbody>
                        @if(count($operationsLists))
                            <?php $counter = 0; ?>
                            @foreach($operationsLists as $operation)
                            <?php
                                $counter+=1;
                                $counter % 2 == 0 ? $css_style = 'table-primary' : $css_style = 'table-secondary';
                            ?>
                                <tr class="{{$css_style}}">
                                    <td style="font-size:12px;">{{$counter}}</td>
                                    <td style="font-size:12px;">
                                        <input type="checkbox" class="operationTypeLeftChkbx" name="operation_type[]"value="{{$operation->id}}">
                                    </td>
                                    <td style="font-size:12px;">{{$operation->operation_type}}</td>
                                </tr>
                            @endforeach
                        @else

                        @endif
                    </tbody>
                </table> 
            </div>
            </div>
        </div>
        <div class="col-md-2 grid-margin">
            <div class="card" style="background:none">
                <div class="card-body">
                    <button type="button" id="assignoperationSpecs" class="btn btn-gradient-primary btn-rounded btn-icon" style="margin:5px;" title="Add Operation Specification to AOC Holder">
                    <i class="mdi mdi-thumb-up"></i>
                    </button>
                    <button type="button" id="removeOprSpecs" class="btn btn-gradient-danger btn-rounded btn-icon" style="margin:5px;" title="Remove Operation Specification from AOC Holder">
                            <i class="mdi mdi-thumb-down"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="col-md-5 grid-margin">
            <div class="card" style="border-radius:0">
            <div class="card-body" style="padding:0;">
                <table class="table table-bordered" width="100%">
                    <thead>
                        <tr class="table-info">
                            <td colspan="4" style="font-size:12px; font-weight:bold">All assigned Operation Specification</td>
                        </tr>
                        <tr class="table-warning">
                            <td style="font-size:12px; font-weight:bold">#</td>
                            <td><input type="checkbox"></td>
                            <td style="font-size:12px; font-weight:bold" colspan="4">All assigned operation specification</td>
                        </tr>
                        
                    </thead>
                    <tbody>
                        <tr class="table-success">
                            <td style="font-size:12px; line-height:15px;" colspan="4">You've not assigned any operation specification to this AOC Holder</td>
                        </tr>
                    </tbody>
                </table> 
            </div>
            </div>
        </div>
    </div>
        
    
    
</form>

@stop


@section('scripts')
<script type="text/javascript" src="{{URL::asset('js/aoc/assign-operations-to-aoc.js')}}"></script>
@stop