@extends('admin.layouts.admin_layout')
@section('content')
<style type="text/css">
    .table td, .table th {
        font-size: 12px;
        line-height: 2.42857 !important;
    }	
</style>
<div class="page-content-wrapper"> 
    <!-- BEGIN CONTENT BODY -->
    <div class="page-content"> 
        <!-- BEGIN PAGE HEADER--> 
        <!-- BEGIN PAGE BAR -->
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li> <a href="{{ route('admin.home') }}">Home</a> <i class="fa fa-circle"></i> </li>
                <li> <span>Ownership Types</span> </li>
            </ul>
        </div>
        <!-- END PAGE BAR --> 
        <!-- BEGIN PAGE TITLE-->
        <h3 class="page-title">Manage Ownership Types <small>Ownership Types</small> </h3>
        <!-- END PAGE TITLE--> 
        <!-- END PAGE HEADER-->
        <div class="row">
            <div class="col-md-12"> 
                <!-- Begin: life time stats -->
                <div class="portlet light portlet-fit portlet-datatable bordered">
                    <div class="portlet-title">
                        <div class="caption"> <i class="icon-settings font-dark"></i> <span class="caption-subject font-dark sbold uppercase">Ownership Types</span> </div>
                        <div class="actions"> <a href="{{ route('create.ownership.type') }}" class="btn btn-xs btn-success"><i class="glyphicon glyphicon-plus"></i> Add New Ownership Type</a> </div>
                    </div>
                    <div class="portlet-body">
                        <div class="table-container">
                            <form method="post" role="form" id="ownershipType-search-form">
                                <table class="table table-striped table-bordered table-hover"  id="ownershipTypeDatatableAjax">
                                    <thead>
                                        <tr role="row" class="filter">
                                            {{-- <td>{!! Form::select('lang', ['' => 'Select Language']+$languages, config('default_lang'), array('id'=>'lang', 'class'=>'form-control')) !!}</td> --}}
                                            <td><input type="text" class="form-control" name="ownership_type" id="ownership_type" autocomplete="off" placeholder="Ownership Type"></td>
                                            <td><select name="is_active" id="is_active" class="form-control">
                                                    <option value="-1">Is Active?</option>
                                                    <option value="1" selected="selected">Active</option>
                                                    <option value="0">Inactive</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr role="row" class="heading">
                                            {{-- <th>Language</th> --}}
                                            <th>Ownership Type</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END CONTENT BODY --> 
</div>
@endsection
@push('scripts') 
<script>
    $(function () {
        var oTable = $('#ownershipTypeDatatableAjax').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            searching: false,
            /*		
             "order": [[1, "asc"]],            
             paging: true,
             info: true,
             */
            ajax: {
                url: '{!! route('fetch.data.ownership.types') !!}',
                data: function (d) {
                    // d.lang = $('#lang').val();
                    d.lang = 'en';
                    d.ownership_type = $('#ownership_type').val();
                    d.is_active = $('#is_active').val();
                }
            }, columns: [
                // {data: 'lang', name: 'lang'},
                {data: 'ownership_type', name: 'ownership_type'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });
        $('#ownershipType-search-form').on('submit', function (e) {
            oTable.draw();
            e.preventDefault();
        });
        // $('#lang').on('change', function (e) {
        //     oTable.draw();
        //     e.preventDefault();
        // });
        $('#ownership_type').on('keyup', function (e) {
            oTable.draw();
            e.preventDefault();
        });
        $('#is_active').on('change', function (e) {
            oTable.draw();
            e.preventDefault();
        });
    });
    function deleteOwnershipType(id, is_default) {
        var msg = 'Are you sure?';
        if (is_default == 1) {
            msg = 'Are you sure? You are going to delete default Ownership Type, all other non default Ownership Types will be deleted too!';
        }
        if (confirm(msg)) {
            $.post("{{ route('delete.ownership.type') }}", {id: id, _method: 'DELETE', _token: '{{ csrf_token() }}'})
                    .done(function (response) {
                        if (response == 'ok')
                        {
                            var table = $('#ownershipTypeDatatableAjax').DataTable();
                            table.row('ownershipTypeDtRow' + id).remove().draw(false);
                        } else
                        {
                            alert('Request Failed!');
                        }
                    });
        }
    }
    function makeActive(id) {
        $.post("{{ route('make.active.ownership.type') }}", {id: id, _method: 'PUT', _token: '{{ csrf_token() }}'})
                .done(function (response) {
                    if (response == 'ok')
                    {
                        var table = $('#ownershipTypeDatatableAjax').DataTable();
                        table.row('ownershipTypeDtRow' + id).remove().draw(false);
                    } else
                    {
                        alert('Request Failed!');
                    }
                });
    }
    function makeNotActive(id) {
        $.post("{{ route('make.not.active.ownership.type') }}", {id: id, _method: 'PUT', _token: '{{ csrf_token() }}'})
                .done(function (response) {
                    if (response == 'ok')
                    {
                        var table = $('#ownershipTypeDatatableAjax').DataTable();
                        table.row('ownershipTypeDtRow' + id).remove().draw(false);
                    } else
                    {
                        alert('Request Failed!');
                    }
                });
    }
</script> 
@endpush