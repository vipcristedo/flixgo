@extends('backend.layouts.master')

@section('title')
    Movie List
@endsection

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet'
          type='text/css'>
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/datatable.css') }}">
@endsection

@section('js')
    <script src="{{ asset('backend/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('backend/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/js/sweetalert.min.js') }}"></script>
    <script>
        $(function () {
            function css() {
                $('#movies-table_length').addClass('main__table-text');
                $('#movies-table_paginate').addClass('paginator');
                $('#movies-table_length label select').select2();
            }

            function dataTable(name = '', slug = '', sort = '') {
                var myTable = $('#movies-table').DataTable({
                    processing: true,
                    serverSide: true,
                    searching: false,
                    destroy: true,
                    ajax: {
                        "url": '{!! route('backend.movie.data') !!}',
                        "data": {
                            "name": name,
                            "slug": slug,
                            "sort": sort,
                        },
                    },
                    columns: [
                        {data: 'id', name: 'id', orderable: false, searchable: false},
                        {data: 'name', name: 'name', orderable: false, searchable: false, class: 'td-with'},
                        {data: 'slug', name: 'slug', orderable: false, searchable: false, class: 'td-with'},
                        {data: 'action', name: 'action', orderable: false, searchable: false},
                    ]
                });
                css();
            }

            dataTable();

            $('.filter__item-menu li').on('click', function (e) {
                e.preventDefault();
                var sort = $('.filter__item-btn input').val();
                var name = '';
                var slug = '';
                $.ajax({
                    url: '{!! route('backend.movie.data') !!}',
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
                var slug = '';
                $.ajax({
                    url: '{!! route('backend.movie.data') !!}',
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
                $('#movies-table').dataTable
            }

            $('#filter').on('change', function (e) {
                e.preventDefault();
                window.setTimeout(search, 200);
            })

            $('#movies-table').on('click', '.btn-nominations', function (e) {
                e.preventDefault();
                let status = $(this).attr('data_status');

                let id = $(this).attr('movie_id');
                $.ajax({
                    type: 'get',
                    url: '/admin/movie/' + id + '/nominations',
                    success: (result) => {
                        result = JSON.parse(result)
                        if (result.nominations == 1) {
                            $(this).attr('title','T???t ????? c???');
                            $(this).removeClass('main__table-btn--delete');
                            $(this).addClass('main__table-btn--edit');
                            $(this).remove('icon');
                            $(this).html('<icon class="icon ion-ios-radio-button-on">');
                        } else {
                            $(this).attr('title','B???t ????? c???');
                            $(this).addClass('main__table-btn--delete');
                            $(this).removeClass('main__table-btn--edit');
                            $(this).remove('icon');
                            $(this).html('<icon class="icon ion-ios-radio-button-off">');
                        }
                    }
                })

            })
        })
    </script>

@endsection

@section('main__title')
    <h2>Movie List</h2>

    {{-- <span class="main__title-stat">{{ count($movies) }} Total</span> --}}
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
                <li>Rating</li>
                <li>Nominations</li>
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
    <a href="{{ route('backend.movie.create') }}" class="main__title-link">add</a>
@endsection

@section('content')
    <div class="col-12">
        <div class="main__table-wrap">
            <table class="main__table" id="movies-table" style="width: 100%">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>NAME</th>
                    <th>SLUG</th>
                    <th>ACTIONS</th>
                </tr>
                </thead>
                {{-- <tbody>
                    @foreach($movies as $key => $movie)
                    <tr>
                        <td>
                            <div class="main__table-text">{{ $movie->id }}</div>
                        </td>
                        <td>
                            <div class="main__table-text">{{$movie->name}}</div>
                        </td>
                        <td>
                            @if($movie->genre == 1)
                            <div class="main__table-text main__table-text--green">
                            MOVIES
                            </div>
                            @else
                            <div class="main__table-text main__table-text--red">
                            TV SERIES
                            </div>
                            @endif
                        </td>
                        <td>
                            <div class="main__table-text">{{ $movie->slug }}</div>
                        </td>
                        <td>
                            <div class="main__table-btns">

                                <a href="{{ route('backend.movie.show' , $movie->id) }}" class="main__table-btn main__table-btn--view">
                                    <i class="icon ion-ios-eye"></i>
                                </a>
                                <a href="{{ route('backend.movie.edit' , $movie->id) }}" class="main__table-btn main__table-btn--edit">
                                    <i class="icon ion-ios-create"></i>
                                </a>
                                <form action="{{ route('backend.movie.destroy' , $movie->id) }}" method="POST">
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
