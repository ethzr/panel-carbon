@extends('layouts.admin')

@section('title')
    Server — {{ $server->name }}: Databases
@endsection

@section('content-header')
    <nav class="cds--breadcrumb cds--breadcrumb--no-trailing-slash" aria-label="breadcrumb">
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.index') }}" class="cds--link">Admin</a></div>
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.servers') }}" class="cds--link">Servers</a></div>
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.servers.view', $server->id) }}" class="cds--link">{{ $server->name }}</a></div>
        <div class="cds--breadcrumb-item"><span class="cds--link">Databases</span></div>
    </nav>
    <h1 class="cds--type-productive-heading-04">{{ $server->name }}</h1>
    <p class="cds--type-body-compact-01">Manage server databases.</p>
@endsection

@section('content')
@include('admin.servers.partials.navigation')
<div class="row">
    <div class="col-sm-7">
        <div class="cds--inline-notification cds--inline-notification--info">
            Database passwords can be viewed when <a href="/server/{{ $server->uuidShort }}/databases">visiting this server</a> on the front-end.
        </div>
        <div class="cds--tile ptero-tile">
            <div class="ptero-tile__header">
                <h3 class="cds--type-productive-heading-02">Active Databases</h3>
            </div>
            <div class="ptero-tile__body table-responsible no-padding">
                <table class="cds--data-table cds--data-table--lg cds--data-table--zebra">
                    <tr>
                        <th>Database</th>
                        <th>Username</th>
                        <th>Connections From</th>
                        <th>Host</th>
                        <th>Max Connections</th>
                        <th></th>
                    </tr>
                    @foreach($server->databases as $database)
                        <tr>
                            <td>{{ $database->database }}</td>
                            <td>{{ $database->username }}</td>
                            <td>{{ $database->remote }}</td>
                            <td><code>{{ $database->host->host }}:{{ $database->host->port }}</code></td>
                            @if($database->max_connections != null)
                                <td>{{ $database->max_connections }}</td>
                            @else
                                <td>Unlimited</td>
                            @endif
                            <td class="text-center">
                                <button data-action="reset-password" data-id="{{ $database->id }}" class="cds--btn cds--btn--sm cds--btn--primary"><i class="fa fa-refresh"></i></button>
                                <button data-action="remove" data-id="{{ $database->id }}" class="cds--btn cds--btn--sm cds--btn--danger"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
    <div class="col-sm-5">
        <div class="cds--tile ptero-tile ptero-tile--success">
            <div class="ptero-tile__header">
                <h3 class="cds--type-productive-heading-02">Create New Database</h3>
            </div>
            <form action="{{ route('admin.servers.view.database', $server->id) }}" method="POST">
                <div class="ptero-tile__body">
                    <div class="cds--form-item">
                        <label for="pDatabaseHostId" class="cds--label">Database Host</label>
                        <select id="pDatabaseHostId" name="database_host_id" class="cds--text-input cds--select-input">
                            @foreach($hosts as $host)
                                <option value="{{ $host->id }}">{{ $host->name }}</option>
                            @endforeach
                        </select>
                        <p class="cds--form__helper-text">Select the host database server that this database should be created on.</p>
                    </div>
                    <div class="cds--form-item">
                        <label for="pDatabaseName" class="cds--label">Database</label>
                        <div class="input-group">
                            <span class="input-group-addon">s{{ $server->id }}_</span>
                            <input id="pDatabaseName" type="text" name="database" class="cds--text-input" placeholder="database" />
                        </div>
                    </div>
                    <div class="cds--form-item">
                        <label for="pRemote" class="cds--label">Connections</label>
                        <input id="pRemote" type="text" name="remote" class="cds--text-input" value="%" />
                        <p class="cds--form__helper-text">This should reflect the IP address that connections are allowed from. Uses standard MySQL notation. If unsure leave as <code>%</code>.</p>
                    </div>
                    <div class="cds--form-item">
                        <label for="pmax_connections" class="cds--label">Concurrent Connections</label>
                        <input id="pmax_connections" type="text" name="max_connections" class="cds--text-input"/>
                        <p class="cds--form__helper-text">This should reflect the max number of concurrent connections from this user to the database. Leave empty for unlimited.</p>
                    </div>
                </div>
                <div class="ptero-tile__footer">
                    {!! csrf_field() !!}
                    <p class="cds--form__helper-text no-margin">A username and password for this database will be randomly generated after form submission.</p>
                    <input type="submit" class="cds--btn cds--btn--sm cds--btn--primary pull-right" value="Create Database" />
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('footer-scripts')
    @parent
    <script>
    $('#pDatabaseHost').select2();
    $('[data-action="remove"]').click(function (event) {
        event.preventDefault();
        var self = $(this);
        swal({
            title: '',
            type: 'warning',
            text: 'Are you sure that you want to delete this database? There is no going back, all data will immediately be removed.',
            showCancelButton: true,
            confirmButtonText: 'Delete',
            confirmButtonColor: '#d9534f',
            closeOnConfirm: false,
            showLoaderOnConfirm: true,
        }, function () {
            $.ajax({
                method: 'DELETE',
                url: '/admin/servers/view/{{ $server->id }}/database/' + self.data('id') + '/delete',
                headers: { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
            }).done(function () {
                self.parent().parent().slideUp();
                swal.close();
            }).fail(function (jqXHR) {
                console.error(jqXHR);
                swal({
                    type: 'error',
                    title: 'Whoops!',
                    text: (typeof jqXHR.responseJSON.error !== 'undefined') ? jqXHR.responseJSON.error : 'An error occurred while processing this request.'
                });
            });
        });
    });
    $('[data-action="reset-password"]').click(function (e) {
        e.preventDefault();
        var block = $(this);
        $(this).addClass('disabled').find('i').addClass('fa-spin');
        $.ajax({
            type: 'PATCH',
            url: '/admin/servers/view/{{ $server->id }}/database',
            headers: { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
            data: { database: $(this).data('id') },
        }).done(function (data) {
            swal({
                type: 'success',
                title: '',
                text: 'The password for this database has been reset.',
            });
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error(jqXHR);
            var error = 'An error occurred while trying to process this request.';
            if (typeof jqXHR.responseJSON !== 'undefined' && typeof jqXHR.responseJSON.error !== 'undefined') {
                error = jqXHR.responseJSON.error;
            }
            swal({
                type: 'error',
                title: 'Whoops!',
                text: error
            });
        }).always(function () {
            block.removeClass('disabled').find('i').removeClass('fa-spin');
        });
    });
    </script>
@endsection
