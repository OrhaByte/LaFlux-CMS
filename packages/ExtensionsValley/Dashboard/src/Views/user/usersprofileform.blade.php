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
        $action = 'extensionsvalley.admin.updateusersprofile';
        ?>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Password Change</h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li class="pull-right"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <form action="{{ route('extensionsvalley.admin.updateuserpassword') }}" method="POST" enctype="multipart/form-data">
                        @csrf


                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                <div class="form-group {{ $errors->has('current_password') ? 'has-error' : '' }} control-required">
                                    <label for="current_password">Current Password</label> <span class="error_red">*</span>
                                    <input type="password" name="current_password" id="current_password" class="form-control" required/>
                                    {{ $errors->first('current_password') }}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }} control-required">
                                    <label for="password">New Password</label> <span class="error_red">*</span>
                                    <input type="password" name="password" id="password" class="form-control" required/>
                                    {{ $errors->first('password') }}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                <div class="form-group {{ $errors->has('confirm_password') ? 'has-error' : '' }} control-required">
                                    <label for="password_confirmation">Confirm Password</label> <span class="error_red">*</span>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required/>
                                    {{ $errors->first('confirm_password') }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 ">
                                <a href="javascript:;" onclick="history.go(-1);" class="btn btn-success">Clear</a>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <div class="x_panel">
            <div class="x_title">
                <h2>Basic Profile</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li class="pull-right"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <form action="{{ route($action) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">

                    <?php
                    $path = "packages/extensionsvalley/dashboard/images/profile/" . \Auth::User()->id;
                    $media = isset($usersprofile->media) ? $usersprofile->media : "";
                    ?>
                    <div class='col-xs-12 col-sm-12 col-md-6 col-lg-6' style="height: 150px;">
                        <div class='form-group control-required'>
                            @if($media == "")
                                <center><img src='{{URL::to('/')}}/packages/extensionsvalley/dashboard/images/profile/user.png'
                                             width='150px' height='150px'/></center>
                            @else
                                <center><img src='{{URL::to('/')}}/{{ $media }}' width='150px'/></center>
                            @endif
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group {{ $errors->has('address_1') ? 'has-error' : '' }} control-required">
                            <label for="address">Address</label> <span class="error_red">*</span>
                            <input type="text" name="address" id="address" value="{{ old('address', isset($usersprofile->address) ? $usersprofile->address : '') }}" class="form-control" placeholder="Address" required tabindex="4">
                            <span class="error_red"> {{ $errors->first('address') }}</span>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group {{ $errors->has('street') ? 'has-error' : '' }} control-required">
                            <label for="street">Street</label> <span class="error_red">*</span>
                            <input type="text" name="street" id="street" value="{{ old('street', isset($usersprofile->street) ? $usersprofile->street : '') }}" class="form-control" placeholder="Street" required tabindex="5">
                            <span class="error_red"> {{ $errors->first('street') }}</span>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group {{ $errors->has('address_2') ? 'has-error' : '' }} control-required">
                            <label for="city">City</label> <span class="error_red">*</span>
                            <input type="text" name="city" id="city" value="{{ old('city', isset($usersprofile->city) ? $usersprofile->city : '') }}" class="form-control" placeholder="City" required tabindex="6">
                            <span class="error_red"> {{ $errors->first('city') }}</span>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group {{ $errors->has('photo') ? 'has-error' : '' }}">
                            Upload or Change your photo (max:1 MB)
                            <input type="file" name="photo" id="photo" accept="image/jpeg,image/jpg,image/png,image/gif">
                            <span class="error_red">  {{ $errors->first('photo') }}</span>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group {{ $errors->has('state') ? 'has-error' : '' }} control-required">
                            <label for="state">State</label> <span class="error_red">*</span>
                            <input type="text" name="state" id="state" value="{{ old('state', isset($usersprofile->state) ? $usersprofile->state : '') }}" class="form-control" placeholder="State" required tabindex="7">
                            <span class="error_red">  {{ $errors->first('state') }}</span>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        <div class="form-group {{ $errors->has('first_name') ? 'has-error' : '' }} control-required">
                            <label for="first_name">First Name</label> <span class="error_red">*</span>
                            <input type="text" name="first_name" id="first_name" value="{{ old('first_name', isset($usersprofile->first_name) ? $usersprofile->first_name : '') }}" class="form-control" placeholder="First Name" required tabindex="1">
                            <span class="error_red"> {{ $errors->first('first_name') }}</span>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        <div class="form-group {{ $errors->has('last_name') ? 'has-error' : '' }} control-required">
                            <label for="last_name">Last Name</label> <span class="error_red">*</span>
                            <input type="text" name="last_name" id="last_name" value="{{ old('last_name', isset($usersprofile->last_name) ? $usersprofile->last_name : '') }}" class="form-control" placeholder="Last Name" required tabindex="2">
                            <span class="error_red"> {{ $errors->first('last_name') }}</span>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group {{ $errors->has('pin') ? 'has-error' : '' }} control-required">
                            <label for="zip">Zip</label> <span class="error_red">*</span>
                            <input type="text" name="zip" id="zip" value="{{ old('zip', isset($usersprofile->zip) ? $usersprofile->zip : '') }}" class="form-control" placeholder="Zip code" required tabindex="8">
                            <span class="error_red"> {{ $errors->first('zip') }}</span>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }} control-required">
                            <label for="email">Email</label> <span class="error_red">*</span>
                            <input type="email" name="email" id="email" value="{{ old('email', empty(old('email')) ? \Auth::guard('admin')->user()->email : old('email')) }}" class="form-control" placeholder="Email address" required tabindex="3">
                            <span class="error_red"> {{ $errors->first('email') }}</span>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group {{ $errors->has('Mobile') ? 'has-error' : '' }} control-required">
                            <label for="mobile">Mobile</label> <span class="error_red">*</span>
                            <input type="text" name="mobile" id="mobile" value="{{ old('mobile', isset($usersprofile->mobile) ? $usersprofile->mobile : '') }}" class="form-control" placeholder="Mobile" required tabindex="9">
                            <span class="error_red"> {{ $errors->first('mobile') }}</span>
                        </div>
                    </div>


                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <a href="javascript:;" onclick="history.go(-1);" class="btn btn-success">Clear</a>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
                </form>
            </div>
        </div>


    </div>
@stop
