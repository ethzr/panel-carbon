@extends('layouts.admin')

@section('title')
    Create User
@endsection

@section('content-header')
    <nav class="cds--breadcrumb cds--breadcrumb--no-trailing-slash" aria-label="breadcrumb">
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.index') }}" class="cds--link">Admin</a></div>
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.users') }}" class="cds--link">Users</a></div>
        <div class="cds--breadcrumb-item"><span class="cds--link">Create</span></div>
    </nav>
    <h1 class="cds--type-productive-heading-04">Create User</h1>
    <p class="cds--type-body-compact-01">Add a new user to the system.</p>
@endsection

@section('content')
<div class="row">
    <form method="post">
        <div class="col-md-6">
            <div class="cds--tile ptero-tile">
                <div class="ptero-tile__header">
                    <h3 class="cds--type-productive-heading-02">Identity</h3>
                </div>
                <div class="ptero-tile__body">
                    <div class="cds--form-item">
                        <label for="email" class="cds--label">Email</label>
                        <div>
                            <input type="text" autocomplete="off" name="email" value="{{ old('email') }}" class="cds--text-input" />
                        </div>
                    </div>
                    <div class="cds--form-item">
                        <label for="username" class="cds--label">Username</label>
                        <div>
                            <input type="text" autocomplete="off" name="username" value="{{ old('username') }}" class="cds--text-input" />
                        </div>
                    </div>
                    <div class="cds--form-item">
                        <label for="name_first" class="cds--label">Client First Name</label>
                        <div>
                            <input type="text" autocomplete="off" name="name_first" value="{{ old('name_first') }}" class="cds--text-input" />
                        </div>
                    </div>
                    <div class="cds--form-item">
                        <label for="name_last" class="cds--label">Client Last Name</label>
                        <div>
                            <input type="text" autocomplete="off" name="name_last" value="{{ old('name_last') }}" class="cds--text-input" />
                        </div>
                    </div>
                    <div class="cds--form-item">
                        <label class="cds--label">Default Language</label>
                        <div>
                            <select name="language" class="cds--text-input cds--select-input">
                                @foreach($languages as $key => $value)
                                    <option value="{{ $key }}" @if(config('app.locale') === $key) selected @endif>{{ $value }}</option>
                                @endforeach
                            </select>
                            <p class="text-muted"><small>The default language to use when rendering the Panel for this user.</small></p>
                        </div>
                    </div>
                </div>
                <div class="ptero-tile__footer">
                    {!! csrf_field() !!}
                    <input type="submit" value="Create User" class="cds--btn cds--btn--primary cds--btn--sm">
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="cds--tile ptero-tile">
                <div class="ptero-tile__header">
                    <h3 class="cds--type-productive-heading-02">Permissions</h3>
                </div>
                <div class="ptero-tile__body">
                    <div class="cds--form-item col-md-12">
                        <label for="root_admin" class="cds--label">Administrator</label>
                        <div>
                            <select name="root_admin" class="cds--text-input cds--select-input">
                                <option value="0">@lang('strings.no')</option>
                                <option value="1">@lang('strings.yes')</option>
                            </select>
                            <p class="text-muted"><small>Setting this to 'Yes' gives a user full administrative access.</small></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="cds--tile ptero-tile">
                <div class="ptero-tile__header">
                    <h3 class="cds--type-productive-heading-02">Password</h3>
                </div>
                <div class="ptero-tile__body">
                    <div class="cds--inline-notification cds--inline-notification--info">
                        <p>Providing a user password is optional. New user emails prompt users to create a password the first time they login. If a password is provided here you will need to find a different method of providing it to the user.</p>
                    </div>
                    <div id="gen_pass" class=" cds--inline-notification cds--inline-notification--success" style="display:none;margin-bottom: 10px;"></div>
                    <div class="cds--form-item">
                        <label for="pass" class="cds--label">Password</label>
                        <div>
                            <input type="password" name="password" class="cds--text-input" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('footer-scripts')
    @parent
    <script>$("#gen_pass_bttn").click(function (event) {
            event.preventDefault();
            $.ajax({
                type: "GET",
                url: "/password-gen/12",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
               },
                success: function(data) {
                    $("#gen_pass").html('<strong>Generated Password:</strong> ' + data).slideDown();
                    $('input[name="password"], input[name="password_confirmation"]').val(data);
                    return false;
                }
            });
            return false;
        });
    </script>
@endsection
