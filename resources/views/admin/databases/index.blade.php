@extends('layouts.admin')

@section('title')
    Database Hosts
@endsection

@section('content-header')
    <nav class="cds--breadcrumb cds--breadcrumb--no-trailing-slash" aria-label="breadcrumb">
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.index') }}" class="cds--link">Admin</a></div>
        <div class="cds--breadcrumb-item"><span class="cds--link">Database Hosts</span></div>
    </nav>
    <h1 class="cds--type-productive-heading-04">Database Hosts</h1>
    <p class="cds--type-body-compact-01">Database hosts that servers can have databases created on.</p>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="cds--tile ptero-tile">
            <div class="ptero-tile__header">
                <h3 class="cds--type-productive-heading-02">Host List</h3>
                <div class="ptero-tile__tools">
                    <button class="cds--btn cds--btn--sm cds--btn--primary" data-toggle="modal" data-target="#newHostModal">Create New</button>
                </div>
            </div>
            <div class="cds--data-table-container">
                <table class="cds--data-table cds--data-table--lg cds--data-table--zebra">
                    <tbody>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Host</th>
                            <th>Port</th>
                            <th>Username</th>
                            <th class="text-center">Databases</th>
                            <th class="text-center">Node</th>
                        </tr>
                        @foreach ($hosts as $host)
                            <tr>
                                <td><code>{{ $host->id }}</code></td>
                                <td><a href="{{ route('admin.databases.view', $host->id) }}">{{ $host->name }}</a></td>
                                <td><code>{{ $host->host }}</code></td>
                                <td><code>{{ $host->port }}</code></td>
                                <td>{{ $host->username }}</td>
                                <td class="text-center">{{ $host->databases_count }}</td>
                                <td class="text-center">
                                    @if(! is_null($host->node))
                                        <a href="{{ route('admin.nodes.view', $host->node->id) }}">{{ $host->node->name }}</a>
                                    @else
                                        <span class="cds--tag cds--tag--gray">None</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="newHostModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.databases') }}" method="POST">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Create New Database Host</h4>
                </div>
                <div class="modal-body">
                    <div class="cds--form-item">
                        <label for="pName" class="cds--label">Name</label>
                        <input type="text" name="name" id="pName" class="cds--text-input" />
                        <p class="cds--form__helper-text">A short identifier used to distinguish this location from others. Must be between 1 and 60 characters, for example, <code>us.nyc.lvl3</code>.</p>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="pHost" class="cds--label">Host</label>
                            <input type="text" name="host" id="pHost" class="cds--text-input" />
                            <p class="cds--form__helper-text">The IP address or FQDN that should be used when attempting to connect to this MySQL host <em>from the panel</em> to add new databases.</p>
                        </div>
                        <div class="col-md-6">
                            <label for="pPort" class="cds--label">Port</label>
                            <input type="text" name="port" id="pPort" class="cds--text-input" value="3306"/>
                            <p class="cds--form__helper-text">The port that MySQL is running on for this host.</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="pUsername" class="cds--label">Username</label>
                            <input type="text" name="username" id="pUsername" class="cds--text-input" />
                            <p class="cds--form__helper-text">The username of an account that has enough permissions to create new users and databases on the system.</p>
                        </div>
                        <div class="col-md-6">
                            <label for="pPassword" class="cds--label">Password</label>
                            <input type="password" name="password" id="pPassword" class="cds--text-input" />
                            <p class="cds--form__helper-text">The password to the account defined.</p>
                        </div>
                    </div>
                    <div class="cds--form-item">
                        <label for="pNodeId" class="cds--label">Linked Node</label>
                        <select name="node_id" id="pNodeId" class="cds--text-input cds--select-input">
                            <option value="">None</option>
                            @foreach($locations as $location)
                                <optgroup label="{{ $location->short }}">
                                    @foreach($location->nodes as $node)
                                        <option value="{{ $node->id }}">{{ $node->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        <p class="cds--form__helper-text">This setting does nothing other than default to this database host when adding a database to a server on the selected node.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <p class="text-danger small text-left">The account defined for this database host <strong>must</strong> have the <code>WITH GRANT OPTION</code> permission. If the defined account does not have this permission requests to create databases <em>will</em> fail. <strong>Do not use the same account details for MySQL that you have defined for this panel.</strong></p>
                    {!! csrf_field() !!}
                    <button type="button" class="cds--btn cds--btn--secondary cds--btn--sm pull-left" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="cds--btn cds--btn--primary cds--btn--sm">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('footer-scripts')
    @parent
    <script>
        $('#pNodeId').select2();
    </script>
@endsection
