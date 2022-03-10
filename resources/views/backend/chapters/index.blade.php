
@extends('backend.layouts.master')

@section('title')
    Chapters List
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
        function css(){
            $('#chapters-table_length').addClass('main__table-text');
            $('#chapters-table_paginate').addClass('paginator');
            $('#chapters-table_length label select').select2();
        }
        function dataTable( name = ' ', sort = ''){
            var myTable = $('#chapters-table').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                destroy:true,
                ajax: {
                    "url"  : '{!! route('backend.chapter.data') !!}',
                    "data" : {
                        "name": name,
                        "sort" : sort,
                    },
                },
                columns: [
                    { data: 'id', name: 'id', orderable: false, searchable: false },
                    { data: 'name', name: 'name', orderable: false, searchable: false , class: 'td-with'},
                    { data: 'description', name: 'description', orderable: false, searchable: false , class: 'td-with'},
                    { data: 'status', name: 'status', orderable: false, searchable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });
            css();
        }
        $('.filter__item-menu li').on('click', function (e) {
            e.preventDefault();
            var sort = $('.filter__item-btn input').val();
            var name = '';
            var slug = '';
            $.ajax({
                url: '{!! route('backend.chapter.data') !!}',
                data: {
                    sort: sort,
                    name: name,
                    slug: slug,
                },
                success: function (response) {
                    dataTable(name, slug, sort);
                },
                error: function (error) {
                    console.log('error');
                }
            })
        })
        function search(e) {
            var sort = $('#sort').val();
            var name = $('#filter').val();
            $.ajax({
                url: '{!! route('backend.chapter.data') !!}',
                data: {
                    sort: sort,
                    name: name,
                },
                success: function (response) {
                    dataTable(name, sort);
                },
                error: function (error) {
                    console.log('error');
                }
            })
            $('#chapters-table').dataTable
        }

        $('#filter').on('change', function (e) {
            e.preventDefault();
            window.setTimeout(search, 200);
        })


        dataTable();
    </script>
@endsection

@section('main__title')
    <h2>Chapter List</h2>
    <div class="main__title-wrap">
        <!-- filter sort -->
        <div class="filter" id="filter__sort">
            <span class="filter__item-label">Sort by:</span>

            <div class="filter__item-btn dropdown-toggle" role="navigation" id="filter-sort" data-toggle="dropdown"
                 aria-haspopup="true" aria-expanded="false">
                <input type="button" value="Date created" id="sort">
                <span></span>
            </div>

            <ul class="filter__item-menu dropdown-menu scrollbar-dropdown" aria-labelledby="filter-sort">
                <li>Date created</li>
                <li>Name</li>
                <li>Realse year</li>
                <li>chap</li>
            </ul>
        </div>
        <!-- end filter sort -->

        <!-- search -->
        <div class="main__title-form">
            <input type="text" placeholder="Find movie.." id="filter">
            <button type="button">
                <i class="icon ion-ios-search"></i>
            </button>
        </div>
        <!-- end search -->
    </div>
    <a href="{{ route('backend.chapter.create') }}" class="main__title-link">add</a>
@endsection

@section('content')
    <div class="col-12">
        <div class="main__table-wrap">
            <table class="main__table" id="chapters-table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>NAME</th>
                    <th>DESCRIPTION</th>
                    <th>STATUS</th>
                    <th>ACTIONS</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
    <!-- end users -->

@endsection
