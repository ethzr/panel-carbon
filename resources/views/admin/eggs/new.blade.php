@extends('layouts.admin')

@section('title')
    Nests &rarr; New Egg
@endsection

@section('content-header')
    <nav class="cds--breadcrumb cds--breadcrumb--no-trailing-slash" aria-label="breadcrumb">
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.index') }}" class="cds--link">Admin</a></div>
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.nests') }}" class="cds--link">Nests</a></div>
        <div class="cds--breadcrumb-item"><span class="cds--link">New Egg</span></div>
    </nav>
    <h1 class="cds--type-productive-heading-04">New Egg</h1>
    <p class="cds--type-body-compact-01">Create a new Egg to assign to servers.</p>
@endsection

@section('content')
<form action="{{ route('admin.nests.egg.new') }}" method="POST">
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
                                <label for="pNestId" class="cds--label">Associated Nest</label>
                                <div>
                                    <select class="cds--select-input" name="nest_id" id="pNestId">
                                        @foreach($nests as $nest)
                                            <option value="{{ $nest->id }}" {{ old('nest_id') != $nest->id ?: 'selected' }}>{{ $nest->name }} &lt;{{ $nest->author }}&gt;</option>
                                        @endforeach
                                    </select>
                                    <p class="cds--form__helper-text">Think of a Nest as a category. You can put multiple Eggs in a nest, but consider putting only Eggs that are related to each other in each Nest.</p>
                                </div>
                            </div>
                            <div class="cds--form-item">
                                <label for="pName" class="cds--label">Name</label>
                                <input type="text" id="pName" name="name" value="{{ old('name') }}" class="cds--text-input" />
                                <p class="cds--form__helper-text">A simple, human-readable name to use as an identifier for this Egg. This is what users will see as their game server type.</p>
                            </div>
                            <div class="cds--form-item">
                                <label for="pDescription" class="cds--label">Description</label>
                                <textarea id="pDescription" name="description" class="cds--text-input" rows="8">{{ old('description') }}</textarea>
                                <p class="cds--form__helper-text">A description of this Egg.</p>
                            </div>
                            <div class="cds--form-item">
                                <div class="checkbox checkbox-primary no-margin-bottom">
                                    <input id="pForceOutgoingIp" name="force_outgoing_ip" type="checkbox" value="1" {{ \Pterodactyl\Helpers\Utilities::checked('force_outgoing_ip', 0) }} />
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
                                <label for="pDockerImage" class="cds--label">Docker Images</label>
                                <textarea id="pDockerImages" name="docker_images" rows="4" placeholder="ghcr.io/pterodactyl/yolks" class="cds--text-input">{{ old('docker_images') }}</textarea>
                                <p class="cds--form__helper-text">The docker images available to servers using this egg. Enter one per line. Users will be able to select from this list of images if more than one value is provided.</p>
                            </div>
                            <div class="cds--form-item">
                                <label for="pStartup" class="cds--label">Startup Command</label>
                                <textarea id="pStartup" name="startup" class="cds--text-input" rows="10">{{ old('startup') }}</textarea>
                                <p class="cds--form__helper-text">The default startup command that should be used for new servers created with this Egg. You can change this per-server as needed.</p>
                            </div>
                            <div class="cds--form-item">
                                <label for="pConfigFeatures" class="cds--label">Features</label>
                                <div>
                                    <select class="cds--text-input cds--select-input" name="features[]" id="pConfigFeatures" multiple>
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
                                <p>All fields are required unless you select a separate option from the 'Copy Settings From' dropdown, in which case fields may be left blank to use the values from that option.</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="cds--form-item">
                                <label for="pConfigFrom" class="cds--label">Copy Settings From</label>
                                <select name="config_from" id="pConfigFrom" class="cds--text-input cds--select-input">
                                    <option value="">None</option>
                                </select>
                                <p class="cds--form__helper-text">If you would like to default to settings from another Egg select it from the dropdown above.</p>
                            </div>
                            <div class="cds--form-item">
                                <label for="pConfigStop" class="cds--label">Stop Command</label>
                                <input type="text" id="pConfigStop" name="config_stop" class="cds--text-input" value="{{ old('config_stop') }}" />
                                <p class="cds--form__helper-text">The command that should be sent to server processes to stop them gracefully. If you need to send a <code>SIGINT</code> you should enter <code>^C</code> here.</p>
                            </div>
                            <div class="cds--form-item">
                                <label for="pConfigLogs" class="cds--label">Log Configuration</label>
                                <textarea data-action="handle-tabs" id="pConfigLogs" name="config_logs" class="cds--text-input" rows="6">{{ old('config_logs') }}</textarea>
                                <p class="cds--form__helper-text">This should be a JSON representation of where log files are stored, and whether or not the daemon should be creating custom logs.</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="cds--form-item">
                                <label for="pConfigFiles" class="cds--label">Configuration Files</label>
                                <textarea data-action="handle-tabs" id="pConfigFiles" name="config_files" class="cds--text-input" rows="6">{{ old('config_files') }}</textarea>
                                <p class="cds--form__helper-text">This should be a JSON representation of configuration files to modify and what parts should be changed.</p>
                            </div>
                            <div class="cds--form-item">
                                <label for="pConfigStartup" class="cds--label">Start Configuration</label>
                                <textarea data-action="handle-tabs" id="pConfigStartup" name="config_startup" class="cds--text-input" rows="6">{{ old('config_startup') }}</textarea>
                                <p class="cds--form__helper-text">This should be a JSON representation of what values the daemon should be looking for when booting a server to determine completion.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ptero-tile__footer">
                    {!! csrf_field() !!}
                    <button type="submit" class="cds--btn cds--btn--primary cds--btn--sm pull-right">Create</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('footer-scripts')
    @parent
    {!! Theme::js('vendor/lodash/lodash.js') !!}
    <script>
    $(document).ready(function() {
        $('#pNestId').select2().change();
        $('#pConfigFrom').select2();
    });
    $('#pNestId').on('change', function (event) {
        $('#pConfigFrom').html('<option value="">None</option>').select2({
            data: $.map(_.get(Pterodactyl.nests, $(this).val() + '.eggs', []), function (item) {
                return {
                    id: item.id,
                    text: item.name + ' <' + item.author + '>',
                };
            }),
        });
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
