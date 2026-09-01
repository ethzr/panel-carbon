@extends('layouts.admin')

@section('title')
    Locations
@endsection

@section('content-header')
    <nav class="cds--breadcrumb cds--breadcrumb--no-trailing-slash" aria-label="breadcrumb">
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.index') }}" class="cds--link">Admin</a></div>
        <div class="cds--breadcrumb-item"><span class="cds--link">Locations</span></div>
    </nav>
    <h1 class="cds--type-productive-heading-04">Locations</h1>
    <p class="cds--type-body-compact-01">All locations that nodes can be assigned to for easier categorization.</p>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="cds--tile ptero-tile">
            <div class="ptero-tile__header">
                <h3 class="cds--type-productive-heading-02">Location List</h3>
                <div class="ptero-tile__tools">
                    <button class="cds--btn cds--btn--sm cds--btn--primary" data-toggle="modal" data-target="#newLocationModal">Create New</button>
                </div>
            </div>
            <div class="cds--data-table-container">
                <table class="cds--data-table cds--data-table--lg cds--data-table--zebra">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Short Code</th>
                            <th>Description</th>
                            <th class="text-center">Nodes</th>
                            <th class="text-center">Servers</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($locations as $location)
                            <tr>
                                <td><code>{{ $location->id }}</code></td>
                                <td><a href="{{ route('admin.locations.view', $location->id) }}">{{ $location->short }}</a></td>
                                <td>{{ $location->long }}</td>
                                <td class="text-center">{{ $location->nodes_count }}</td>
                                <td class="text-center">{{ $location->servers_count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="newLocationModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.locations') }}" method="POST">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Create Location</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="pShortModal" class="cds--label">Short Code</label>
                            <input type="text" name="short" id="pShortModal" class="cds--text-input" />
                            <p class="cds--form__helper-text">A short identifier used to distinguish this location from others. Must be between 1 and 60 characters, for example, <code>us.nyc.lvl3</code>.</p>
                        </div>
                        <div class="col-md-12">
                            <label for="pLongModal" class="cds--label">Description</label>
                            <textarea name="long" id="pLongModal" class="cds--text-input" rows="4"></textarea>
                            <p class="cds--form__helper-text">A longer description of this location. Must be less than 191 characters.</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    {!! csrf_field() !!}
                    <button type="button" class="cds--btn cds--btn--secondary cds--btn--sm" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="cds--btn cds--btn--primary cds--btn--sm">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
