@extends('layouts.admin')

@section('title')
    Nests &rarr; {{ $nest->name }}
@endsection

@section('content-header')
    <nav class="cds--breadcrumb cds--breadcrumb--no-trailing-slash" aria-label="breadcrumb">
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.index') }}" class="cds--link">Admin</a></div>
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.nests') }}" class="cds--link">Nests</a></div>
        <div class="cds--breadcrumb-item"><span class="cds--link">{{ $nest->name }}</span></div>
    </nav>
    <h1 class="cds--type-productive-heading-04">{{ $nest->name }}</h1>
    <p class="cds--type-body-compact-01">{{ str_limit($nest->description, 50) }}</p>
@endsection

@section('content')
<div class="row">
    <form action="{{ route('admin.nests.view', $nest->id) }}" method="POST">
        <div class="col-md-6">
            <div class="cds--tile ptero-tile">
                <div class="ptero-tile__body">
                    <div class="cds--form-item">
                        <label class="cds--label">Name <span class="field-required"></span></label>
                        <div>
                            <input type="text" name="name" class="cds--text-input" value="{{ $nest->name }}" />
                            <p class="text-muted"><small>This should be a descriptive category name that encompasses all of the options within the service.</small></p>
                        </div>
                    </div>
                    <div class="cds--form-item">
                        <label class="cds--label">Description</label>
                        <div>
                            <textarea name="description" class="cds--text-input" rows="7">{{ $nest->description }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="ptero-tile__footer">
                    {!! csrf_field() !!}
                    <button type="submit" name="_method" value="PATCH" class="cds--btn cds--btn--primary cds--btn--sm pull-right">Save</button>
                    <button id="deleteButton" type="submit" name="_method" value="DELETE" class="cds--btn cds--btn--sm cds--btn--danger muted muted-hover"><i class="fa fa-trash-o"></i></button>
                </div>
            </div>
        </div>
    </form>
    <div class="col-md-6">
        <div class="cds--tile ptero-tile">
            <div class="ptero-tile__body">
                <div class="cds--form-item">
                    <label class="cds--label">Nest ID</label>
                    <div>
                        <input type="text" readonly class="cds--text-input" value="{{ $nest->id }}" />
                        <p class="cds--form__helper-text">A unique ID used for identification of this nest internally and through the API.</p>
                    </div>
                </div>
                <div class="cds--form-item">
                    <label class="cds--label">Author</label>
                    <div>
                        <input type="text" readonly class="cds--text-input" value="{{ $nest->author }}" />
                        <p class="cds--form__helper-text">The author of this service option. Please direct questions and issues to them unless this is an official option authored by <code>support@pterodactyl.io</code>.</p>
                    </div>
                </div>
                <div class="cds--form-item">
                    <label class="cds--label">UUID</label>
                    <div>
                        <input type="text" readonly class="cds--text-input" value="{{ $nest->uuid }}" />
                        <p class="cds--form__helper-text">A UUID that all servers using this option are assigned for identification purposes.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <div class="cds--tile ptero-tile">
            <div class="ptero-tile__header">
                <h3 class="cds--type-productive-heading-02">Nest Eggs</h3>
            </div>
            <div class="cds--data-table-container">
                <table class="cds--data-table cds--data-table--lg cds--data-table--zebra">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th class="text-center">Servers</th>
                        <th class="text-center"></th>
                    </tr>
                    @foreach($nest->eggs as $egg)
                        <tr>
                            <td class="align-middle"><code>{{ $egg->id }}</code></td>
                            <td class="align-middle"><a href="{{ route('admin.nests.egg.view', $egg->id) }}" data-toggle="tooltip" data-placement="right" title="{{ $egg->author }}">{{ $egg->name }}</a></td>
                            <td class="col-xs-8 align-middle">{{ $egg->description }}</td>
                            <td class="text-center align-middle"><code>{{ $egg->servers->count() }}</code></td>
                            <td class="align-middle">
                                <a href="{{ route('admin.nests.egg.export', ['egg' => $egg->id]) }}"><i class="fa fa-download"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
            <div class="ptero-tile__footer">
                <a href="{{ route('admin.nests.egg.new') }}"><button class="cds--btn cds--btn--primary cds--btn--sm pull-right">New Egg</button></a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer-scripts')
    @parent
    <script>
        $('#deleteButton').on('mouseenter', function (event) {
            $(this).find('i').html(' Delete Nest');
        }).on('mouseleave', function (event) {
            $(this).find('i').html('');
        });
    </script>
@endsection
