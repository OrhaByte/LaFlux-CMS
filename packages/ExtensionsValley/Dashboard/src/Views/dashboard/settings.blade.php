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
                        <h2>Site Settings</h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li class="pull-right"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <form action="{{ route('extensionsvalley.admin.updatesettings') }}" method="POST" enctype="multipart/form-data">
                        @csrf


                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group {{ $errors->has('site_name') ? 'has-error' : '' }} control-required">
                                    <label for="site_name">Site Name</label> <span class="error_red">*</span>
                                    <input type="text" name="settings[site_name]" id="site_name" value="{{ old('settings.site_name', \WebConf::get('site_name')) }}" class="form-control" placeholder="Your Site Name" required tabindex="1">
                                    <span class="error_red"> {{ $errors->first('site_name') }}</span>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group {{ $errors->has('copy_right') ? 'has-error' : '' }} control-required">
                                    <label for="copy_right">Copy Right </label> <span class="error_red">*</span>
                                    <input type="text" name="settings[copy_right]" id="copy_right" value="{{ old('settings.copy_right', \WebConf::get('copy_right')) }}" class="form-control" placeholder="Copy right info" required tabindex="2">
                                    <span class="error_red">{{ $errors->first('copy_right') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <div class="form-group {{ $errors->has('site_logo') ? 'has-error' : '' }} control-required">
                                    <label for="site_logo">Logo</label> (jpeg,jpg,png,gif 1MB)
                                    <input type="file" name="site_logo" id="site_logo" accept="image/jpeg,image/jpg,image/png,image/gif">
                                    <span class="error_red">{{ $errors->first('site_logo') }}</span>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <div class="form-group ">
                                    @if(\WebConf::get('site_logo') != "")
                                        <img src="{{URL::to('/')}}/{{ \WebConf::get('site_logo') }}" width="80"
                                             alt="logo"/>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <div class="form-group {{ $errors->has('fav_icon') ? 'has-error' : '' }} control-required">
                                    <label for="fav_icon">Fav Icon </label> (ico 1MB)
                                    <input type="file" name="fav_icon" id="fav_icon" accept=".ico,image/jpg,image/png,image/gif">
                                    <span class="error_red"> {{ $errors->first('fav_icon') }}</span>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <div class="form-group ">
                                    @if(\WebConf::get('fav_icon') != "")
                                        <img src="{{URL::to('/')}}/{{ \WebConf::get('fav_icon') }}" width="64"
                                             alt="logo"/>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group {{ $errors->has('meta_title') ? 'has-error' : '' }} control-required">
                                    <label for="meta_title">SEO Title</label> <span class="error_red">*</span>
                                    <input type="text" name="settings[meta_title]" id="meta_title" value="{{ old('settings.meta_title', \WebConf::get('meta_title')) }}" class="form-control" placeholder="SEO Title" required tabindex="5">
                                    <span class="error_red"> {{ $errors->first('meta_title') }}</span>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group {{ $errors->has('meta_desc') ? 'has-error' : '' }} control-required">
                                    <label for="meta_desc">SEO Description </label> <span class="error_red">*</span>
                                    <input type="text" name="settings[meta_desc]" id="meta_desc" value="{{ old('settings.meta_desc', \WebConf::get('meta_desc')) }}" class="form-control" placeholder="SEO Description" required tabindex="6">
                                    <span class="error_red">{{ $errors->first('meta_desc') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group {{ $errors->has('meta_keywords') ? 'has-error' : '' }} control-required">
                                    <label for="meta_keywords">SEO Keywords</label> <span class="error_red">*</span>
                                    <input type="text" name="settings[meta_keywords]" id="meta_keywords" value="{{ old('settings.meta_keywords', \WebConf::get('meta_keywords')) }}" class="form-control" placeholder="SEO Keywords eg: Books,shops,blogs etc" required tabindex="7">
                                    <span class="error_red"> {{ $errors->first('meta_keywords') }}</span>
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

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2><i class="fa fa-exchange"></i> Find &amp; Replace URLs <small>Local to Production Migration Tool</small></h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li class="pull-right"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <p class="text-muted">
                            Use this tool when moving your site between environments (e.g. local to production) to update stored URLs across Admin ACL sidebar links, CMS menus, posts, pages, media paths, and settings.
                        </p>

                        @if(Session::has('url_replace_report'))
                            <div class="alert {{ Session::get('url_replace_dry_run') ? 'alert-info' : 'alert-success' }}">
                                <strong>{{ Session::get('url_replace_dry_run') ? 'Dry Run Preview:' : 'Replacement Summary:' }}</strong>
                                <ul style="margin: 6px 0 0 18px;">
                                    @foreach(Session::get('url_replace_report') as $row)
                                        <li><strong>{{ $row['label'] }}</strong> (<code>{{ $row['table'] }}</code>): {{ $row['count'] }} matching field(s)</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('extensionsvalley.admin.replaceurls') }}" method="POST" onsubmit="return confirmUrlReplace();">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group {{ $errors->has('find_url') ? 'has-error' : '' }}">
                                        <label for="find_url">Find URL <span class="error_red">*</span></label>
                                        <input type="text" name="find_url" id="find_url" class="form-control"
                                               value="{{ old('find_url', 'http://localhost/laravel-admin/public') }}"
                                               placeholder="http://localhost/laravel-admin/public" required>
                                        <span class="error_red">{{ $errors->first('find_url') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group {{ $errors->has('replace_url') ? 'has-error' : '' }}">
                                        <label for="replace_url">Replace With URL <span class="error_red">*</span></label>
                                        <input type="text" name="replace_url" id="replace_url" class="form-control"
                                               value="{{ old('replace_url', url('/')) }}"
                                               placeholder="https://yourdomain.com" required>
                                        <span class="error_red">{{ $errors->first('replace_url') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Tables to Update</label>
                                <div class="row">
                                    @php
                                        $defaultTargets = old('targets', ['acl_permission', 'cms_menu_items', 'cms_posts', 'cms_pages', 'cms_categories', 'cms_media', 'gen_settings']);
                                        $targetOptions = [
                                            'acl_permission' => 'Admin Menus & ACL Permissions (acl_permission)',
                                            'cms_menu_items' => 'CMS Menu Items (cms_menu_items)',
                                            'cms_posts' => 'CMS Posts Content & Featured Images (cms_posts)',
                                            'cms_pages' => 'CMS Pages Content & Featured Images (cms_pages)',
                                            'cms_categories' => 'CMS Categories Description (cms_categories)',
                                            'cms_media' => 'CMS Media Paths (cms_media)',
                                            'gen_settings' => 'General Settings (gen_settings)',
                                        ];
                                    @endphp
                                    @foreach($targetOptions as $tKey => $tLabel)
                                        <div class="col-md-4 col-sm-6">
                                            <label style="font-weight:normal; cursor:pointer;">
                                                <input type="checkbox" name="targets[]" value="{{ $tKey }}" {{ in_array($tKey, (array) $defaultTargets) ? 'checked' : '' }}>
                                                {{ $tLabel }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                <span class="error_red">{{ $errors->first('targets') }}</span>
                            </div>

                            <div class="form-group">
                                <label style="cursor:pointer;">
                                    <input type="checkbox" name="dry_run" id="dry_run" value="1" {{ old('dry_run', '1') ? 'checked' : '' }}>
                                    <strong>Dry Run</strong> <small class="text-muted">(Preview how many fields match without modifying the database)</small>
                                </label>
                            </div>

                            <button type="submit" class="btn btn-warning">
                                <i class="fa fa-refresh"></i> Run Find &amp; Replace
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function confirmUrlReplace() {
            var isDryRun = document.getElementById('dry_run').checked;
            if (isDryRun) return true;
            return confirm('Are you sure you want to permanently replace URLs in the selected database tables? (Tip: back up your database first)');
        }
    </script>
@stop
