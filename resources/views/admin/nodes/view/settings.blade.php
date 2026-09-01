@extends('layouts.admin')

@section('title')
    {{ $node->name }}: Settings
@endsection

@section('content-header')
    <nav class="cds--breadcrumb cds--breadcrumb--no-trailing-slash" aria-label="breadcrumb">
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.index') }}" class="cds--link">Admin</a></div>
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.nodes') }}" class="cds--link">Nodes</a></div>
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.nodes.view', $node->id) }}" class="cds--link">{{ $node->name }}</a></div>
        <div class="cds--breadcrumb-item"><span class="cds--link">Settings</span></div>
    </nav>
    <h1 class="cds--type-productive-heading-04">{{ $node->name }}</h1>
    <p class="cds--type-body-compact-01">Configure your node settings.</p>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="cds--tabs">
            <ul class="cds--tab--list">
                <li><a href="{{ route('admin.nodes.view', $node->id) }}">About</a></li>
                <li class="active"><a href="{{ route('admin.nodes.view.settings', $node->id) }}">Settings</a></li>
                <li><a href="{{ route('admin.nodes.view.configuration', $node->id) }}">Configuration</a></li>
                <li><a href="{{ route('admin.nodes.view.allocation', $node->id) }}">Allocation</a></li>
                <li><a href="{{ route('admin.nodes.view.servers', $node->id) }}">Servers</a></li>
            </ul>
        </div>
    </div>
