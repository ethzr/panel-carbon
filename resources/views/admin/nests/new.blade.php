@extends('layouts.admin')

@section('title')
    New Nest
@endsection

@section('content-header')
    <nav class="cds--breadcrumb cds--breadcrumb--no-trailing-slash" aria-label="breadcrumb">
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.index') }}" class="cds--link">Admin</a></div>
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.nests') }}" class="cds--link">Nests</a></div>
        <div class="cds--breadcrumb-item"><span class="cds--link">New</span></div>
    </nav>
    <h1 class="cds--type-productive-heading-04">New Nest</h1>
    <p class="cds--type-body-compact-01">Configure a new nest to deploy to all nodes.</p>
@endsection

@section('content')
<form action="{{ route('admin.nests.new') }}" method="POST">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="ptero-tile__header">
                    <h3 class="cds--type-productive-heading-02">New Nest</h3>
                </div>
                <div class="ptero-tile__body">
                    <div class="cds--form-item">
                        <label class="cds--label">Name</label>
                        <div>
                            <input type="text" name="name" class="cds--text-input" value="{{ old('name') }}" />
                            <p class="text-muted"><small>This should be a descriptive category name that encompasses all of the eggs within the nest.</small></p>
                        </div>
                    </div>
                    <div class="cds--form-item">
                        <label class="cds--label">Description</label>
                        <div>
                            <textarea name="description" class="cds--text-input" rows="6">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="ptero-tile__footer">
                    {!! csrf_field() !!}
                    <button type="submit" class="cds--btn cds--btn--primary pull-right">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
