@extends('layouts.admin')

@section('title')
    Nodes &rarr; New
@endsection

@section('content-header')
    <nav class="cds--breadcrumb cds--breadcrumb--no-trailing-slash" aria-label="breadcrumb">
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.index') }}" class="cds--link">Admin</a></div>
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.nodes') }}" class="cds--link">Nodes</a></div>
        <div class="cds--breadcrumb-item"><span class="cds--link">New</span></div>
    </nav>
    <h1 class="cds--type-productive-heading-04">New Node</h1>
    <p class="cds--type-body-compact-01">Create a new local or remote node for servers to be installed to.</p>
@endsection

@section('content')
<form action="{{ route('admin.nodes.new') }}" method="POST">
    <div class="row">
        <div class="col-sm-6">
            <div class="cds--tile ptero-tile">
                <div class="ptero-tile__header">
                    <h3 class="cds--type-productive-heading-02">Basic Details</h3>
                </div>
                <div class="ptero-tile__body">
                    <div class="cds--form-item">
                        <label for="pName" class="cds--label">Name</label>
                        <input type="text" name="name" id="pName" class="cds--text-input" value="{{ old('name') }}"/>
                        <p class="cds--form__helper-text">Character limits: <code>a-zA-Z0-9_.-</code> and <code>[Space]</code> (min 1, max 100 characters).</p>
                    </div>
                    <div class="cds--form-item">
                        <label for="pDescription" class="cds--label">Description</label>
                        <textarea name="description" id="pDescription" rows="4" class="cds--text-input">{{ old('description') }}</textarea>
                    </div>
                    <div class="cds--form-item">
                        <label for="pLocationId" class="cds--label">Location</label>
                        <select class="cds--select-input" name="location_id" id="pLocationId">
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}" {{ $location->id != old('location_id') ?: 'selected' }}>{{ $location->short }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="cds--form-item">
                        <label class="cds--label">Node Visibility</label>
                        <div>
                            <div class="radio radio-success radio-inline">

                                <input type="radio" id="pPublicTrue" value="1" name="public" checked>
                                <label for="pPublicTrue"> Public </label>
                            </div>
                            <div class="radio radio-danger radio-inline">
                                <input type="radio" id="pPublicFalse" value="0" name="public">
                                <label for="pPublicFalse"> Private </label>
                            </div>
                        </div>
                        <p class="cds--form__helper-text">By setting a node to <code>private</code> you will be denying the ability to auto-deploy to this node.
                    </div>
                    <div class="cds--form-item">
                        <label for="pFQDN" class="cds--label">FQDN</label>
                        <input type="text" name="fqdn" id="pFQDN" class="cds--text-input" value="{{ old('fqdn') }}"/>
                        <p class="cds--form__helper-text">Please enter domain name (e.g <code>node.example.com</code>) to be used for connecting to the daemon. An IP address may be used <em>only</em> if you are not using SSL for this node.</p>
                    </div>
                    <div class="cds--form-item">
                        <label class="cds--label">Communicate Over SSL</label>
                        <div>
                            <div class="radio radio-success radio-inline">
                                <input type="radio" id="pSSLTrue" value="https" name="scheme" checked>
                                <label for="pSSLTrue"> Use SSL Connection</label>
                            </div>
                            <div class="radio radio-danger radio-inline">
                                <input type="radio" id="pSSLFalse" value="http" name="scheme" @if(request()->isSecure()) disabled @endif>
                                <label for="pSSLFalse"> Use HTTP Connection</label>
                            </div>
                        </div>
                        @if(request()->isSecure())
                            <p class="text-danger small">Your Panel is currently configured to use a secure connection. In order for browsers to connect to your node it <strong>must</strong> use a SSL connection.</p>
                        @else
                            <p class="cds--form__helper-text">In most cases you should select to use a SSL connection. If using an IP Address or you do not wish to use SSL at all, select a HTTP connection.</p>
                        @endif
                    </div>
                    <div class="cds--form-item">
                        <label class="cds--label">Behind Proxy</label>
                        <div>
                            <div class="radio radio-success radio-inline">
                                <input type="radio" id="pProxyFalse" value="0" name="behind_proxy" checked>
                                <label for="pProxyFalse"> Not Behind Proxy </label>
                            </div>
                            <div class="radio radio-info radio-inline">
                                <input type="radio" id="pProxyTrue" value="1" name="behind_proxy">
                                <label for="pProxyTrue"> Behind Proxy </label>
                            </div>
                        </div>
                        <p class="cds--form__helper-text">If you are running the daemon behind a proxy such as Cloudflare, select this to have the daemon skip looking for certificates on boot.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="cds--tile ptero-tile">
                <div class="ptero-tile__header">
                    <h3 class="cds--type-productive-heading-02">Configuration</h3>
                </div>
                <div class="ptero-tile__body">
                    <div class="row">
                        <div class="cds--form-item col-md-6">
                            <label for="pDaemonBase" class="cds--label">Daemon Server File Directory</label>
                            <input type="text" name="daemonBase" id="pDaemonBase" class="cds--text-input" value="/var/lib/pterodactyl/volumes" />
                            <p class="cds--form__helper-text">Enter the directory where server files should be stored. <strong>If you use OVH you should check your partition scheme. You may need to use <code>/home/daemon-data</code> to have enough space.</strong></p>
                        </div>
                        <div class="cds--form-item col-md-6">
                            <label for="pMemory" class="cds--label">Total Memory</label>
                            <div class="input-group">
                                <input type="text" name="memory" data-multiplicator="true" class="cds--text-input" id="pMemory" value="{{ old('memory') }}"/>
                                <span class="input-group-addon">MiB</span>
                            </div>
                        </div>
                        <div class="cds--form-item col-md-6">
                            <label for="pMemoryOverallocate" class="cds--label">Memory Over-Allocation</label>
                            <div class="input-group">
                                <input type="text" name="memory_overallocate" class="cds--text-input" id="pMemoryOverallocate" value="{{ old('memory_overallocate') }}"/>
                                <span class="input-group-addon">%</span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <p class="cds--form__helper-text">Enter the total amount of memory available for new servers. If you would like to allow overallocation of memory enter the percentage that you want to allow. To disable checking for overallocation enter <code>-1</code> into the field. Entering <code>0</code> will prevent creating new servers if it would put the node over the limit.</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="cds--form-item col-md-6">
                            <label for="pDisk" class="cds--label">Total Disk Space</label>
                            <div class="input-group">
                                <input type="text" name="disk" data-multiplicator="true" class="cds--text-input" id="pDisk" value="{{ old('disk') }}"/>
                                <span class="input-group-addon">MiB</span>
                            </div>
                        </div>
                        <div class="cds--form-item col-md-6">
                            <label for="pDiskOverallocate" class="cds--label">Disk Over-Allocation</label>
                            <div class="input-group">
                                <input type="text" name="disk_overallocate" class="cds--text-input" id="pDiskOverallocate" value="{{ old('disk_overallocate') }}"/>
                                <span class="input-group-addon">%</span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <p class="cds--form__helper-text">Enter the total amount of disk space available for new servers. If you would like to allow overallocation of disk space enter the percentage that you want to allow. To disable checking for overallocation enter <code>-1</code> into the field. Entering <code>0</code> will prevent creating new servers if it would put the node over the limit.</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="cds--form-item col-md-6">
                            <label for="pDaemonListen" class="cds--label">Daemon Port</label>
                            <input type="text" name="daemonListen" class="cds--text-input" id="pDaemonListen" value="8080" />
                        </div>
                        <div class="cds--form-item col-md-6">
                            <label for="pDaemonSFTP" class="cds--label">Daemon SFTP Port</label>
                            <input type="text" name="daemonSFTP" class="cds--text-input" id="pDaemonSFTP" value="2022" />
                        </div>
                        <div class="col-md-12">
                            <p class="cds--form__helper-text">The daemon runs its own SFTP management container and does not use the SSHd process on the main physical server. <Strong>Do not use the same port that you have assigned for your physical server's SSH process.</strong> If you will be running the daemon behind CloudFlare&reg; you should set the daemon port to <code>8443</code> to allow websocket proxying over SSL.</p>
                        </div>
                    </div>
                </div>
                <div class="ptero-tile__footer">
                    {!! csrf_field() !!}
                    <button type="submit" class="cds--btn cds--btn--primary pull-right">Create Node</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('footer-scripts')
    @parent
    <script>
        $('#pLocationId').select2();
    </script>
@endsection
