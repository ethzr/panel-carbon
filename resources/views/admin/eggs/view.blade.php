@extends('layouts.admin')

@section('title')
    Nests &rarr; Egg: {{ $egg->name }}
@endsection

@section('content-header')
    <nav class="cds--breadcrumb cds--breadcrumb--no-trailing-slash" aria-label="breadcrumb">
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.index') }}" class="cds--link">Admin</a></div>
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.nests') }}" class="cds--link">Nests</a></div>
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.nests.view', $egg->nest->id) }}" class="cds--link">{{ $egg->nest->name }}</a></div>
        <div class="cds--breadcrumb-item"><span class="cds--link">{{ $egg->name }}</span></div>
    </nav>
    <h1 class="cds--type-productive-heading-04">{{ $egg->name }}</h1>
    <p class="cds--type-body-compact-01">{{ str_limit($egg->description, 50) }}</p>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="cds--tabs">
            <ul class="cds--tab--list">
                <li class="active"><a href="{{ route('admin.nests.egg.view', $egg->id) }}">Configuration</a></li>
                <li><a href="{{ route('admin.nests.egg.variables', $egg->id) }}">Variables</a></li>
                <li><a href="{{ route('admin.nests.egg.scripts', $egg->id) }}">Install Script</a></li>
            </ul>
        </div>
    </div>
