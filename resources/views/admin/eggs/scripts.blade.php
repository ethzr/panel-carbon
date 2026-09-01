@extends('layouts.admin')

@section('title')
    Nests &rarr; Egg: {{ $egg->name }} &rarr; Install Script
@endsection

@section('content-header')
    <nav class="cds--breadcrumb cds--breadcrumb--no-trailing-slash" aria-label="breadcrumb">
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.index') }}" class="cds--link">Admin</a></div>
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.nests') }}" class="cds--link">Nests</a></div>
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.nests.view', $egg->nest->id) }}" class="cds--link">{{ $egg->nest->name }}</a></div>
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.nests.egg.view', $egg->id) }}" class="cds--link">{{ $egg->name }}</a></div>
        <div class="cds--breadcrumb-item"><span class="cds--link">{{ $egg->name }}</span></div>
    </nav>
    <h1 class="cds--type-productive-heading-04">{{ $egg->name }}</h1>
    <p class="cds--type-body-compact-01">Manage the install script for this Egg.</p>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="cds--tabs">
            <ul class="cds--tab--list">
                <li><a href="{{ route('admin.nests.egg.view', $egg->id) }}">Configuration</a></li>
                <li><a href="{{ route('admin.nests.egg.variables', $egg->id) }}">Variables</a></li>
                <li class="active"><a href="{{ route('admin.nests.egg.scripts', $egg->id) }}">Install Script</a></li>
            </ul>
        </div>
    </div>
</div>
<form action="{{ route('admin.nests.egg.scripts', $egg->id) }}" method="POST">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="ptero-tile__header">
                    <h3 class="cds--type-productive-heading-02">Install Script</h3>
                </div>
                @if(! is_null($egg->copyFrom))
                    <div class="ptero-tile__body">
                        <div class="cds--inline-notification cds--inline-notification--warning no-margin">
                            This service option is copying installation scripts and container options from <a href="{{ route('admin.nests.egg.view', $egg->copyFrom->id) }}">{{ $egg->copyFrom->name }}</a>. Any changes you make to this script will not apply unless you select "None" from the dropdown box below.
                        </div>
                    </div>
                @endif
                <div class="ptero-tile__body no-padding">
                    <div id="editor_install"style="height:300px">{{ $egg->script_install }}</div>
                </div>
                <div class="ptero-tile__body">
                    <div class="row">
                        <div class="cds--form-item col-sm-4">
                            <label class="cds--label">Copy Script From</label>
                            <select class="cds--select-input" id="pCopyScriptFrom" name="copy_script_from">
                                <option value="">None</option>
                                @foreach($copyFromOptions as $opt)
                                    <option value="{{ $opt->id }}" {{ $egg->copy_script_from !== $opt->id ?: 'selected' }}>{{ $opt->name }}</option>
                                @endforeach
                            </select>
                            <p class="cds--form__helper-text">If selected, script above will be ignored and script from selected option will be used in place.</p>
                        </div>
                        <div class="cds--form-item col-sm-4">
                            <label class="cds--label">Script Container</label>
                            <input type="text" name="script_container" class="cds--text-input" value="{{ $egg->script_container }}" />
                            <p class="cds--form__helper-text">Docker container to use when running this script for the server.</p>
                        </div>
                        <div class="cds--form-item col-sm-4">
                            <label class="cds--label">Script Entrypoint Command</label>
                            <input type="text" name="script_entry" class="cds--text-input" value="{{ $egg->script_entry }}" />
                            <p class="cds--form__helper-text">The entrypoint command to use for this script.</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 text-muted">
                            The following service options rely on this script:
                            @if(count($relyOnScript) > 0)
                                @foreach($relyOnScript as $rely)
                                    <a href="{{ route('admin.nests.egg.view', $rely->id) }}">
                                        <code>{{ $rely->name }}</code>@if(!$loop->last),&nbsp;@endif
                                    </a>
                                @endforeach
                            @else
                                <em>none</em>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="ptero-tile__footer">
                    {!! csrf_field() !!}
                    <textarea name="script_install" class="hidden"></textarea>
                    <button type="submit" name="_method" value="PATCH" class="cds--btn cds--btn--primary cds--btn--sm pull-right">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('footer-scripts')
    @parent
    {!! Theme::js('vendor/ace/ace.js') !!}
    {!! Theme::js('vendor/ace/ext-modelist.js') !!}
    <script>
    $(document).ready(function () {
        $('#pCopyScriptFrom').select2();

        const InstallEditor = ace.edit('editor_install');
        const Modelist = ace.require('ace/ext/modelist')

        InstallEditor.setTheme('ace/theme/chrome');
        InstallEditor.getSession().setMode('ace/mode/sh');
        InstallEditor.getSession().setUseWrapMode(true);
        InstallEditor.setShowPrintMargin(false);

        $('form').on('submit', function (e) {
            $('textarea[name="script_install"]').val(InstallEditor.getValue());
        });
    });
    </script>

@endsection
