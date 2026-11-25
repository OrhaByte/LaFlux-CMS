@extends('Dashboard::dashboard.dashboard')
@section('content-header')

    <!-- Navigation Starts-->
    @include('Dashboard::dashboard.partials.headersidebar')
    <!-- Navigation Ends-->

@stop
@section('content-area')
    <div class="right_col" role="main">

        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                    <h2>{{$title}}</h2>
                </div>
            </div>
        </div>
        <?php
        if (isset($group)) {
            $action = 'extensionsvalley.admin.updategroup';
        } else {
            $action = 'extensionsvalley.admin.savegroup';
        }
        if (isset($viewmode)) {
            $readonly = "readonly";
        } else {
            $readonly = "";
        }

        ?>
        <div class="x_panel">
            <form action="{{ route($action) }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }} control-required">
                        <label for="name">Name</label> <span class="error_red">*</span>
                        <input type="text" name="name" id="name" value="{{ old('name', isset($group->name) ? $group->name : '') }}" class="form-control" placeholder="Name" required {{ $readonly }}>
                        <span class="error_red">{{ $errors->first('name') }}</span>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }} control-required">
                        <label for="status">Status</label> <span class="error_red">*</span>
                        <select name="status" id="status" class="form-control select2" required>
                            <option value="1" {{ (old('status', isset($group->status) ? $group->status : null) == '1') ? 'selected' : '' }}>Publish</option>
                            <option value="0" {{ (old('status', isset($group->status) ? $group->status : null) == '0') ? 'selected' : '' }}>Unpublish</option>
                        </select>
                    </div>
                </div>
            </div>

            @if(isset($group->id))
                <Input type="hidden" name="group_id" value="{{$group->id}}"/>
            @endif
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <a onclick="history.go(-1);" class="btn btn-success">Cancel</a>
                    @if(!isset($viewmode))
                        <button type="submit" class="btn btn-primary">Submit</button>
                    @endif
                </div>
            </div>
              <input type="hidden" name="accesstoken" value="{{request()->has('accesstoken') ? request()->get('accesstoken') : ''}}" />

            </form>
        </div>
    </div>
@stop

