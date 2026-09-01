@extends('layouts.admin')

@section('title')
    Egg &rarr; {{ $egg->name }} &rarr; Variables
@endsection

@section('content-header')
    <nav class="cds--breadcrumb cds--breadcrumb--no-trailing-slash" aria-label="breadcrumb">
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.index') }}" class="cds--link">Admin</a></div>
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.nests') }}" class="cds--link">Nests</a></div>
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.nests.view', $egg->nest->id) }}" class="cds--link">{{ $egg->nest->name }}</a></div>
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.nests.egg.view', $egg->id) }}" class="cds--link">{{ $egg->name }}</a></div>
        <div class="cds--breadcrumb-item"><span class="cds--link">Variables</span></div>
    </nav>
    <h1 class="cds--type-productive-heading-04">{{ $egg->name }}</h1>
    <p class="cds--type-body-compact-01">Managing variables for this Egg.</p>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="cds--tabs">
            <ul class="cds--tab--list">
                <li><a href="{{ route('admin.nests.egg.view', $egg->id) }}">Configuration</a></li>
                <li class="active"><a href="{{ route('admin.nests.egg.variables', $egg->id) }}">Variables</a></li>
                <li><a href="{{ route('admin.nests.egg.scripts', $egg->id) }}">Install Script</a></li>
            </ul>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <div class="cds--tile ptero-tile">
            <div class="ptero-tile__body">
                <a href="#" class="cds--btn cds--btn--sm cds--btn--primary pull-right" data-toggle="modal" data-target="#newVariableModal">Create New Variable</a>
            </div>
        </div>
    </div>
</div>
<div class="row">
    @foreach($egg->variables as $variable)
        <div class="col-sm-6">
            <div class="box">
                <div class="ptero-tile__header">
                    <h3 class="cds--type-productive-heading-02">{{ $variable->name }}</h3>
                </div>
                <form action="{{ route('admin.nests.egg.variables.edit', ['egg' => $egg->id, 'variable' => $variable->id]) }}" method="POST">
                    <div class="ptero-tile__body">
                        <div class="cds--form-item">
                            <label class="cds--label">Name</label>
                            <input type="text" name="name" value="{{ $variable->name }}" class="cds--text-input" />
                        </div>
                        <div class="cds--form-item">
                            <label class="cds--label">Description</label>
                            <textarea name="description" class="cds--text-input" rows="3">{{ $variable->description }}</textarea>
                        </div>
                        <div class="row">
                            <div class="cds--form-item col-md-6">
                                <label class="cds--label">Environment Variable</label>
                                <input type="text" name="env_variable" value="{{ $variable->env_variable }}" class="cds--text-input" />
                            </div>
                            <div class="cds--form-item col-md-6">
                                <label class="cds--label">Default Value</label>
                                <input type="text" name="default_value" value="{{ $variable->default_value }}" class="cds--text-input" />
                            </div>
                            <div class="col-xs-12">
                                <p class="cds--form__helper-text">This variable can be accessed in the startup command by using <code>{{ $variable->env_variable }}</code>.</p>
                            </div>
                        </div>
                        <div class="cds--form-item">
                            <label class="cds--label">Permissions</label>
                            <select name="options[]" class="pOptions cds--text-input cds--select-input" multiple>
                                <option value="user_viewable" {{ (! $variable->user_viewable) ?: 'selected' }}>Users Can View</option>
                                <option value="user_editable" {{ (! $variable->user_editable) ?: 'selected' }}>Users Can Edit</option>
                            </select>
                        </div>
                        <div class="cds--form-item">
                            <label class="cds--label">Input Rules</label>
                            <input type="text" name="rules" class="cds--text-input" value="{{ $variable->rules }}" />
                            <p class="cds--form__helper-text">These rules are defined using standard <a href="https://laravel.com/docs/11.x/validation#available-validation-rules" target="_blank">Laravel Framework validation rules</a>.</p>
                        </div>
                    </div>
                    <div class="ptero-tile__footer">
                        {!! csrf_field() !!}
                        <button class="cds--btn cds--btn--sm cds--btn--primary pull-right" name="_method" value="PATCH" type="submit">Save</button>
                        <button class="cds--btn cds--btn--sm cds--btn--danger pull-left muted muted-hover" data-action="delete" name="_method" value="DELETE" type="submit"><i class="fa fa-trash-o"></i></button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
</div>
<div class="modal fade" id="newVariableModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Create New Egg Variable</h4>
            </div>
            <form action="{{ route('admin.nests.egg.variables', $egg->id) }}" method="POST">
                <div class="modal-body">
                    <div class="cds--form-item">
                        <label class="cds--label">Name <span class="field-required"></span></label>
                        <input type="text" name="name" class="cds--text-input" value="{{ old('name') }}"/>
                    </div>
                    <div class="cds--form-item">
                        <label class="cds--label">Description</label>
                        <textarea name="description" class="cds--text-input" rows="3">{{ old('description') }}</textarea>
                    </div>
                    <div class="row">
                        <div class="cds--form-item col-md-6">
                            <label class="cds--label">Environment Variable <span class="field-required"></span></label>
                            <input type="text" name="env_variable" class="cds--text-input" value="{{ old('env_variable') }}" />
                        </div>
                        <div class="cds--form-item col-md-6">
                            <label class="cds--label">Default Value</label>
                            <input type="text" name="default_value" class="cds--text-input" value="{{ old('default_value') }}" />
                        </div>
                        <div class="col-xs-12">
                            <p class="cds--form__helper-text">This variable can be accessed in the startup command by entering <code>@{{environment variable value}}</code>.</p>
                        </div>
                    </div>
                    <div class="cds--form-item">
                        <label class="cds--label">Permissions</label>
                        <select name="options[]" class="pOptions cds--text-input cds--select-input" multiple>
                            <option value="user_viewable">Users Can View</option>
                            <option value="user_editable">Users Can Edit</option>
                        </select>
                    </div>
                    <div class="cds--form-item">
                        <label class="cds--label">Input Rules <span class="field-required"></span></label>
                        <input type="text" name="rules" class="cds--text-input" value="{{ old('rules', 'required|string|max:20') }}" placeholder="required|string|max:20" />
                        <p class="cds--form__helper-text">These rules are defined using standard <a href="https://laravel.com/docs/11.x/validation#available-validation-rules" target="_blank">Laravel Framework validation rules</a>.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    {!! csrf_field() !!}
                    <button type="button" class="cds--btn cds--btn--secondary pull-left" data-dismiss="modal">Close</button>
                    <button type="submit" class="cds--btn cds--btn--primary">Create Variable</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('footer-scripts')
    @parent
    <script>
        $('.pOptions').select2();
        $('[data-action="delete"]').on('mouseenter', function (event) {
            $(this).find('i').html(' Delete Variable');
        }).on('mouseleave', function (event) {
            $(this).find('i').html('');
        });
    </script>
@endsection
