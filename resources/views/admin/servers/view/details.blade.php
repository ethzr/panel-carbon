@extends('layouts.admin')

@section('title')
    Server — {{ $server->name }}: Details
@endsection

@section('content-header')
    <nav class="cds--breadcrumb cds--breadcrumb--no-trailing-slash" aria-label="breadcrumb">
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.index') }}" class="cds--link">Admin</a></div>
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.servers') }}" class="cds--link">Servers</a></div>
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.servers.view', $server->id) }}" class="cds--link">{{ $server->name }}</a></div>
        <div class="cds--breadcrumb-item"><span class="cds--link">Details</span></div>
    </nav>
    <h1 class="cds--type-productive-heading-04">{{ $server->name }}</h1>
    <p class="cds--type-body-compact-01">Edit details for this server including owner and container.</p>
@endsection

@section('content')
@include('admin.servers.partials.navigation')
<div class="row">
    <div class="col-xs-12">
        <div class="cds--tile ptero-tile">
            <div class="ptero-tile__header">
                <h3 class="cds--type-productive-heading-02">Base Information</h3>
            </div>
            <form action="{{ route('admin.servers.view.details', $server->id) }}" method="POST">
                <div class="ptero-tile__body">
                    <div class="cds--form-item">
                        <label for="name" class="cds--label">Server Name <span class="field-required"></span></label>
                        <input type="text" name="name" value="{{ old('name', $server->name) }}" class="cds--text-input" />
                        <p class="cds--form__helper-text">Character limits: <code>a-zA-Z0-9_-</code> and <code>[Space]</code>.</p>
                    </div>
                    <div class="cds--form-item">
                        <label for="external_id" class="cds--label">External Identifier</label>
                        <input type="text" name="external_id" value="{{ old('external_id', $server->external_id) }}" class="cds--text-input" />
                        <p class="cds--form__helper-text">Leave empty to not assign an external identifier for this server. The external ID should be unique to this server and not be in use by any other servers.</p>
                    </div>
                    <div class="cds--form-item">
                        <label for="pUserId" class="cds--label">Server Owner <span class="field-required"></span></label>
                        <select name="owner_id" class="cds--text-input cds--select-input" id="pUserId">
                            <option value="{{ $server->owner_id }}" selected>{{ $server->user->email }}</option>
                        </select>
                        <p class="cds--form__helper-text">You can change the owner of this server by changing this field to an email matching another use on this system. If you do this a new daemon security token will be generated automatically.</p>
                    </div>
                    <div class="cds--form-item">
                        <label for="description" class="cds--label">Server Description</label>
                        <textarea name="description" rows="3" class="cds--text-input">{{ old('description', $server->description) }}</textarea>
                        <p class="cds--form__helper-text">A brief description of this server.</p>
                    </div>
                </div>
                <div class="ptero-tile__footer">
                    {!! csrf_field() !!}
                    {!! method_field('PATCH') !!}
                    <input type="submit" class="cds--btn cds--btn--sm cds--btn--primary" value="Update Details" />
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('footer-scripts')
    @parent
    <script>
    function escapeHtml(str) {
        var div = document.createElement('div');
        div.appendChild(document.createTextNode(str));
        return div.innerHTML;
    }

    $('#pUserId').select2({
        ajax: {
            url: '/admin/users/accounts.json',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    filter: { email: params.term },
                    page: params.page,
                };
            },
            processResults: function (data, params) {
                return { results: data };
            },
            cache: true,
        },
        escapeMarkup: function (markup) { return markup; },
        minimumInputLength: 2,
        templateResult: function (data) {
            if (data.loading) return escapeHtml(data.text);

            return '<div class="user-block"> \
                <img class="img-circle img-bordered-xs" src="https://www.gravatar.com/avatar/' + escapeHtml(data.md5) + '?s=120" alt="User Image"> \
                <span class="username"> \
                    <a href="#">' + escapeHtml(data.name_first) + ' ' + escapeHtml(data.name_last) +'</a> \
                </span> \
                <span class="description"><strong>' + escapeHtml(data.email) + '</strong> - ' + escapeHtml(data.username) + '</span> \
            </div>';
        },
        templateSelection: function (data) {
            if (typeof data.name_first === 'undefined') {
                data = {
                    md5: '{{ md5(strtolower($server->user->email)) }}',
                    name_first: '{{ $server->user->name_first }}',
                    name_last: '{{ $server->user->name_last }}',
                    email: '{{ $server->user->email }}',
                    id: {{ $server->owner_id }}
                };
            }

            return '<div> \
                <span> \
                    <img class="img-rounded img-bordered-xs" src="https://www.gravatar.com/avatar/' + escapeHtml(data.md5) + '?s=120" style="height:28px;margin-top:-4px;" alt="User Image"> \
                </span> \
                <span style="padding-left:5px;"> \
                    ' + escapeHtml(data.name_first) + ' ' + escapeHtml(data.name_last) + ' (<strong>' + escapeHtml(data.email) + '</strong>) \
                </span> \
            </div>';
        }
    });
    </script>
@endsection
