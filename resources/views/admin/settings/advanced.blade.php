@extends('layouts.admin')
@include('partials/admin.settings.nav', ['activeTab' => 'advanced'])

@section('title')
    Advanced Settings
@endsection

@section('content-header')
    <nav class="cds--breadcrumb cds--breadcrumb--no-trailing-slash" aria-label="breadcrumb">
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.index') }}" class="cds--link">Admin</a></div>
        <div class="cds--breadcrumb-item"><span class="cds--link">Settings</span></div>
    </nav>
    <h1 class="cds--type-productive-heading-04">Advanced Settings</h1>
    <p class="cds--type-body-compact-01">Configure advanced settings for Pterodactyl.</p>
@endsection

@section('content')
    @yield('settings::nav')
    <div class="row">
        <div class="col-xs-12">
            <form action="" method="POST">
                <div class="box">
                    <div class="ptero-tile__header">
                        <h3 class="cds--type-productive-heading-02">reCAPTCHA</h3>
                    </div>
                    <div class="ptero-tile__body">
                        <div class="row">
                            <div class="cds--form-item col-md-4">
                                <label class="cds--label">Status</label>
                                <div>
                                    <select class="cds--text-input cds--select-input" name="recaptcha:enabled">
                                        <option value="true">Enabled</option>
                                        <option value="false" @if(old('recaptcha:enabled', config('recaptcha.enabled')) == '0') selected @endif>Disabled</option>
                                    </select>
                                    <p class="cds--form__helper-text">If enabled, login forms and password reset forms will do a silent captcha check and display a visible captcha if needed.</p>
                                </div>
                            </div>
                            <div class="cds--form-item col-md-4">
                                <label class="cds--label">Site Key</label>
                                <div>
                                    <input type="text" required class="cds--text-input" name="recaptcha:website_key" value="{{ old('recaptcha:website_key', config('recaptcha.website_key')) }}">
                                </div>
                            </div>
                            <div class="cds--form-item col-md-4">
                                <label class="cds--label">Secret Key</label>
                                <div>
                                    <input type="text" required class="cds--text-input" name="recaptcha:secret_key" value="{{ old('recaptcha:secret_key', config('recaptcha.secret_key')) }}">
                                    <p class="cds--form__helper-text">Used for communication between your site and Google. Be sure to keep it a secret.</p>
                                </div>
                            </div>
                        </div>
                        @if($showRecaptchaWarning)
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="cds--inline-notification cds--inline-notification--warning no-margin">
                                        You are currently using reCAPTCHA keys that were shipped with this Panel. For improved security it is recommended to <a href="https://www.google.com/recaptcha/admin">generate new invisible reCAPTCHA keys</a> that tied specifically to your website.
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="box">
                    <div class="ptero-tile__header">
                        <h3 class="cds--type-productive-heading-02">HTTP Connections</h3>
                    </div>
                    <div class="ptero-tile__body">
                        <div class="row">
                            <div class="cds--form-item col-md-6">
                                <label class="cds--label">Connection Timeout</label>
                                <div>
                                    <input type="number" required class="cds--text-input" name="pterodactyl:guzzle:connect_timeout" value="{{ old('pterodactyl:guzzle:connect_timeout', config('pterodactyl.guzzle.connect_timeout')) }}">
                                    <p class="cds--form__helper-text">The amount of time in seconds to wait for a connection to be opened before throwing an error.</p>
                                </div>
                            </div>
                            <div class="cds--form-item col-md-6">
                                <label class="cds--label">Request Timeout</label>
                                <div>
                                    <input type="number" required class="cds--text-input" name="pterodactyl:guzzle:timeout" value="{{ old('pterodactyl:guzzle:timeout', config('pterodactyl.guzzle.timeout')) }}">
                                    <p class="cds--form__helper-text">The amount of time in seconds to wait for a request to be completed before throwing an error.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box">
                    <div class="ptero-tile__header">
                        <h3 class="cds--type-productive-heading-02">Automatic Allocation Creation</h3>
                    </div>
                    <div class="ptero-tile__body">
                        <div class="row">
                            <div class="cds--form-item col-md-4">
                                <label class="cds--label">Status</label>
                                <div>
                                    <select class="cds--text-input cds--select-input" name="pterodactyl:client_features:allocations:enabled">
                                        <option value="false">Disabled</option>
                                        <option value="true" @if(old('pterodactyl:client_features:allocations:enabled', config('pterodactyl.client_features.allocations.enabled'))) selected @endif>Enabled</option>
                                    </select>
                                    <p class="cds--form__helper-text">If enabled users will have the option to automatically create new allocations for their server via the frontend.</p>
                                </div>
                            </div>
                            <div class="cds--form-item col-md-4">
                                <label class="cds--label">Starting Port</label>
                                <div>
                                    <input type="number" class="cds--text-input" name="pterodactyl:client_features:allocations:range_start" value="{{ old('pterodactyl:client_features:allocations:range_start', config('pterodactyl.client_features.allocations.range_start')) }}">
                                    <p class="cds--form__helper-text">The starting port in the range that can be automatically allocated.</p>
                                </div>
                            </div>
                            <div class="cds--form-item col-md-4">
                                <label class="cds--label">Ending Port</label>
                                <div>
                                    <input type="number" class="cds--text-input" name="pterodactyl:client_features:allocations:range_end" value="{{ old('pterodactyl:client_features:allocations:range_end', config('pterodactyl.client_features.allocations.range_end')) }}">
                                    <p class="cds--form__helper-text">The ending port in the range that can be automatically allocated.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="cds--tile ptero-tile">
                    <div class="ptero-tile__footer">
                        {{ csrf_field() }}
                        <button type="submit" name="_method" value="PATCH" class="cds--btn cds--btn--sm cds--btn--primary pull-right">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
