@extends('layouts.admin')

@section('title')
    Server — {{ $server->name }}: Delete
@endsection

@section('content-header')
    <nav class="cds--breadcrumb cds--breadcrumb--no-trailing-slash" aria-label="breadcrumb">
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.index') }}" class="cds--link">Admin</a></div>
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.servers') }}" class="cds--link">Servers</a></div>
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.servers.view', $server->id) }}" class="cds--link">{{ $server->name }}</a></div>
        <div class="cds--breadcrumb-item"><span class="cds--link">Delete</span></div>
    </nav>
    <h1 class="cds--type-productive-heading-04">{{ $server->name }}</h1>
    <p class="cds--type-body-compact-01">Delete this server from the panel.</p>
@endsection

@section('content')
@include('admin.servers.partials.navigation')
<div class="row">
    <div class="col-md-6">
        <div class="box">
            <div class="ptero-tile__header">
                <h3 class="cds--type-productive-heading-02">Safely Delete Server</h3>
            </div>
            <div class="ptero-tile__body">
                <p>This action will attempt to delete the server from both the panel and daemon. If either one reports an error the action will be cancelled.</p>
                <p class="text-danger small">Deleting a server is an irreversible action. <strong>All server data</strong> (including files and users) will be removed from the system.</p>
            </div>
            <div class="ptero-tile__footer">
                <form id="deleteform" action="{{ route('admin.servers.view.delete', $server->id) }}" method="POST">
                    {!! csrf_field() !!}
                    <button id="deletebtn" class="cds--btn cds--btn--danger">Safely Delete This Server</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="cds--tile ptero-tile ptero-tile--danger">
            <div class="ptero-tile__header">
                <h3 class="cds--type-productive-heading-02">Force Delete Server</h3>
            </div>
            <div class="ptero-tile__body">
                <p>This action will attempt to delete the server from both the panel and daemon. If the daemon does not respond, or reports an error the deletion will continue.</p>
                <p class="text-danger small">Deleting a server is an irreversible action. <strong>All server data</strong> (including files and users) will be removed from the system. This method may leave dangling files on your daemon if it reports an error.</p>
            </div>
            <div class="ptero-tile__footer">
                <form id="forcedeleteform" action="{{ route('admin.servers.view.delete', $server->id) }}" method="POST">
                    {!! csrf_field() !!}
                    <input type="hidden" name="force_delete" value="1" />
                    <button id="forcedeletebtn"" class="cds--btn cds--btn--danger">Forcibly Delete This Server</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer-scripts')
    @parent
    <script>
    $('#deletebtn').click(function (event) {
        event.preventDefault();
        swal({
            title: '',
            type: 'warning',
            text: 'Are you sure that you want to delete this server? There is no going back, all data will immediately be removed.',
            showCancelButton: true,
            confirmButtonText: 'Delete',
            confirmButtonColor: '#d9534f',
            closeOnConfirm: false
        }, function () {
            $('#deleteform').submit()
        });
    });

    $('#forcedeletebtn').click(function (event) {
        event.preventDefault();
        swal({
            title: '',
            type: 'warning',
            text: 'Are you sure that you want to delete this server? There is no going back, all data will immediately be removed.',
            showCancelButton: true,
            confirmButtonText: 'Delete',
            confirmButtonColor: '#d9534f',
            closeOnConfirm: false
        }, function () {
            $('#forcedeleteform').submit()
        });
    });
    </script>
@endsection
