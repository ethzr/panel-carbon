
@extends('layouts.admin')

@section('title')
    Mounts
@endsection

@section('content-header')
    <nav class="cds--breadcrumb cds--breadcrumb--no-trailing-slash" aria-label="breadcrumb">
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.index') }}" class="cds--link">Admin</a></div>
        <div class="cds--breadcrumb-item"><span class="cds--link">Mounts</span></div>
    </nav>
    <h1 class="cds--type-productive-heading-04">Mounts</h1>
    <p class="cds--type-body-compact-01">Configure and manage additional mount points for servers.</p>
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="cds--tile ptero-tile">
                <div class="ptero-tile__header">
                    <h3 class="cds--type-productive-heading-02">Mount List</h3>

                    <div class="ptero-tile__tools">
                        <button class="cds--btn cds--btn--sm cds--btn--primary" data-toggle="modal" data-target="#newMountModal">Create New</button>
                    </div>
                </div>

                <div class="cds--data-table-container">
                    <table class="cds--data-table cds--data-table--lg cds--data-table--zebra">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Source</th>
                                <th>Target</th>
                                <th class="text-center">Eggs</th>
                                <th class="text-center">Nodes</th>
                                <th class="text-center">Servers</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mounts as $mount)
                                <tr>
                                    <td><code>{{ $mount->id }}</code></td>
                                    <td><a href="{{ route('admin.mounts.view', $mount->id) }}">{{ $mount->name }}</a></td>
                                    <td><code>{{ $mount->source }}</code></td>
                                    <td><code>{{ $mount->target }}</code></td>
                                    <td class="text-center">{{ $mount->eggs_count }}</td>
                                    <td class="text-center">{{ $mount->nodes_count }}</td>
                                    <td class="text-center">{{ $mount->servers_count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="newMountModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.mounts') }}" method="POST">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>

                        <h4 class="modal-title">Create Mount</h4>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="pName" class="cds--label">Name</label>
                                <input type="text" id="pName" name="name" class="cds--text-input" />
                                <p class="cds--form__helper-text">Unique name used to separate this mount from another.</p>
                            </div>

                            <div class="col-md-12">
                                <label for="pDescription" class="cds--label">Description</label>
                                <textarea id="pDescription" name="description" class="cds--text-input" rows="4"></textarea>
                                <p class="cds--form__helper-text">A longer description for this mount, must be less than 191 characters.</p>
                            </div>

                            <div class="col-md-6">
                                <label for="pSource" class="cds--label">Source</label>
                                <input type="text" id="pSource" name="source" class="cds--text-input" />
                                <p class="cds--form__helper-text">File path on the host system to mount to a container.</p>
                            </div>

                            <div class="col-md-6">
                                <label for="pTarget" class="cds--label">Target</label>
                                <input type="text" id="pTarget" name="target" class="cds--text-input" />
                                <p class="cds--form__helper-text">Where the mount will be accessible inside a container.</p>
                            </div>

                            <div class="col-md-6">
                                <label class="cds--label">Read Only</label>

                                <div>
                                    <div class="radio radio-success radio-inline">
                                        <input type="radio" id="pReadOnlyFalse" name="read_only" value="0" checked>
                                        <label for="pReadOnlyFalse">False</label>
                                    </div>

                                    <div class="radio radio-warning radio-inline">
                                        <input type="radio" id="pReadOnly" name="read_only" value="1">
                                        <label for="pReadOnly">True</label>
                                    </div>
                                </div>

                                <p class="cds--form__helper-text">Is the mount read only inside the container?</p>
                            </div>

                            <div class="col-md-6">
                                <label class="cds--label">User Mountable</label>

                                <div>
                                    <div class="radio radio-success radio-inline">
                                        <input type="radio" id="pUserMountableFalse" name="user_mountable" value="0" checked>
                                        <label for="pUserMountableFalse">False</label>
                                    </div>

                                    <div class="radio radio-warning radio-inline">
                                        <input type="radio" id="pUserMountable" name="user_mountable" value="1">
                                        <label for="pUserMountable">True</label>
                                    </div>
                                </div>

                                <p class="cds--form__helper-text">Should users be able to mount this themselves?</p>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        {!! csrf_field() !!}
                        <button type="button" class="cds--btn cds--btn--secondary cds--btn--sm pull-left" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="cds--btn cds--btn--primary cds--btn--sm">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
