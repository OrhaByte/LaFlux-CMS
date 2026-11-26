@extends('Dashboard::dashboard.dashboard')
@section('content-header')
    @include('Dashboard::dashboard.partials.headersidebar')
@stop
@section('content-area')
    <div class="right_col" role="main">
        <div class="row">
            <div class="col-md-12">
                <div class="x_panel">
                    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
                        <div>
                            <h2 style="margin:0;"><i class="fa fa-sitemap"></i> {{ $title }}</h2>
                            <small class="text-muted">Automatically includes Home, Pages, Posts, Categories, and Tags for all active languages.</small>
                        </div>
                        <div style="display:flex; gap:8px; align-items:center;">
                            <a href="{{ url('/sitemap.xml') }}" target="_blank" class="btn btn-default btn-sm">
                                <i class="fa fa-external-link"></i> Open /sitemap.xml
                            </a>
                            <form action="{{ route('extensionsvalley.cms.sitemap.generate') }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fa fa-refresh"></i> Generate Static sitemap.xml
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>URL Breakdown by Language <small>Total: {{ count($entries) }} URLs</small></h2>
                        <div class="pull-right" style="padding-top:6px; font-size:12px;">
                            @if($staticExists)
                                <span class="label label-success">Static file generated: {{ $staticUpdated }}</span>
                            @else
                                <span class="label label-info">Served dynamically via /sitemap.xml</span>
                            @endif
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>Language</th>
                                <th>Code</th>
                                <th>Pages</th>
                                <th>Posts</th>
                                <th>Categories</th>
                                <th>Tags</th>
                                <th>Total URLs</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($stats as $st)
                                <tr>
                                    <td><strong>{{ $st['name'] }}</strong></td>
                                    <td><span class="label label-primary">{{ $st['code'] }}</span></td>
                                    <td>{{ $st['pages'] }}</td>
                                    <td>{{ $st['posts'] }}</td>
                                    <td>{{ $st['categories'] }}</td>
                                    <td>{{ $st['tags'] }}</td>
                                    <td><strong>{{ $st['total'] }}</strong></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>XML Preview</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <pre style="max-height:360px; overflow:auto; background:#0f172a; color:#e2e8f0; border-radius:8px; padding:16px; font-size:12px;">{{ $xmlPreview }}</pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

