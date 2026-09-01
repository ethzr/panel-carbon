@extends('layouts.admin')

@section('title')
    Server — {{ $server->name }}: Mounts
@endsection

@section('content-header')
    <nav class="cds--breadcrumb cds--breadcrumb--no-trailing-slash" aria-label="breadcrumb">
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.index') }}" class="cds--link">Admin</a></div>
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.servers') }}" class="cds--link">Servers</a></div>
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.servers.view', $server->id) }}" class="cds--link">{{ $server->name }}</a></div>
        <div class="cds--breadcrumb-item"><span class="cds--link">Mounts</span></div>
    </nav>
    <h1 class="cds--type-productive-heading-04">{{ $server->name }}</h1>
    <p class="cds--type-body-compact-01">Manage server mounts.</p>
@endsection

@section('content')
    @include('admin.servers.partials.navigation')

    <div class="row">
        <div class="col-sm-12">
            <div class="cds--tile ptero-tile">
                <div class="ptero-tile__header">
                    <h3 class="cds--type-productive-heading-02">Available Mounts</h3>
                </div>

                <div class="ptero-tile__body table-responsible no-padding">
                    <table class="cds--data-table cds--data-table--lg cds--data-table--zebra">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Source</th>
                            <th>Target</th>
                            <th>Status</th>
                            <th></th>
                        </tr>

                        @foreach ($mounts as $mount)
                            <tr>
                                <td class="col-sm-1 middle"><code>{{ $mount->id }}</code></td>
                                <td class="middle"><a href="{{ route('admin.mounts.view', $mount->id) }}">{{ $mount->name }}</a></td>
                                <td class="middle"><code>{{ $mount->source }}</code></td>
                                <td class="col-sm-2 middle"><code>{{ $mount->target }}</code></td>

                                @if (! in_array($mount->id, $server->mounts->pluck('id')->toArray()))
                                    <td class="col-sm-2 middle">
                                        <span class="label label-primary">Unmounted</span>
                                    </td>

                                    <td class="col-sm-1 middle">
                                        <form action="{{ route('admin.servers.view.mounts.store', [ 'server' => $server->id ]) }}" method="POST">
                                            {!! csrf_field() !!}
                                            <input type="hidden" value="{{ $mount->id }}" name="mount_id" />
                                            <button type="submit" class="cds--btn cds--btn--sm cds--btn--primary"><i class="fa fa-plus"></i></button>
                                        </form>
                                    </td>
                                @else
                                    <td class="col-sm-2 middle">
                                        <span class="cds--tag cds--tag--green">Mounted</span>
                                    </td>

                                    <td class="col-sm-1 middle">
                                        <form action="{{ route('admin.servers.view.mounts.delete', [ 'server' => $server->id, 'mount' => $mount->id ]) }}" method="POST">
                                            @method('DELETE')
                                            {!! csrf_field() !!}

                                            <button type="submit" class="cds--btn cds--btn--sm cds--btn--danger"><i class="fa fa-times"></i></button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
