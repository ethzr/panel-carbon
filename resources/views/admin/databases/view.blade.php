@extends('layouts.admin')

@section('title')
    Database Hosts &rarr; View &rarr; {{ $host->name }}
@endsection

@section('content-header')
    <nav class="cds--breadcrumb cds--breadcrumb--no-trailing-slash" aria-label="breadcrumb">
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.index') }}" class="cds--link">Admin</a></div>
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.databases') }}" class="cds--link">Database Hosts</a></div>
        <div class="cds--breadcrumb-item"><span class="cds--link">{{ $host->name }}</span></div>
    </nav>
    <h1 class="cds--type-productive-heading-04">{{ $host->name }}</h1>
    <p class="cds--type-body-compact-01">Viewing associated databases and details for this database host.</p>
@endsection

@section('content')
<form action="{{ route('admin.databases.view', $host->id) }}" method="POST">
    <div class="row">
        <div class="col-sm-6">
            <div class="cds--tile ptero-tile">
                <div class="ptero-tile__header">
                    <h3 class="cds--type-productive-heading-02">Host Details</h3>
                </div>
                <div class="ptero-tile__body">
                    <div class="cds--form-item">
                        <label for="pName" class="cds--label">Name</label>
                        <input type="text" id="pName" name="name" class="cds--text-input" value="{{ old('name', $host->name) }}" />
                    </div>
                    <div class="cds--form-item">
                        <label for="pHost" class="cds--label">Host</label>
                        <input type="text" id="pHost" name="host" class="cds--text-input" value="{{ old('host', $host->host) }}" />
                        <p class="cds--form__helper-text">The IP address or FQDN that should be used when attempting to connect to this MySQL host <em>from the panel</em> to add new databases.</p>
                    </div>
                    <div class="cds--form-item">
                        <label for="pPort" class="cds--label">Port</label>
                        <input type="text" id="pPort" name="port" class="cds--text-input" value="{{ old('port', $host->port) }}" />
                        <p class="cds--form__helper-text">The port that MySQL is running on for this host.</p>
                    </div>
                    <div class="cds--form-item">
                        <label for="pNodeId" class="cds--label">Linked Node</label>
                        <select name="node_id" id="pNodeId" class="cds--text-input cds--select-input">
                            <option value="">None</option>
                            @foreach($locations as $location)
                                <optgroup label="{{ $location->short }}">
                                    @foreach($location->nodes as $node)
                                        <option value="{{ $node->id }}" {{ $host->node_id !== $node->id ?: 'selected' }}>{{ $node->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        <p class="cds--form__helper-text">This setting does nothing other than default to this database host when adding a database to a server on the selected node.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="cds--tile ptero-tile">
                <div class="ptero-tile__header">
                    <h3 class="cds--type-productive-heading-02">User Details</h3>
                </div>
                <div class="ptero-tile__body">
                    <div class="cds--form-item">
                        <label for="pUsername" class="cds--label">Username</label>
                        <input type="text" name="username" id="pUsername" class="cds--text-input" value="{{ old('username', $host->username) }}" />
                        <p class="cds--form__helper-text">The username of an account that has enough permissions to create new users and databases on the system.</p>
                    </div>
                    <div class="cds--form-item">
                        <label for="pPassword" class="cds--label">Password</label>
                        <input type="password" name="password" id="pPassword" class="cds--text-input" />
                        <p class="cds--form__helper-text">The password to the account defined. Leave blank to continue using the assigned password.</p>
                    </div>
                    <hr />
                    <p class="text-danger small text-left">The account defined for this database host <strong>must</strong> have the <code>WITH GRANT OPTION</code> permission. If the defined account does not have this permission requests to create databases <em>will</em> fail. <strong>Do not use the same account details for MySQL that you have defined for this panel.</strong></p>
                </div>
                <div class="ptero-tile__footer">
                    {!! csrf_field() !!}
                    <button name="_method" value="PATCH" class="cds--btn cds--btn--sm cds--btn--primary pull-right">Save</button>
                    <button name="_method" value="DELETE" class="cds--btn cds--btn--sm cds--btn--danger pull-left muted muted-hover"><i class="fa fa-trash-o"></i></button>
                </div>
            </div>
        </div>
    </div>
</form>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="ptero-tile__header">
                <h3 class="cds--type-productive-heading-02">Databases</h3>
            </div>
            <div class="cds--data-table-container">
                <table class="cds--data-table cds--data-table--lg cds--data-table--zebra">
                    <tr>
                        <th>Server</th>
                        <th>Database Name</th>
                        <th>Username</th>
                        <th>Connections From</th>
                        <th>Max Connections</th>
                        <th></th>
                    </tr>
                    @foreach($databases as $database)
                        <tr>
                            <td class="middle"><a href="{{ route('admin.servers.view', $database->getRelation('server')->id) }}">{{ $database->getRelation('server')->name }}</a></td>
                            <td class="middle">{{ $database->database }}</td>
                            <td class="middle">{{ $database->username }}</td>
                            <td class="middle">{{ $database->remote }}</td>
                            @if($database->max_connections != null)
                                <td class="middle">{{ $database->max_connections }}</td>
                            @else
                                <td class="middle">Unlimited</td>
                            @endif
                            <td class="text-center">
                                <a href="{{ route('admin.servers.view.database', $database->getRelation('server')->id) }}">
                                    <button class="cds--btn cds--btn--sm cds--btn--primary">Manage</button>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
            @if($databases->hasPages())
                <div class="ptero-tile__footer">
                    <div class="col-md-12 text-center">{!! $databases->render() !!}</div>
                </div>
            @endif
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
