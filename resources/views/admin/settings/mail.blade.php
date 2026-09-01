@extends('layouts.admin')
@include('partials/admin.settings.nav', ['activeTab' => 'mail'])

@section('title')
    Mail Settings
@endsection

@section('content-header')
    <nav class="cds--breadcrumb cds--breadcrumb--no-trailing-slash" aria-label="breadcrumb">
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.index') }}" class="cds--link">Admin</a></div>
        <div class="cds--breadcrumb-item"><span class="cds--link">Settings</span></div>
    </nav>
    <h1 class="cds--type-productive-heading-04">Mail Settings</h1>
    <p class="cds--type-body-compact-01">Configure how Pterodactyl should handle sending emails.</p>
@endsection

@section('content')
    @yield('settings::nav')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="ptero-tile__header">
                    <h3 class="cds--type-productive-heading-02">Email Settings</h3>
                </div>
                @if($disabled)
                    <div class="ptero-tile__body">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="cds--inline-notification cds--inline-notification--info no-margin-bottom">
                                    This interface is limited to instances using SMTP as the mail driver. Please either use <code>php artisan p:environment:mail</code> command to update your email settings, or set <code>MAIL_DRIVER=smtp</code> in your environment file.
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <form>
                        <div class="ptero-tile__body">
                            <div class="row">
                                <div class="cds--form-item col-md-6">
                                    <label class="cds--label">SMTP Host</label>
                                    <div>
                                        <input required type="text" class="cds--text-input" name="mail:mailers:smtp:host" value="{{ old('mail:mailers:smtp:host', config('mail.mailers.smtp.host')) }}" />
                                        <p class="cds--form__helper-text">Enter the SMTP server address that mail should be sent through.</p>
                                    </div>
                                </div>
                                <div class="cds--form-item col-md-2">
                                    <label class="cds--label">SMTP Port</label>
                                    <div>
                                        <input required type="number" class="cds--text-input" name="mail:mailers:smtp:port" value="{{ old('mail:mailers:smtp:port', config('mail.mailers.smtp.port')) }}" />
                                        <p class="cds--form__helper-text">Enter the SMTP server port that mail should be sent through.</p>
                                    </div>
                                </div>
                                <div class="cds--form-item col-md-4">
                                    <label class="cds--label">Encryption</label>
                                    <div>
                                        @php
                                            $encryption = old('mail:mailers:smtp:encryption', config('mail.mailers.smtp.encryption'));
                                        @endphp
                                        <select name="mail:mailers:smtp:encryption" class="cds--text-input cds--select-input">
                                            <option value="" @if($encryption === '') selected @endif>None</option>
                                            <option value="tls" @if($encryption === 'tls') selected @endif>Transport Layer Security (TLS)</option>
                                            <option value="ssl" @if($encryption === 'ssl') selected @endif>Secure Sockets Layer (SSL)</option>
                                        </select>
                                        <p class="cds--form__helper-text">Select the type of encryption to use when sending mail.</p>
                                    </div>
                                </div>
                                <div class="cds--form-item col-md-6">
                                    <label class="cds--label">Username <span class="field-optional"></span></label>
                                    <div>
                                        <input type="text" class="cds--text-input" name="mail:mailers:smtp:username" value="{{ old('mail:mailers:smtp:username', config('mail.mailers.smtp.username')) }}" />
                                        <p class="cds--form__helper-text">The username to use when connecting to the SMTP server.</p>
                                    </div>
                                </div>
                                <div class="cds--form-item col-md-6">
                                    <label class="cds--label">Password <span class="field-optional"></span></label>
                                    <div>
                                        <input type="password" class="cds--text-input" name="mail:mailers:smtp:password"/>
                                        <p class="cds--form__helper-text">The password to use in conjunction with the SMTP username. Leave blank to continue using the existing password. To set the password to an empty value enter <code>!e</code> into the field.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <hr />
                                <div class="cds--form-item col-md-6">
                                    <label class="cds--label">Mail From</label>
                                    <div>
                                        <input required type="email" class="cds--text-input" name="mail:from:address" value="{{ old('mail:from:address', config('mail.from.address')) }}" />
                                        <p class="cds--form__helper-text">Enter an email address that all outgoing emails will originate from.</p>
                                    </div>
                                </div>
                                <div class="cds--form-item col-md-6">
                                    <label class="cds--label">Mail From Name <span class="field-optional"></span></label>
                                    <div>
                                        <input type="text" class="cds--text-input" name="mail:from:name" value="{{ old('mail:from:name', config('mail.from.name')) }}" />
                                        <p class="cds--form__helper-text">The name that emails should appear to come from.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ptero-tile__footer">
                            {{ csrf_field() }}
                            <div class="pull-right">
                                <button type="button" id="testButton" class="cds--btn cds--btn--sm cds--btn--primary">Test</button>
                                <button type="button" id="saveButton" class="cds--btn cds--btn--sm cds--btn--primary">Save</button>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('footer-scripts')
    @parent

    <script>
        function saveSettings() {
            return $.ajax({
                method: 'PATCH',
                url: '/admin/settings/mail',
                contentType: 'application/json',
                data: JSON.stringify({
                    'mail:mailers:smtp:host': $('input[name="mail:mailers:smtp:host"]').val(),
                    'mail:mailers:smtp:port': $('input[name="mail:mailers:smtp:port"]').val(),
                    'mail:mailers:smtp:encryption': $('select[name="mail:mailers:smtp:encryption"]').val(),
                    'mail:mailers:smtp:username': $('input[name="mail:mailers:smtp:username"]').val(),
                    'mail:mailers:smtp:password': $('input[name="mail:mailers:smtp:password"]').val(),
                    'mail:from:address': $('input[name="mail:from:address"]').val(),
                    'mail:from:name': $('input[name="mail:from:name"]').val()
                }),
                headers: { 'X-CSRF-Token': $('input[name="_token"]').val() }
            }).fail(function (jqXHR) {
                showErrorDialog(jqXHR, 'save');
            });
        }

        function testSettings() {
            swal({
                type: 'info',
                title: 'Test Mail Settings',
                text: 'Click "Test" to begin the test.',
                showCancelButton: true,
                confirmButtonText: 'Test',
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            }, function () {
                $.ajax({
                    method: 'POST',
                    url: '/admin/settings/mail/test',
                    headers: { 'X-CSRF-TOKEN': $('input[name="_token"]').val() }
                }).fail(function (jqXHR) {
                    showErrorDialog(jqXHR, 'test');
                }).done(function () {
                    swal({
                        title: 'Success',
                        text: 'The test message was sent successfully.',
                        type: 'success'
                    });
                });
            });
        }

        function saveAndTestSettings() {
            saveSettings().done(testSettings);
        }

        function showErrorDialog(jqXHR, verb) {
            console.error(jqXHR);
            var errorText = '';
            if (!jqXHR.responseJSON) {
                errorText = jqXHR.responseText;
            } else if (jqXHR.responseJSON.error) {
                errorText = jqXHR.responseJSON.error;
            } else if (jqXHR.responseJSON.errors) {
                $.each(jqXHR.responseJSON.errors, function (i, v) {
                    if (v.detail) {
                        errorText += v.detail + ' ';
                    }
                });
            }

            swal({
                title: 'Whoops!',
                text: 'An error occurred while attempting to ' + verb + ' mail settings: ' + errorText,
                type: 'error'
            });
        }

        $(document).ready(function () {
            $('#testButton').on('click', saveAndTestSettings);
            $('#saveButton').on('click', function () {
                saveSettings().done(function () {
                    swal({
                        title: 'Success',
                        text: 'Mail settings have been updated successfully and the queue worker was restarted to apply these changes.',
                        type: 'success'
                    });
                });
            });
        });
    </script>
@endsection
