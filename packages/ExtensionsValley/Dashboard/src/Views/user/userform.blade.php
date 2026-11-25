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
        if (isset($user)) {
            $action = 'extensionsvalley.admin.updateuser';
        } else {
            $action = 'extensionsvalley.admin.saveuser';
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
                        <input type="text" name="name" id="name" value="{{ old('name', isset($user->name) ? $user->name : '') }}" class="form-control" placeholder="User Full Name" required {{ $readonly }}>
                        <span class="error_red">{{ $errors->first('name') }}</span>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }} control-required">
                        <label for="email">Email</label> <span class="error_red">*</span>
                        <input type="email" name="email" id="email" value="{{ old('email', isset($user->email) ? $user->email : '') }}" class="form-control" placeholder="Email" required {{ $readonly }}>
                        <span class="error_red">{{ $errors->first('email') }}</span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }} control-required">
                        <label for="password">Password</label>
                        @if(!isset($user->id))<span class="error_red">*</span> @endif
                        <Input type="password" name="password" id="password" {{$readonly}} class="form-control"
                               @if(!isset($user->id)) required @endif />
                        <span class="error_red">{{ $errors->first('password') }}</span>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <div class="form-group {{ $errors->has('password_confirmation') ? 'has-error' : '' }} control-required">
                        <label for="password_confirmation">Confirm Password</label>
                        @if(!isset($user->id))<span class="error_red">*</span> @endif
                        <Input type="password" name="password_confirmation" {{$readonly}} id="password_confirmation"
                               class="form-control"
                               @if(!isset($user->id)) required @endif/>
                        <span class="error_red">{{ $errors->first('password_confirmation') }}</span>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <div class="form-group {{ $errors->has('groups') ? 'has-error' : '' }} control-required">
                        <label for="groups">User Groups</label> <span class="error_red">*</span>
                        <select name="groups" id="groups" class="form-control select2" required>
                            @foreach($groups as $key => $value)
                                <option value="{{ $key }}" {{ (old('groups', isset($user->groups) ? $user->groups : null) == $key) ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }} control-required">
                        <label for="status">Status</label> <span class="error_red">*</span>
                        <select name="status" id="status" class="form-control select2" required>
                            <option value="1" {{ (old('status', isset($user->status) ? $user->status : null) == '1') ? 'selected' : '' }}>Publish</option>
                            <option value="0" {{ (old('status', isset($user->status) ? $user->status : null) == '0') ? 'selected' : '' }}>Unpublish</option>
                        </select>
                    </div>
                </div>

            </div>

            @if(isset($user->id))
                <Input type="hidden" name="user_id" value="{{$user->id}}"/>
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

