@extends('Dashboard::dashboard.dashboard')
@section('content-header')
    @include('Dashboard::dashboard.partials.headersidebar')
@stop
@section('content-area')
    <div class="right_col" role="main">
        <div class="row">
            <div class="col-md-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2><i class="fa fa-inbox"></i> {{ $title }}</h2>
                        <div class="pull-right">
                            <a href="{{ url('admin/ExtensionsValley/cms/list/cms_forms') }}" class="btn btn-sm btn-default">&larr; Back to Forms</a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        @if($submissions->isEmpty())
                            <p class="text-muted">No submissions received for this form yet.</p>
                        @else
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th width="60">#</th>
                                    <th>Submitted Data</th>
                                    <th width="160">Date &amp; IP</th>
                                    <th width="70">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($submissions as $sub)
                                    <tr>
                                        <td>{{ $sub->id }}</td>
                                        <td>
                                            @foreach((array) $sub->payload as $key => $val)
                                                <div style="margin-bottom:4px;">
                                                    <strong>{{ ucwords(str_replace('_', ' ', $key)) }}:</strong>
                                                    {{ is_array($val) ? implode(', ', $val) : $val }}
                                                </div>
                                            @endforeach
                                        </td>
                                        <td>
                                            {{ $sub->created_at ? $sub->created_at->format('M j, Y H:i') : '' }}<br>
                                            <small class="text-muted">{{ $sub->ip_address }}</small>
                                        </td>
                                        <td>
                                            <form action="{{ route('extensionsvalley.cms.deleteformsubmission', ['id' => $sub->id]) }}" method="POST" onsubmit="return confirm('Delete this submission?');">
                                                @csrf
                                                <button type="submit" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div>{{ $submissions->links() }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

