
@extends('backend.layouts.master')

@section('title')
Permission List
@endsection

@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="{{ asset('backend/css/datatable.css') }}">
@endsection

@section('js')
<script src="{{ asset('backend/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('backend/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend/js/sweetalert.min.js') }}"></script>

<script type="text/javascript">
$(function(){
    function css(){
        $('#permissions-table_length').addClass('main__table-text');
        $('#permissions-table_paginate').addClass('paginator');
        $('#permissions-table_length label select').select2();
    }
    function dataTable(name = '', sort = ''){
        var myTable = $('#permissions-table').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            destroy:true,
            ajax: {
                "url"  : '{!! route('backend.permission.data') !!}',
                "data" : {
                    "name" : name,
                    "sort" : sort,
                },
            },
            columns: [
                { data: 'id', name: 'id', orderable: false, searchable: false },
                { data: 'name', name: 'name', orderable: false, searchable: false, class: 'td-with' },
                { data: 'display_name', name: 'display_name', orderable: false, searchable: false, class: 'td-with' },
                { data: 'status', name: 'status', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]
        });
        css();
    }

    dataTable();

    $('.filter__item-menu li').on('click', function(e){
        e.preventDefault();
        var sort = $('.filter__item-btn input').val();
        var title = $('#filter').val();
        
    $('body').append('<p>'+sort+'</p>');
        $.ajax({
            url: '{!! route('backend.permission.data') !!}',
            data: {
                sort : sort,
                title : title,
            },
            success: function (response) {
                dataTable(title, sort);
            },
            error: function (error) {
            }
        })
    })
    function search (e){
        var sort = $('.filter__item-btn input').val();
        var title = $('#filter').val();
        $.ajax({
            url: '{!! route('backend.permission.data') !!}',
            data: {
                sort : sort,
                title : title,
            },
            success: function (response) {
                dataTable(title, sort);
            },
            error: function (error) {
            }
        })
    }

    $('#filter').on('change',function(e){
        e.preventDefault();
        window.setTimeout( search, 200 );
    })
});

</script>
@endsection

@section('main__title')
<h2>Permission List</h2>
{{-- <span class="main__title-stat">{{ count($permissions) }} Total</span> --}}
<div class="main__title-wrap">
    <!-- filter sort -->
    <div class="filter" id="filter__sort">
        <span class="filter__item-label">Sort by:</span>

        <div class="filter__item-btn dropdown-toggle" role="navigation" id="filter-sort" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <input type="button" value="Date created">
            <span></span>
        </div>

        <ul class="filter__item-menu dropdown-menu scrollbar-dropdown" aria-labelledby="filter-sort">
            <li>Date created</li>
            <li>Name</li>
            <li>Active</li>
            <li>Hidden</li>
        </ul>
    </div>
    <!-- end filter sort -->

    <!-- search -->
    <div class="main__title-form">
        <input type="text" placeholder="Find permission.." id="filter">
        <button type="button">
            <i class="icon ion-ios-search"></i>
        </button>
    </div>
    <!-- end search -->
</div>
@endsection

@section('content')
<div class="col-12">
    <div class="main__table-wrap">
        <table class="main__table" id="permissions-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>NAME</th>
                    <th>DISPLAY NAME</th>
                    <th>STATUS</th>
                    <th>ACTIONS</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<!-- end users -->
@endsection