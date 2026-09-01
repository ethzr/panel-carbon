@extends('layouts.admin')

@section('title')
    Locations &rarr; View &rarr; {{ $location->short }}
@endsection

@section('content-header')
    <nav class="cds--breadcrumb cds--breadcrumb--no-trailing-slash" aria-label="breadcrumb">
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.index') }}" class="cds--link">Admin</a></div>
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.locations') }}" class="cds--link">Locations</a></div>
        <div class="cds--breadcrumb-item"><span class="cds--link">{{ $location->short }}</span></div>
    </nav>
    <h1 class="cds--type-productive-heading-04">{{ $location->short }}</h1>
    <p class="cds--type-body-compact-01">{{ str_limit($location->long, 75) }}</p>
@endsection

@section('content')
<div class="row">
    <div class="col-sm-6">
        <div class="cds--tile ptero-tile">
            <div class="ptero-tile__header">
                <h3 class="cds--type-productive-heading-02">Location Details</h3>
            </div>
            <form action="{{ route('admin.locations.view', $location->id) }}" method="POST">
                <div class="ptero-tile__body">
                    <div class="cds--form-item">
                        <label for="pShort" class="cds--label">Short Code</label>
                        <input type="text" id="pShort" name="short" class="cds--text-input" value="{{ $location->short }}" />
                    </div>
                    <div class="cds--form-item">
                        <label for="pLong" class="cds--label">Description</label>
                        <textarea id="pLong" name="long" class="cds--text-input" rows="4">{{ $location->long }}</textarea>
                    </div>
                </div>
                <div class="ptero-tile__footer">
                    {!! csrf_field() !!}
                    {!! method_field('PATCH') !!}
                    <button name="action" value="edit" class="cds--btn cds--btn--sm cds--btn--primary pull-right">Save</button>
                    <button name="action" value="delete" class="cds--btn cds--btn--sm cds--btn--danger pull-left muted muted-hover"><i class="fa fa-trash-o"></i></button>
                </div>
            </form>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="box">
            <div class="ptero-tile__header">
                <h3 class="cds--type-productive-heading-02">Nodes</h3>
            </div>
            <div class="cds--data-table-container">
                <table class="cds--data-table cds--data-table--lg cds--data-table--zebra">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>FQDN</th>
                        <th>Servers</th>
                    </tr>
                    @foreach($location->nodes as $node)
                        <tr>
                            <td><code>{{ $node->id }}</code></td>
                            <td><a href="{{ route('admin.nodes.view', $node->id) }}">{{ $node->name }}</a></td>
                            <td><code>{{ $node->fqdn }}</code></td>
                            <td>{{ $node->servers->count() }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
