@extends('layouts.admin')

@section('title')
    Application API
@endsection

@section('content-header')
    <nav class="cds--breadcrumb cds--breadcrumb--no-trailing-slash" aria-label="breadcrumb">
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.index') }}" class="cds--link">Admin</a></div>
        <div class="cds--breadcrumb-item"><a href="{{ route('admin.api.index') }}" class="cds--link">Application API</a></div>
        <div class="cds--breadcrumb-item"><span class="cds--link">New Credentials</span></div>
    </nav>
    <h1 class="cds--type-productive-heading-04">Application API</h1>
    <p class="cds--type-body-compact-01">Create a new application API key.</p>
@endsection

@section('content')
    <div class="row">
        <form method="POST" action="{{ route('admin.api.new') }}">
            <div class="col-sm-8 col-xs-12">
                <div class="cds--tile ptero-tile">
                    <div class="ptero-tile__header">
                        <h3 class="cds--type-productive-heading-02">Select Permissions</h3>
                        <div class="ptero-tile__tools">
                            <div class="cds--btn-set">
                                <button type="button" class="cds--btn cds--btn--sm cds--btn--secondary" id="btn-bulk-read">Read All</button>
                                <button type="button" class="cds--btn cds--btn--sm cds--btn--secondary" id="btn-bulk-rw">Read &amp; Write All</button>
                                <button type="button" class="cds--btn cds--btn--sm cds--btn--secondary" id="btn-bulk-none">None All</button>
                            </div>
                        </div>
                    </div>
                    <div class="cds--data-table-container">
                        <table class="cds--data-table cds--data-table--lg cds--data-table--zebra" style="min-width: 650px;">
                            @foreach($resources as $resource)
                                <tr>
                                    <td class="strong" style="vertical-align: middle; padding-left: 15px;">
                                        {{ str_replace('_', ' ', title_case($resource)) }}
                                    </td>
                                    
                                    <td class="text-center" style="vertical-align: middle;">
                                        <div class="radio radio-primary" style="margin: 0;">
                                            <input type="radio" id="r_{{ $resource }}" name="r_{{ $resource }}" value="{{ $permissions['r'] }}">
                                            <label for="r_{{ $resource }}">Read</label>
                                        </div>
                                    </td>
                                    
                                    <td class="text-center" style="vertical-align: middle;">
                                        <div class="radio radio-primary" style="margin: 0;">
                                            <input type="radio" id="rw_{{ $resource }}" name="r_{{ $resource }}" value="{{ $permissions['rw'] }}">
                                            <label for="rw_{{ $resource }}">Read &amp; Write</label>
                                        </div>
                                    </td>
                                    
                                    <td class="text-center" style="vertical-align: middle;">
                                        <div class="radio" style="margin: 0;">
                                            <input type="radio" id="n_{{ $resource }}" name="r_{{ $resource }}" value="{{ $permissions['n'] }}" checked>
                                            <label for="n_{{ $resource }}">None</label>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-xs-12">
                <div class="cds--tile ptero-tile">
                    <div class="ptero-tile__body">
                        <div class="cds--form-item">
                            <label class="cds--label" for="memoField">Description <span class="field-required"></span></label>
                            <input id="memoField" type="text" name="memo" class="cds--text-input">
                        </div>
                        <p class="text-muted">Once you have assigned permissions and created this set of credentials you will be unable to come back and edit it. If you need to make changes down the road you will need to create a new set of credentials.</p>
                    </div>
                    <div class="ptero-tile__footer">
                        {{ csrf_field() }}
                        <button type="submit" class="cds--btn cds--btn--primary cds--btn--sm pull-right">Create Credentials</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection


@section('footer-scripts')
    @parent
    <script>
        $(document).ready(function() {
            
            function setButtonActive(activeButton) {
                $('#btn-bulk-read, #btn-bulk-rw, #btn-bulk-none')
                    .removeClass('btn-primary')
                    .addClass('btn-default');
                $(activeButton)
                    .removeClass('btn-default')
                    .addClass('btn-primary');
            }

            
            setButtonActive('#btn-bulk-none');

            $('#btn-bulk-read').click(function(e) {
                e.preventDefault();
                $('input[id^="r_"]').prop('checked', true);
                setButtonActive(this); 
            });

            $('#btn-bulk-rw').click(function(e) {
                e.preventDefault();
                $('input[id^="rw_"]').prop('checked', true);
                setButtonActive(this); 
            });

            $('#btn-bulk-none').click(function(e) {
                e.preventDefault();
                $('input[id^="n_"]').prop('checked', true);
                setButtonActive(this); 
            });
            
            
            $('input[type="radio"]').change(function() {
                $('#btn-bulk-read, #btn-bulk-rw, #btn-bulk-none')
                    .removeClass('btn-primary')
                    .addClass('btn-default');
            });
        });
    </script>
@endsection
