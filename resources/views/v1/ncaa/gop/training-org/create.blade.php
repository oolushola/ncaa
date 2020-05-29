@extends('v1.ncaa.design-layout')

@section('title') Training Organizations @stop

@section('main')
    <div class="page-header">
        <h3 class="page-title">
            Training Organization  <span id="errContainer"></span>
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <!-- <li class="breadcrumb-item"><a href="{{URL('training-organization')}}">Training Organization</a></li> -->
            <li class="breadcrumb-item active" aria-current="page">Add Training Organization</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-5 grid-margin">
            <div class="card">
                <div class="card-body">
                    <form method="POST" name="frmTrainingOrganization" id="frmTrainingOrganization">
                    {!! csrf_field() !!}
                    @if(isset($recid))
                        {!! method_field('PATCH') !!}
                    @endif
                        
                        <div class="form-group">
                            <label class="labelholder">Training Organization *</label>
                            <input type="text" name="training_organization" id="training_organization" class="form-control" value="<?php if(isset($recid)){echo $recid->training_organization;}else{echo ""; } ?>">
                        </div>
                        <div class="form-group">
                            <label class="labelholder">Description <sub>(optional)</sub></label>
                            <textarea name="description" id="description" class="form-control"><?php if(isset($recid)){echo $recid->description;}else{echo ""; } ?></textarea>
                        </div>

                        @if(isset($recid))
                            <input type="hidden" name="id" id="id" value="{{$recid->id}}">
                        @endif
                        
                        @if(isset($recid))
                            <button type="submit" class="btn btn-gradient-primary mr-2" id="updateTrainingOrganization">UPDATE</button>
                        @else      
                            <button type="submit" class="btn btn-gradient-primary mr-2" id="addTrainingOrganization">SAVE</button>
                        @endif
                        <input type="reset" class="btn btn-light" value="Cancel">

                        <div id="loader"></div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-7 grid-margin stretch-card">
            <div class="card">
                <div class="card-body" style="padding:4px;">
                    <h4 class="card-title">Preview Pane</h4>
                    <span id="deleteLoader" style="display:block"></span>
                    {{ $trainingOrganizationListings->links() }}
                    <table class="table table-bordered" width="100%">
                        <thead class="table-info">
                            <tr class="table-warning">
                                <th colspan="4" style="font-size:11px; font-weight:bold">Training Organization Lists</th>
                            </tr>
                            <tr>
                                <th width="5%" style="font-size:11px; font-weight:bold">#</th>
                                <th width="85%" style="font-size:11px; font-weight:bold">Training Organization</th>
                                <th width="5%" style="font-size:11px; font-weight:bold; text-align:center">Edit</th>
                                <th width="5%" style="font-size:11px; font-weight:bold; text-align:center">Remove</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($trainingOrganizationListings))
                            <?php $count = 0; ?>
                                @foreach($trainingOrganizationListings as $trainingOrganization)
                                <?php $count++; 
                                    $count % 2 == 0 ? $css = 'table-primary' : $css = 'table-secondary';
                                ?>
                                    <tr class="{{$css}}">
                                        <td>{{$count}}</td>
                                        <td>{{strtoupper($trainingOrganization->training_organization)}}</td>
                                        <td>
                                            <a href="{{URL('training-organization/'.$trainingOrganization->id.'/edit')}}">
                                                <i class="mdi mdi-pen" style="font-size:25px;"></i>
                                            </a>
                                        </td>
                                        <td style="color:red">
                                            <form method="POST" name="frmDeleteTrainingOrganization" id="frmDeleteTrainingOrganization">
                                                {!! csrf_field() !!} {!! method_field('DELETE') !!}
                                                <i class="mdi mdi-delete-forever deleteTrainingOrganization" style="font-size:25px; cursor:pointer" title="{{$trainingOrganization->training_organization}}" value="{{$trainingOrganization->id}}"></i>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                            <tr class="table-danger">
                                <td colspan="6" style="font-size:11px; font-weight:bold">No training organization has been added yet.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>                
                </div>
            </div>
        </div>
        
    </div>

@stop

@section('scripts')
<script type="text/javascript" src="{{URL::asset('js/gop/training-organization.js')}}"></Script>
@stop