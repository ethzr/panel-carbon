
@extends('layouts.admin')

@section('title')
    Mounts &rarr; View &rarr; {{ $mount->id }}
@endsection

@section('content-header')
    <nav class="cds--breadcrumb cds--breadcrumb--no-trailing-slash" aria-label="breadcrumb">
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.index') }}" class="cds--link">Admin</a></div>
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.mounts') }}" class="cds--link">Mounts</a></div>
        <div class="cds--breadcrumb-item"><span class="cds--link">{{ $mount->name }}</span></div>
    </nav>
    <h1 class="cds--type-productive-heading-04">{{ $mount->name }}</h1>
    <p class="cds--type-body-compact-01">{{ str_limit($mount->description, 75) }}</p>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-6">
            <div class="cds--tile ptero-tile">
                <div class="ptero-tile__header">
                    <h3 class="cds--type-productive-heading-02">Mount Details</h3>
                </div>

                <form action="{{ route('admin.mounts.view', $mount->id) }}" method="POST">
                    <div class="ptero-tile__body">
                        <div class="cds--form-item">
                            <label for="PUniqueID" class="cds--label">Unique ID</label>
                            <input type="text" id="PUniqueID" class="cds--text-input" value="{{ $mount->uuid }}" disabled />
                        </div>

                        <div class="cds--form-item">
                            <label for="pName" class="cds--label">Name</label>
                            <input type="text" id="pName" name="name" class="cds--text-input" value="{{ $mount->name }}" />
                        </div>

                        <div class="cds--form-item">
                            <label for="pDescription" class="cds--label">Description</label>
                            <textarea id="pDescription" name="description" class="cds--text-input" rows="4">{{ $mount->description }}</textarea>
                        </div>

                        <div class="row">
                            <div class="cds--form-item col-md-6">
                                <label for="pSource" class="cds--label">Source</label>
                                <input type="text" id="pSource" name="source" class="cds--text-input" value="{{ $mount->source }}" />
                            </div>

                            <div class="cds--form-item col-md-6">
                                <label for="pTarget" class="cds--label">Target</label>
                                <input type="text" id="pTarget" name="target" class="cds--text-input" value="{{ $mount->target }}" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="cds--form-item col-md-6">
                                <label class="cds--label">Read Only</label>

                                <div>
                                    <div class="radio radio-success radio-inline">
                                        <input type="radio" id="pReadOnlyFalse" name="read_only" value="0" @if(!$mount->read_only) checked @endif>
                                        <label for="pReadOnlyFalse">False</label>
                                    </div>

                                    <div class="radio radio-warning radio-inline">
                                        <input type="radio" id="pReadOnly" name="read_only" value="1" @if($mount->read_only) checked @endif>
                                        <label for="pReadOnly">True</label>
                                    </div>
                                </div>
                            </div>

                            <div class="cds--form-item col-md-6">
                                <label class="cds--label">User Mountable</label>

                                <div>
                                    <div class="radio radio-success radio-inline">
                                        <input type="radio" id="pUserMountableFalse" name="user_mountable" value="0" @if(!$mount->user_mountable) checked @endif>
                                        <label for="pUserMountableFalse">False</label>
                                    </div>

                                    <div class="radio radio-warning radio-inline">
                                        <input type="radio" id="pUserMountable" name="user_mountable" value="1" @if($mount->user_mountable) checked @endif>
                                        <label for="pUserMountable">True</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ptero-tile__footer">
                        {!! csrf_field() !!}
                        {!! method_field('PATCH') !!}

                        <button name="action" value="edit" class="cds--btn cds--btn--sm cds--btn--primary pull-right">Save</button>
                        <button name="action" value="delete" class="cds--btn cds--btn--sm cds--btn--danger pull-left muted muted-hover"><i class="fa fa-trash-o"></i></button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="box">
                <div class="ptero-tile__header">
                    <h3 class="cds--type-productive-heading-02">Eggs</h3>

                    <div class="ptero-tile__tools">
                        <button class="cds--btn cds--btn--sm cds--btn--primary" data-toggle="modal" data-target="#addEggsModal">Add Eggs</button>
                    </div>
                </div>

                <div class="cds--data-table-container">
                    <table class="cds--data-table cds--data-table--lg cds--data-table--zebra">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th></th>
                        </tr>

                        @foreach ($mount->eggs as $egg)
                            <tr>
                                <td class="col-sm-2 middle"><code>{{ $egg->id }}</code></td>
                                <td class="middle"><a href="{{ route('admin.nests.egg.view', $egg->id) }}">{{ $egg->name }}</a></td>
                                <td class="col-sm-1 middle">
                                    <button data-action="detach-egg" data-id="{{ $egg->id }}" class="cds--btn cds--btn--sm cds--btn--danger"><i class="fa fa-trash-o"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>

            <div class="box">
                <div class="ptero-tile__header">
                    <h3 class="cds--type-productive-heading-02">Nodes</h3>

                    <div class="ptero-tile__tools">
                        <button class="cds--btn cds--btn--sm cds--btn--primary" data-toggle="modal" data-target="#addNodesModal">Add Nodes</button>
                    </div>
                </div>

                <div class="cds--data-table-container">
                    <table class="cds--data-table cds--data-table--lg cds--data-table--zebra">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>FQDN</th>
                            <th></th>
                        </tr>

                        @foreach ($mount->nodes as $node)
                            <tr>
                                <td class="col-sm-2 middle"><code>{{ $node->id }}</code></td>
                                <td class="middle"><a href="{{ route('admin.nodes.view', $node->id) }}">{{ $node->name }}</a></td>
                                <td class="middle"><code>{{ $node->fqdn }}</code></td>
                                <td class="col-sm-1 middle">
                                    <button data-action="detach-node" data-id="{{ $node->id }}" class="cds--btn cds--btn--sm cds--btn--danger"><i class="fa fa-trash-o"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addEggsModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.mounts.eggs', $mount->id) }}" method="POST">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" style="color: #FFFFFF">&times;</span>
                        </button>

                        <h4 class="modal-title">Add Eggs</h4>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="cds--form-item col-md-12">
                                <label for="pEggs">Eggs</label>
                                <select id="pEggs" name="eggs[]" class="cds--text-input cds--select-input" multiple>
                                    @foreach ($nests as $nest)
                                        <optgroup label="{{ $nest->name }}">
                                            @foreach ($nest->eggs as $egg)

                                                @if (! in_array($egg->id, $mount->eggs->pluck('id')->toArray()))
                                                    <option value="{{ $egg->id }}">{{ $egg->name }}</option>
                                                @endif

                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        {!! csrf_field() !!}

                        <button type="button" class="cds--btn cds--btn--secondary cds--btn--sm pull-left" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="cds--btn cds--btn--primary cds--btn--sm">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addNodesModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.mounts.nodes', $mount->id) }}" method="POST">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" style="color: #FFFFFF">&times;</span>
                        </button>

                        <h4 class="modal-title">Add Nodes</h4>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="cds--form-item col-md-12">
                                <label for="pNodes">Nodes</label>
                                <select id="pNodes" name="nodes[]" class="cds--text-input cds--select-input" multiple>
                                    @foreach ($locations as $location)
                                        <optgroup label="{{ $location->long }} ({{ $location->short }})">
                                            @foreach ($location->nodes as $node)

                                                @if (! in_array($node->id, $mount->nodes->pluck('id')->toArray()))
                                                    <option value="{{ $node->id }}">{{ $node->name }}</option>
                                                @endif

                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        {!! csrf_field() !!}

                        <button type="button" class="cds--btn cds--btn--secondary cds--btn--sm pull-left" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="cds--btn cds--btn--primary cds--btn--sm">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('footer-scripts')
    @parent

    <script>
        $(document).ready(function() {
            $('#pEggs').select2({
                placeholder: 'Select eggs..',
            });

            $('#pNodes').select2({
                placeholder: 'Select nodes..',
            });

            $('button[data-action="detach-egg"]').click(function (event) {
                event.preventDefault();

                const element = $(this);
                const eggId = $(this).data('id');

                $.ajax({
                    method: 'DELETE',
                    url: '/admin/mounts/' + {{ $mount->id }} + '/eggs/' + eggId,
                    headers: { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
                }).done(function () {
                    element.parent().parent().addClass('warning').delay(100).fadeOut();
                    swal({ type: 'success', title: 'Egg detached.' });
                }).fail(function (jqXHR) {
                    console.error(jqXHR);
                    swal({
                        title: 'Whoops!',
                        text: jqXHR.responseJSON.error,
                        type: 'error'
                    });
                });
            });

            $('button[data-action="detach-node"]').click(function (event) {
                event.preventDefault();

                const element = $(this);
                const nodeId = $(this).data('id');

                $.ajax({
                    method: 'DELETE',
                    url: '/admin/mounts/' + {{ $mount->id }} + '/nodes/' + nodeId,
                    headers: { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
                }).done(function () {
                    element.parent().parent().addClass('warning').delay(100).fadeOut();
                    swal({ type: 'success', title: 'Node detached.' });
                }).fail(function (jqXHR) {
                    console.error(jqXHR);
                    swal({
                        title: 'Whoops!',
                        text: jqXHR.responseJSON.error,
                        type: 'error'
                    });
                });
            });
        });
    </script>
@endsection
