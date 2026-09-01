@extends('layouts.admin')

@section('title')
    Manage User: {{ $user->username }}
@endsection

@section('content-header')
    <nav class="cds--breadcrumb cds--breadcrumb--no-trailing-slash" aria-label="breadcrumb">
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.index') }}" class="cds--link">Admin</a></div>
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.users') }}" class="cds--link">Users</a></div>
        <div class="cds--breadcrumb-item"><span class="cds--link">{{ $user->username }}</span></div>
    </nav>
    <h1 class="cds--type-productive-heading-04">{{ $user->name_first }} {{ $user->name_last}}</h1>
    <p class="cds--type-body-compact-01">{{ $user->username }}</p>
@endsection

@section('content')
<div class="row">
    <form action="{{ route('admin.users.view', $user->id) }}" method="post">
        <div class="col-md-6">
            <div class="cds--tile ptero-tile">
                <div class="ptero-tile__header">
                    <h3 class="cds--type-productive-heading-02">Identity</h3>
                </div>
                <div class="ptero-tile__body">
                    <div class="cds--form-item">
                        <label for="email" class="cds--label">Email</label>
                        <div>
                            <input type="email" name="email" value="{{ $user->email }}" class="cds--text-input form-autocomplete-stop">
                        </div>
                    </div>
                    <div class="cds--form-item">
                        <label for="registered" class="cds--label">Username</label>
                        <div>
                            <input type="text" name="username" value="{{ $user->username }}" class="cds--text-input form-autocomplete-stop">
                        </div>
                    </div>
                    <div class="cds--form-item">
                        <label for="registered" class="cds--label">Client First Name</label>
                        <div>
                            <input type="text" name="name_first" value="{{ $user->name_first }}" class="cds--text-input form-autocomplete-stop">
                        </div>
                    </div>
                    <div class="cds--form-item">
                        <label for="registered" class="cds--label">Client Last Name</label>
                        <div>
                            <input type="text" name="name_last" value="{{ $user->name_last }}" class="cds--text-input form-autocomplete-stop">
                        </div>
                    </div>
                    <div class="cds--form-item">
                        <label class="cds--label">Default Language</label>
                        <div>
                            <select name="language" class="cds--text-input cds--select-input">
                                @foreach($languages as $key => $value)
                                    <option value="{{ $key }}" @if($user->language === $key) selected @endif>{{ $value }}</option>
                                @endforeach
                            </select>
                            <p class="text-muted"><small>The default language to use when rendering the Panel for this user.</small></p>
                        </div>
                    </div>
                </div>
                <div class="ptero-tile__footer">
                    {!! csrf_field() !!}
                    {!! method_field('PATCH') !!}
                    <input type="submit" value="Update User" class="cds--btn cds--btn--primary cds--btn--sm">
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box">
                <div class="ptero-tile__header">
                    <h3 class="cds--type-productive-heading-02">Password</h3>
                </div>
                <div class="ptero-tile__body">
                    <div class="cds--inline-notification cds--inline-notification--success" style="display:none;margin-bottom:10px;" id="gen_pass"></div>
                    <div class="cds--form-item no-margin-bottom">
                        <label for="password" class="cds--label">Password <span class="field-optional"></span></label>
                        <div>
                            <input type="password" id="password" name="password" class="cds--text-input form-autocomplete-stop">
                            <p class="cds--form__helper-text">Leave blank to keep this user's password the same. User will not receive any notification if password is changed.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box">
                <div class="ptero-tile__header">
                    <h3 class="cds--type-productive-heading-02">Permissions</h3>
                </div>
                <div class="ptero-tile__body">
                    <div class="cds--form-item">
                        <label for="root_admin" class="cds--label">Administrator</label>
                        <div>
                            <select name="root_admin" class="cds--text-input cds--select-input">
                                <option value="0">@lang('strings.no')</option>
                                <option value="1" {{ $user->root_admin ? 'selected="selected"' : '' }}>@lang('strings.yes')</option>
                            </select>
                            <p class="text-muted"><small>Setting this to 'Yes' gives a user full administrative access.</small></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="col-xs-12">
        <div class="cds--tile ptero-tile ptero-tile--danger">
            <div class="ptero-tile__header">
                <h3 class="cds--type-productive-heading-02">Delete User</h3>
            </div>
            <div class="ptero-tile__body">
                <p class="no-margin">There must be no servers associated with this account in order for it to be deleted.</p>
            </div>
            <div class="ptero-tile__footer">
                <form action="{{ route('admin.users.view', $user->id) }}" method="POST">
                    {!! csrf_field() !!}
                    {!! method_field('DELETE') !!}
                    <input id="delete" type="submit" class="cds--btn cds--btn--sm cds--btn--danger pull-right" {{ $user->servers->count() < 1 ?: 'disabled' }} value="Delete User" />
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