</div>
<form action="{{ route('admin.nodes.view.settings', $node->id) }}" method="POST">
    <div class="row">
        <div class="col-sm-6">
            <div class="box">
                <div class="ptero-tile__header">
                    <h3 class="cds--type-productive-heading-02">Settings</h3>
                </div>
                <div class="ptero-tile__body row">
                    <div class="cds--form-item col-xs-12">
                        <label for="name" class="cds--label">Node Name</label>
                        <div>
                            <input type="text" autocomplete="off" name="name" class="cds--text-input" value="{{ old('name', $node->name) }}" />
                            <p class="text-muted"><small>Character limits: <code>a-zA-Z0-9_.-</code> and <code>[Space]</code> (min 1, max 100 characters).</small></p>
                        </div>
                    </div>
                    <div class="cds--form-item col-xs-12">
                        <label for="description" class="cds--label">Description</label>
                        <div>
                            <textarea name="description" id="description" rows="4" class="cds--text-input">{{ $node->description }}</textarea>
                        </div>
                    </div>
                    <div class="cds--form-item col-xs-12">
                        <label for="name" class="cds--label">Location</label>
                        <div>
                            <select name="location_id" class="cds--text-input cds--select-input">
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}" {{ (((int) old('location_id', $node->location_id)) === $location->id) ? 'selected' : '' }}>{{ $location->long }} ({{ $location->short }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="cds--form-item col-xs-12">
                        <label for="public" class="cds--label">Allow Automatic Allocation <sup><a data-toggle="tooltip" data-placement="top" title="Allow automatic allocation to this Node?">?</a></sup></label>
                        <div>
                            <input type="radio" name="public" value="1" {{ (old('public', $node->public)) ? 'checked' : '' }} id="public_1" checked> <label for="public_1" style="padding-left:5px;">Yes</label><br />
                            <input type="radio" name="public" value="0" {{ (old('public', $node->public)) ? '' : 'checked' }} id="public_0"> <label for="public_0" style="padding-left:5px;">No</label>
                        </div>
                    </div>
                    <div class="cds--form-item col-xs-12">
                        <label for="fqdn" class="cds--label">Fully Qualified Domain Name</label>
                        <div>
                            <input type="text" autocomplete="off" name="fqdn" class="cds--text-input" value="{{ old('fqdn', $node->fqdn) }}" />
                        </div>
                        <p class="text-muted"><small>Please enter domain name (e.g <code>node.example.com</code>) to be used for connecting to the daemon. An IP address may only be used if you are not using SSL for this node.
                                <a tabindex="0" data-toggle="popover" data-trigger="focus" title="Why do I need a FQDN?" data-content="In order to secure communications between your server and this node we use SSL. We cannot generate a SSL certificate for IP Addresses, and as such you will need to provide a FQDN.">Why?</a>
                            </small></p>
                    </div>
                    <div class="cds--form-item col-xs-12">
                        <label class="cds--label"><span class="cds--tag cds--tag--purple"><i class="fa fa-power-off"></i></span> Communicate Over SSL</label>
                        <div>
                            <div class="radio radio-success radio-inline">
                                <input type="radio" id="pSSLTrue" value="https" name="scheme" {{ (old('scheme', $node->scheme) === 'https') ? 'checked' : '' }}>
                                <label for="pSSLTrue"> Use SSL Connection</label>
                            </div>
                            <div class="radio radio-danger radio-inline">
                                <input type="radio" id="pSSLFalse" value="http" name="scheme" {{ (old('scheme', $node->scheme) !== 'https') ? 'checked' : '' }}>
                                <label for="pSSLFalse"> Use HTTP Connection</label>
                            </div>
                        </div>
                        <p class="cds--form__helper-text">In most cases you should select to use a SSL connection. If using an IP Address or you do not wish to use SSL at all, select a HTTP connection.</p>
                    </div>
                    <div class="cds--form-item col-xs-12">
                        <label class="cds--label"><span class="cds--tag cds--tag--purple"><i class="fa fa-power-off"></i></span> Behind Proxy</label>
                        <div>
                            <div class="radio radio-success radio-inline">
                                <input type="radio" id="pProxyFalse" value="0" name="behind_proxy" {{ (old('behind_proxy', $node->behind_proxy) == false) ? 'checked' : '' }}>
                                <label for="pProxyFalse"> Not Behind Proxy </label>
                            </div>
                            <div class="radio radio-info radio-inline">
                                <input type="radio" id="pProxyTrue" value="1" name="behind_proxy" {{ (old('behind_proxy', $node->behind_proxy) == true) ? 'checked' : '' }}>
                                <label for="pProxyTrue"> Behind Proxy </label>
                            </div>
                        </div>
                        <p class="cds--form__helper-text">If you are running the daemon behind a proxy such as Cloudflare, select this to have the daemon skip looking for certificates on boot.</p>
                    </div>
                    <div class="cds--form-item col-xs-12">
                        <label class="cds--label"><span class="cds--tag cds--tag--purple"><i class="fa fa-wrench"></i></span> Maintenance Mode</label>
                        <div>
                            <div class="radio radio-success radio-inline">
                                <input type="radio" id="pMaintenanceFalse" value="0" name="maintenance_mode" {{ (old('maintenance_mode', $node->maintenance_mode) == false) ? 'checked' : '' }}>
                                <label for="pMaintenanceFalse"> Disabled</label>
                            </div>
                            <div class="radio radio-warning radio-inline">
                                <input type="radio" id="pMaintenanceTrue" value="1" name="maintenance_mode" {{ (old('maintenance_mode', $node->maintenance_mode) == true) ? 'checked' : '' }}>
                                <label for="pMaintenanceTrue"> Enabled</label>
                            </div>
                        </div>
                        <p class="cds--form__helper-text">If the node is marked as 'Under Maintenance' users won't be able to access servers that are on this node.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="box">
                <div class="ptero-tile__header">
                    <h3 class="cds--type-productive-heading-02">Allocation Limits</h3>
                </div>
                <div class="ptero-tile__body row">
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="cds--form-item col-xs-6">
                                <label for="memory" class="cds--label">Total Memory</label>
                                <div class="input-group">
                                    <input type="text" name="memory" class="cds--text-input" data-multiplicator="true" value="{{ old('memory', $node->memory) }}"/>
                                    <span class="input-group-addon">MiB</span>
                                </div>
                            </div>
                            <div class="cds--form-item col-xs-6">
                                <label for="memory_overallocate" class="cds--label">Overallocate</label>
                                <div class="input-group">
                                    <input type="text" name="memory_overallocate" class="cds--text-input" value="{{ old('memory_overallocate', $node->memory_overallocate) }}"/>
                                    <span class="input-group-addon">%</span>
                                </div>
                            </div>
                        </div>
                        <p class="cds--form__helper-text">Enter the total amount of memory available on this node for allocation to servers. You may also provide a percentage that can allow allocation of more than the defined memory.</p>
                    </div>
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="cds--form-item col-xs-6">
                                <label for="disk" class="cds--label">Disk Space</label>
                                <div class="input-group">
                                    <input type="text" name="disk" class="cds--text-input" data-multiplicator="true" value="{{ old('disk', $node->disk) }}"/>
                                    <span class="input-group-addon">MiB</span>
                                </div>
                            </div>
                            <div class="cds--form-item col-xs-6">
                                <label for="disk_overallocate" class="cds--label">Overallocate</label>
                                <div class="input-group">
                                    <input type="text" name="disk_overallocate" class="cds--text-input" value="{{ old('disk_overallocate', $node->disk_overallocate) }}"/>
                                    <span class="input-group-addon">%</span>
                                </div>
                            </div>
                        </div>
                        <p class="cds--form__helper-text">Enter the total amount of disk space available on this node for server allocation. You may also provide a percentage that will determine the amount of disk space over the set limit to allow.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="box">
                <div class="ptero-tile__header">
                    <h3 class="cds--type-productive-heading-02">General Configuration</h3>
                </div>
                <div class="ptero-tile__body row">
                    <div class="cds--form-item col-xs-12">
                        <label for="disk_overallocate" class="cds--label">Maximum Web Upload Filesize</label>
                        <div class="input-group">
                            <input type="text" name="upload_size" class="cds--text-input" value="{{ old('upload_size', $node->upload_size) }}"/>
                            <span class="input-group-addon">MiB</span>
                        </div>
                        <p class="text-muted"><small>Enter the maximum size of files that can be uploaded through the web-based file manager.</small></p>
                    </div>
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="cds--form-item col-md-6">
                                <label for="daemonListen" class="cds--label"><span class="cds--tag cds--tag--purple"><i class="fa fa-power-off"></i></span> Daemon Port</label>
                                <div>
                                    <input type="text" name="daemonListen" class="cds--text-input" value="{{ old('daemonListen', $node->daemonListen) }}"/>
                                </div>
                            </div>
                            <div class="cds--form-item col-md-6">
                                <label for="daemonSFTP" class="cds--label"><span class="cds--tag cds--tag--purple"><i class="fa fa-power-off"></i></span> Daemon SFTP Port</label>
                                <div>
                                    <input type="text" name="daemonSFTP" class="cds--text-input" value="{{ old('daemonSFTP', $node->daemonSFTP) }}"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <p class="text-muted"><small>The daemon runs its own SFTP management container and does not use the SSHd process on the main physical server. <Strong>Do not use the same port that you have assigned for your physical server's SSH process.</strong></small></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="cds--tile ptero-tile">
                <div class="ptero-tile__header">
                    <h3 class="cds--type-productive-heading-02">Save Settings</h3>
                </div>
                <div class="ptero-tile__body row">
                    <div class="cds--form-item col-sm-6">
                        <div>
                            <input type="checkbox" name="reset_secret" id="reset_secret" /> <label for="reset_secret" class="cds--label">Reset Daemon Master Key</label>
                        </div>
                        <p class="text-muted"><small>Resetting the daemon master key will void any request coming from the old key. This key is used for all sensitive operations on the daemon including server creation and deletion. We suggest changing this key regularly for security.</small></p>
                    </div>
                </div>
                <div class="ptero-tile__footer">
                    {!! method_field('PATCH') !!}
                    {!! csrf_field() !!}
                    <button type="submit" class="cds--btn cds--btn--primary pull-right">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('footer-scripts')
    @parent
    <script>
    $('[data-toggle="popover"]').popover({
        placement: 'auto'
    });
    $('select[name="location_id"]').select2();
    </script>
@endsection
