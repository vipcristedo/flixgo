    
@extends('backend.layouts.master')

@section('title')
Admin List
@endsection

@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="{{ asset('backend/css/datatable.css') }}">
@endsection

@section('js')
<script src="{{ asset('backend/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('backend/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend/js/sweetalert.min.js') }}"></script>
<script>
$(function(){
    function css(){
        $('#admins-table_length').addClass('main__table-text');
        $('#admins-table_paginate').addClass('paginator');
        $('#admins-table_length label select').select2();
    }
    function dataTable(name = '', sort = ''){
        var myTable = $('#admins-table').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            destroy:true,
            ajax: {
                "url"  : '{!! route('backend.admin.data') !!}',
                "data" : {
                    "name" : name,
                    "sort" : sort,
                },
            },
            columns: [
                { data: 'id', name: 'id', orderable: false, searchable: false },
                { data: 'name', name: 'name', orderable: false, searchable: false },
                { data: 'is_active', name: 'is_active', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]
        });
        css();
    }
    
    dataTable();

    $('.filter__item-menu li').on('click', function(e){
        e.preventDefault();
        var sort = $('.filter__item-btn input').val();
        var name = $('#filter').val();
        $.ajax({
            url: '{!! route('backend.admin.data') !!}',
            data: {
                sort : sort,
                name : name,
            },
            success: function (response) {
                dataTable(name, sort);
            },
            error: function (error) {
                console.log('error');
            }
        })
    })
    function search (e){
        var sort = $('.filter__item-btn input').val();
        var name = $('#filter').val();
        $.ajax({
            url: '{!! route('backend.admin.data') !!}',
            data: {
                sort : sort,
                name : name,
            },
            success: function (response) {
                dataTable(name, sort);
            },
            error: function (error) {
                console.log('error');
            }
        })
    }

    $('#filter').on('change',function(e){
        e.preventDefault();
        window.setTimeout( search, 200 );
    })
})
</script>
@endsection

@section('main__title')
<h2>Admin List</h2>
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
        <input type="text" placeholder="Find admin.." id="filter">
        <button type="button">
            <i class="icon ion-ios-search"></i>
        </button>
    </div>
    <!-- end search -->
</div>
<a href="{{ route('backend.admin.create') }}" class="main__title-link">add</a>
@endsection

@section('content')
<div class="col-12">
    <div class="main__table-wrap">
        <table class="main__table" id="admins-table" style="width: 100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>NAME</th>
                    <th>STATUS</th>
                    <th>ACTIONS</th>
                </tr>
            </thead>

            {{-- <tbody>
                @foreach($admins as $key => $admin)
                <tr>
                    <td>
                        <div class="main__table-text">{{ $admin->id }}</div>
                    </td>
                    <td>
                        <div class="main__table-text">{{$admin->name}}</div>
                    </td>
                    <td>
                        @if($admin->is_active == 1)
                        <div class="main__table-text main__table-text--green">
                        Active
                        </div>
                        @else
                        <div class="main__table-text main__table-text--red">
                        Hidden
                        </div>
                        @endif
                    </td>
                    <td>
                        <div class="main__table-btns">
                            <a href="{{ route('backend.admin.edit' , $admin->id) }}" class="main__table-btn main__table-btn--edit">
                                <i class="icon ion-ios-create"></i>
                            </a>
                            <form action="{{ route('backend.admin.destroy' , $admin->id) }}" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <button type="submit" class="main__table-btn main__table-btn--delete">
                                    <i class="icon ion-ios-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody> --}}
        </table>
    </div>
</div>
<!-- end users -->
@endsection