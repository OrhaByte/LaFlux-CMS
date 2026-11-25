@extends('Theme::layout')

@php
    $tpl = $templateData ?? [];
    $formHtml = !empty($tpl['form_slug'])
        ? \ExtensionsValley\Cms\Helpers\ShortcodeHelper::render('[form slug="' . e($tpl['form_slug']) . '"]')
        : '';
@endphp

@section('hero')
    <section class="lf-page-banner">
        <div class="lf-container">
            <h1>{{ $page->title }}</h1>
            @if(!empty($tpl['subtitle']))
                <p>{{ $tpl['subtitle'] }}</p>
            @endif
        </div>
    </section>
@endsection

@section('content')
    <section class="lf-section">
        <div class="lf-container">
            <div class="lf-grid-2" style="align-items:start;">
                <div>
                    <div class="lf-card" style="margin-bottom:22px;">
                        <div class="lf-card-body">
                            @shortcode($page->content)
                        </div>
                    </div>

                    <div class="lf-card">
                        <div class="lf-card-body">
                            <h3 style="margin-top:0;">Contact Information</h3>
                            @if(!empty($tpl['contact_email']))
                                <p style="margin:0 0 10px 0;"><strong>Email:</strong> <a href="mailto:{{ $tpl['contact_email'] }}">{{ $tpl['contact_email'] }}</a></p>
                            @endif
                            @if(!empty($tpl['contact_phone']))
                                <p style="margin:0 0 10px 0;"><strong>Phone:</strong> {{ $tpl['contact_phone'] }}</p>
                            @endif
                            @if(!empty($tpl['contact_address']))
                                <p style="margin:0;"><strong>Address:</strong> {{ $tpl['contact_address'] }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div>
                    @if(!empty(trim($formHtml)))
                        {!! $formHtml !!}
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

