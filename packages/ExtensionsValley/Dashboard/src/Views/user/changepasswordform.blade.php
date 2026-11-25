@extends('Dashboard::dashboard.dashboard')
@section('content-main')
    <div id="wrapper">
        <!-- Navigation Starts-->
    @include('Dashboard::dashboard.partials.navigationbar')
    <!-- Navigation Ends-->
    </div>
@stop
@section('content-area')
    <div id="page-wrapper">
        <!-- Dashboard title Starts -->
    @include('Dashboard::dashboard.partials.dashboardtitlesection')
    <!-- Dashboard title Ends -->
        <?php
        $action = 'extensionsvalley.admin.updateuserpassword';
        ?>
        <form action="{{ route($action) }}" method="POST">
        @csrf
        <div class="row">

            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <div class="form-group control-required">
                    <label for="oldpassword">Current Password</label>
                    <input type="password" name="oldpassword" id="oldpassword" class="form-control" required/>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <div class="form-group control-required">
                    <label for="newpassword">New Password</label>
                    <input type="password" name="newpassword" id="newpassword" class="form-control" required/>

                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <div class="form-group control-required">
                    <label for="confirmpassword">Confirm Password</label>
                    <input type="password" name="confirmpassword" id="confirmpassword" class="form-control" required/>
                </div>
            </div>

        </div>


        @if(isset($user->id))
            <Input type="hidden" name="user_id" value="{{$user->id}}"/>
        @endif
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
        </form>
    </div>
@stop

