@extends('layouts.admin')

@section('title')
    Server — {{ $server->name }}: Manage
@endsection

@section('content-header')
    <nav class="cds--breadcrumb cds--breadcrumb--no-trailing-slash" aria-label="breadcrumb">
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.index') }}" class="cds--link">Admin</a></div>
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.servers') }}" class="cds--link">Servers</a></div>
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.servers.view', $server->id) }}" class="cds--link">{{ $server->name }}</a></div>
        <div class="cds--breadcrumb-item"><span class="cds--link">Manage</span></div>
    </nav>
    <h1 class="cds--type-productive-heading-04">{{ $server->name }}</h1>
    <p class="cds--type-body-compact-01">Additional actions to control this server.</p>
@endsection

@section('content')
    @include('admin.servers.partials.navigation')
    <div class="row equal-height">
        <div class="col-sm-4">
            <div class="cds--tile ptero-tile ptero-tile--danger">
                <div class="ptero-tile__header">
                    <h3 class="cds--type-productive-heading-02">Reinstall Server</h3>
                </div>
                <div class="ptero-tile__body">
                    <p>This will reinstall the server with the assigned service scripts. <strong>Danger!</strong> This could overwrite server data.</p>
                </div>
                <div class="ptero-tile__footer">
                    @if(! $server->canBeReinstalled())
                        <button class="cds--btn cds--btn--danger disabled">Reinstall Server</button>
                        <p style="padding-top: 1rem;">This server is set to skip its install script. Disable "Skip Egg Install Script" on the startup page to reinstall it.</p>
                    @elseif($server->isInstalled())
                        <form action="{{ route('admin.servers.view.manage.reinstall', $server->id) }}" method="POST">
                            {!! csrf_field() !!}
                            <button type="submit" class="cds--btn cds--btn--danger">Reinstall Server</button>
                        </form>
                    @else
                        <button class="cds--btn cds--btn--danger disabled">Server Must Install Properly to Reinstall</button>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="cds--tile ptero-tile">
                <div class="ptero-tile__header">
                    <h3 class="cds--type-productive-heading-02">Install Status</h3>
                </div>
                <div class="ptero-tile__body">
                    <p>If you need to change the install status from uninstalled to installed, or vice versa, you may do so with the button below.</p>
                </div>
                <div class="ptero-tile__footer">
                    <form action="{{ route('admin.servers.view.manage.toggle', $server->id) }}" method="POST">
                        {!! csrf_field() !!}
                        <button type="submit" class="cds--btn cds--btn--primary">Toggle Install Status</button>
                    </form>
                </div>
            </div>
        </div>

        @if(! $server->isSuspended())
            <div class="col-sm-4">
                <div class="cds--tile ptero-tile ptero-tile--warning">
                    <div class="ptero-tile__header">
                        <h3 class="cds--type-productive-heading-02">Suspend Server</h3>
                    </div>
                    <div class="ptero-tile__body">
                        <p>This will suspend the server, stop any running processes, and immediately block the user from being able to access their files or otherwise manage the server through the panel or API.</p>
                    </div>
                    <div class="ptero-tile__footer">
                        <form action="{{ route('admin.servers.view.manage.suspension', $server->id) }}" method="POST">
                            {!! csrf_field() !!}
                            <input type="hidden" name="action" value="suspend" />
                            <button type="submit" class="cds--btn cds--btn--tertiary @if(! is_null($server->transfer)) disabled @endif">Suspend Server</button>
                        </form>
                    </div>
                </div>
            </div>
        @else
            <div class="col-sm-4">
                <div class="cds--tile ptero-tile ptero-tile--success">
                    <div class="ptero-tile__header">
                        <h3 class="cds--type-productive-heading-02">Unsuspend Server</h3>
                    </div>
                    <div class="ptero-tile__body">
                        <p>This will unsuspend the server and restore normal user access.</p>
                    </div>
                    <div class="ptero-tile__footer">
                        <form action="{{ route('admin.servers.view.manage.suspension', $server->id) }}" method="POST">
                            {!! csrf_field() !!}
                            <input type="hidden" name="action" value="unsuspend" />
                            <button type="submit" class="cds--btn cds--btn--primary">Unsuspend Server</button>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        @if(is_null($server->transfer))
            <div class="col-sm-4">
                <div class="cds--tile ptero-tile ptero-tile--success">
                    <div class="ptero-tile__header">
                        <h3 class="cds--type-productive-heading-02">Transfer Server</h3>
                    </div>
                    <div class="ptero-tile__body">
                        <p>
                            Transfer this server to another node connected to this panel.
                            <strong>Warning!</strong> This feature has not been fully tested and may have bugs.
                        </p>
                    </div>

                    <div class="ptero-tile__footer">
                        @if($canTransfer)
                            <button class="cds--btn cds--btn--primary" data-toggle="modal" data-target="#transferServerModal">Transfer Server</button>
                        @else
                            <button class="cds--btn cds--btn--primary disabled">Transfer Server</button>
                            <p style="padding-top: 1rem;">Transferring a server requires more than one node to be configured on your panel.</p>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="col-sm-4">
                <div class="cds--tile ptero-tile ptero-tile--success">
                    <div class="ptero-tile__header">
                        <h3 class="cds--type-productive-heading-02">Transfer Server</h3>
                    </div>
                    <div class="ptero-tile__body">
                        <p>
                            This server is currently being transferred to another node.
                            Transfer was initiated at <strong>{{ $server->transfer->created_at }}</strong>
                        </p>
                    </div>

                    <div class="ptero-tile__footer">
                        <button class="cds--btn cds--btn--primary disabled">Transfer Server</button>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="modal fade" id="transferServerModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.servers.view.manage.transfer', $server->id) }}" method="POST">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Transfer Server</h4>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="cds--form-item col-md-12">
                                <label for="pNodeId">Node</label>
                                <select name="node_id" id="pNodeId" class="cds--text-input cds--select-input">
                                    @foreach($locations as $location)
                                        <optgroup label="{{ $location->long }} ({{ $location->short }})">
                                            @foreach($location->nodes as $node)

                                                @if($node->id != $server->node_id)
                                                    <option value="{{ $node->id }}"
                                                            @if($location->id === old('location_id')) selected @endif
                                                    >{{ $node->name }}</option>
                                                @endif

                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                <p class="cds--form__helper-text no-margin">The node which this server will be transferred to.</p>
                            </div>

                            <div class="cds--form-item col-md-12">
                                <label for="pAllocation">Default Allocation</label>
                                <select name="allocation_id" id="pAllocation" class="cds--text-input cds--select-input"></select>
                                <p class="cds--form__helper-text no-margin">The main allocation that will be assigned to this server.</p>
                            </div>

                            <div class="cds--form-item col-md-12">
                                <label for="pAllocationAdditional">Additional Allocation(s)</label>
                                <select name="allocation_additional[]" id="pAllocationAdditional" class="cds--text-input cds--select-input" multiple></select>
                                <p class="cds--form__helper-text no-margin">Additional allocations to assign to this server on creation.</p>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        {!! csrf_field() !!}
                        <button type="button" class="cds--btn cds--btn--secondary cds--btn--sm pull-left" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="cds--btn cds--btn--primary cds--btn--sm">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('footer-scripts')
    @parent
    {!! Theme::js('vendor/lodash/lodash.js') !!}

    @if($canTransfer)
        {!! Theme::js('js/admin/server/transfer.js') !!}
    @endif
@endsection