</div>
<form action="{{ route('admin.nests.egg.view', $egg->id) }}" enctype="multipart/form-data" method="POST">
    <div class="row">
        <div class="col-xs-12">
            <div class="cds--tile ptero-tile ptero-tile--danger">
                <div class="ptero-tile__body">
                    <div class="row">
                        <div class="col-xs-8">
                            <div class="cds--form-item no-margin-bottom">
                                <label for="pName" class="cds--label">Egg File</label>
                                <div>
                                    <input type="file" name="import_file" class="cds--text-input" style="border: 0;margin-left:-10px;" />
                                    <p class="cds--form__helper-text no-margin-bottom">If you would like to replace settings for this Egg by uploading a new JSON file, simply select it here and press "Update Egg". This will not change any existing startup strings or Docker images for existing servers.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            {!! csrf_field() !!}
                            <button type="submit" name="_method" value="PUT" class="cds--btn cds--btn--sm cds--btn--danger pull-right">Update Egg</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<form action="{{ route('admin.nests.egg.view', $egg->id) }}" method="POST">
    <div class="row">
        <div class="col-xs-12">
            <div class="cds--tile ptero-tile">
                <div class="ptero-tile__header">
                    <h3 class="cds--type-productive-heading-02">Configuration</h3>
                </div>
                <div class="ptero-tile__body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="cds--form-item">
                                <label for="pName" class="cds--label">Name <span class="field-required"></span></label>
                                <input type="text" id="pName" name="name" value="{{ $egg->name }}" class="cds--text-input" />
                                <p class="cds--form__helper-text">A simple, human-readable name to use as an identifier for this Egg.</p>
                            </div>
                            <div class="cds--form-item">
                                <label for="pUuid" class="cds--label">UUID</label>
                                <input type="text" id="pUuid" readonly value="{{ $egg->uuid }}" class="cds--text-input" />
                                <p class="cds--form__helper-text">This is the globally unique identifier for this Egg which the Daemon uses as an identifier.</p>
                            </div>
                            <div class="cds--form-item">
                                <label for="pAuthor" class="cds--label">Author</label>
                                <input type="text" id="pAuthor" readonly value="{{ $egg->author }}" class="cds--text-input" />
                                <p class="cds--form__helper-text">The author of this version of the Egg. Uploading a new Egg configuration from a different author will change this.</p>
                            </div>
                            <div class="cds--form-item">
                                <label for="pDockerImage" class="cds--label">Docker Images <span class="field-required"></span></label>
                                <textarea id="pDockerImages" name="docker_images" class="cds--text-input" rows="4">{{ implode(PHP_EOL, $images) }}</textarea>
                                <p class="cds--form__helper-text">
                                    The docker images available to servers using this egg. Enter one per line. Users
                                    will be able to select from this list of images if more than one value is provided.
                                    Optionally, a display name may be provided by prefixing the image with the name
                                    followed by a pipe character, and then the image URL. Example: <code>Display Name|ghcr.io/my/egg</code>
                                </p>
                            </div>
                            <div class="cds--form-item">
                                <div class="checkbox checkbox-primary no-margin-bottom">
                                    <input id="pForceOutgoingIp" name="force_outgoing_ip" type="checkbox" value="1" @if($egg->force_outgoing_ip) checked @endif />
                                    <label for="pForceOutgoingIp" class="strong">Force Outgoing IP</label>
                                    <p class="cds--form__helper-text">
                                        Forces all outgoing network traffic to have its Source IP NATed to the IP of the server's primary allocation IP.
                                        Required for certain games to work properly when the Node has multiple public IP addresses.
                                        <br>
                                        <strong>
                                            Enabling this option will disable internal networking for any servers using this egg,
                                            causing them to be unable to internally access other servers on the same node.
                                        </strong>
                                    </p>
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-6">
                            <div class="cds--form-item">
                                <label for="pDescription" class="cds--label">Description</label>
                                <textarea id="pDescription" name="description" class="cds--text-input" rows="8">{{ $egg->description }}</textarea>
                                <p class="cds--form__helper-text">A description of this Egg that will be displayed throughout the Panel as needed.</p>
                            </div>
                            <div class="cds--form-item">
                                <label for="pStartup" class="cds--label">Startup Command <span class="field-required"></span></label>
                                <textarea id="pStartup" name="startup" class="cds--text-input" rows="8">{{ $egg->startup }}</textarea>
                                <p class="cds--form__helper-text">The default startup command that should be used for new servers using this Egg.</p>
                            </div>
                            <div class="cds--form-item">
                                <label for="pConfigFeatures" class="cds--label">Features</label>
                                <div>
                                    <select class="cds--text-input cds--select-input" name="features[]" id="pConfigFeatures" multiple>
                                        @foreach(($egg->features ?? []) as $feature)
                                            <option value="{{ $feature }}" selected>{{ $feature }}</option>
                                        @endforeach
                                    </select>
                                    <p class="cds--form__helper-text">Additional features belonging to the egg. Useful for configuring additional panel modifications.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="cds--tile ptero-tile">
                <div class="ptero-tile__header">
                    <h3 class="cds--type-productive-heading-02">Process Management</h3>
                </div>
                <div class="ptero-tile__body">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="cds--inline-notification cds--inline-notification--warning">
                                <p>The following configuration options should not be edited unless you understand how this system works. If wrongly modified it is possible for the daemon to break.</p>
                                <p>All fields are required unless you select a separate option from the 'Copy Settings From' dropdown, in which case fields may be left blank to use the values from that Egg.</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="cds--form-item">
                                <label for="pConfigFrom" class="cds--label">Copy Settings From</label>
                                <select name="config_from" id="pConfigFrom" class="cds--text-input cds--select-input">
                                    <option value="">None</option>
                                    @foreach($egg->nest->eggs as $o)
                                        <option value="{{ $o->id }}" {{ ($egg->config_from !== $o->id) ?: 'selected' }}>{{ $o->name }} &lt;{{ $o->author }}&gt;</option>
                                    @endforeach
                                </select>
                                <p class="cds--form__helper-text">If you would like to default to settings from another Egg select it from the menu above.</p>
                            </div>
                            <div class="cds--form-item">
                                <label for="pConfigStop" class="cds--label">Stop Command</label>
                                <input type="text" id="pConfigStop" name="config_stop" class="cds--text-input" value="{{ $egg->config_stop }}" />
                                <p class="cds--form__helper-text">The command that should be sent to server processes to stop them gracefully. If you need to send a <code>SIGINT</code> you should enter <code>^C</code> here.</p>
                            </div>
                            <div class="cds--form-item">
                                <label for="pConfigLogs" class="cds--label">Log Configuration</label>
                                <textarea data-action="handle-tabs" id="pConfigLogs" name="config_logs" class="cds--text-input" rows="6">{{ ! is_null($egg->config_logs) ? json_encode(json_decode($egg->config_logs), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : '' }}</textarea>
                                <p class="cds--form__helper-text">This should be a JSON representation of where log files are stored, and whether or not the daemon should be creating custom logs.</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="cds--form-item">
                                <label for="pConfigFiles" class="cds--label">Configuration Files</label>
                                <textarea data-action="handle-tabs" id="pConfigFiles" name="config_files" class="cds--text-input" rows="6">{{ ! is_null($egg->config_files) ? json_encode(json_decode($egg->config_files), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : '' }}</textarea>
                                <p class="cds--form__helper-text">This should be a JSON representation of configuration files to modify and what parts should be changed.</p>
                            </div>
                            <div class="cds--form-item">
                                <label for="pConfigStartup" class="cds--label">Start Configuration</label>
                                <textarea data-action="handle-tabs" id="pConfigStartup" name="config_startup" class="cds--text-input" rows="6">{{ ! is_null($egg->config_startup) ? json_encode(json_decode($egg->config_startup), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : '' }}</textarea>
                                <p class="cds--form__helper-text">This should be a JSON representation of what values the daemon should be looking for when booting a server to determine completion.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ptero-tile__footer">
                    {!! csrf_field() !!}
                    <button type="submit" name="_method" value="PATCH" class="cds--btn cds--btn--primary cds--btn--sm pull-right">Save</button>
                    <a href="{{ route('admin.nests.egg.export', $egg->id) }}" class="cds--btn cds--btn--sm cds--btn--tertiary" style="margin-right:10px;">Export</a>
                    <button id="deleteButton" type="submit" name="_method" value="DELETE" class="cds--btn cds--btn--danger cds--btn--sm muted muted-hover">
                        <i class="fa fa-trash-o"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('footer-scripts')
    @parent
    <script>
    $('#pConfigFrom').select2();
    $('#deleteButton').on('mouseenter', function (event) {
        $(this).find('i').html(' Delete Egg');
    }).on('mouseleave', function (event) {
        $(this).find('i').html('');
    });
    $('textarea[data-action="handle-tabs"]').on('keydown', function(event) {
        if (event.keyCode === 9) {
            event.preventDefault();

            var curPos = $(this)[0].selectionStart;
            var prepend = $(this).val().substr(0, curPos);
            var append = $(this).val().substr(curPos);

            $(this).val(prepend + '    ' + append);
        }
    });
    $('#pConfigFeatures').select2({
        tags: true,
        selectOnClose: false,
        tokenSeparators: [',', ' '],
    });
    </script>
@endsection
