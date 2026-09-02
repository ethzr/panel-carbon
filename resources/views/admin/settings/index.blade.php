@extends('layouts.admin')
@include('partials/admin.settings.nav', ['activeTab' => 'basic'])

@section('title')
    Settings
@endsection

@section('content-header')
    <nav class="cds--breadcrumb cds--breadcrumb--no-trailing-slash" aria-label="breadcrumb">
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.index') }}" class="cds--link">Admin</a></div>
        <div class="cds--breadcrumb-item"><span class="cds--link">Settings</span></div>
    </nav>
    <h1 class="cds--type-productive-heading-04">Panel Settings</h1>
    <p class="cds--type-body-compact-01">Configure Pterodactyl to your liking.</p>
@endsection

@section('content')
    @yield('settings::nav')
    <div class="row">
        <div class="col-xs-12">
            <div class="cds--tile ptero-tile">
                <div class="ptero-tile__header">
                    <h3 class="cds--type-productive-heading-02">Panel Settings</h3>
                </div>
                <form action="{{ route('admin.settings') }}" method="POST">
                    <div class="ptero-tile__body">
                        <div class="row">
                            <div class="cds--form-item col-md-4">
                                <label class="cds--label">Company Name</label>
                                <div>
                                    <input type="text" class="cds--text-input" name="app:name" value="{{ old('app:name', config('app.name')) }}" />
                                    <p class="text-muted"><small>This is the name that is used throughout the panel and in emails sent to clients.</small></p>
                                </div>
                            </div>
                            <div class="cds--form-item col-md-4">
                                <label class="cds--label">Require 2-Factor Authentication</label>
                                <div>
                                    <div class="ptero-radio-set">
                                        @php
                                            $level = old('pterodactyl:auth:2fa_required', config('pterodactyl.auth.2fa_required'));
                                        @endphp
                                        <label>
                                            <input type="radio" name="pterodactyl:auth:2fa_required" value="0" @if ($level == 0) checked @endif>
                                            Not Required
                                        </label>
                                        <label>
                                            <input type="radio" name="pterodactyl:auth:2fa_required" value="1" @if ($level == 1) checked @endif>
                                            Admin Only
                                        </label>
                                        <label>
                                            <input type="radio" name="pterodactyl:auth:2fa_required" value="2" @if ($level == 2) checked @endif>
                                            All Users
                                        </label>
                                    </div>
                                    <p class="text-muted"><small>If enabled, any account falling into the selected grouping will be required to have 2-Factor authentication enabled to use the Panel.</small></p>
                                </div>
                            </div>
                            <div class="cds--form-item col-md-4">
                                <label class="cds--label">Default Language</label>
                                <div>
                                    <select name="app:locale" class="cds--text-input cds--select-input">
                                        @foreach($languages as $key => $value)
                                            <option value="{{ $key }}" @if(config('app.locale') === $key) selected @endif>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-muted"><small>The default language to use when rendering UI components.</small></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ptero-tile__footer">
                        {!! csrf_field() !!}
                        <button type="submit" name="_method" value="PATCH" class="cds--btn cds--btn--sm cds--btn--primary pull-right">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
