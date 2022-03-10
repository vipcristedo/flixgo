
@extends('backend.layouts.master')

@section('title')
Dashboard
@endsection

@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="{{ asset('backend/css/datatable.css') }}">
@endsection

@section('js')
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
@endsection

@section('main__title')
<div class="col-12">
                    <div class="main__title">
                        <h2>Dashboard</h2>

                    </div>
                </div>
                <!-- end main title -->

                <!-- stats -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="stats">
                        <span>Total Movie</span>
                        <p>{{ $totalMovie }}</p>
                        <i class="icon ion-ios-film"></i>
                    </div>
                </div>
                <!-- end stats -->

                <!-- stats -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="stats">
                        <span>Total Playlist</span>
                        <p>{{ $totalPlaylist }}</p>
                        <i class="icon ion-ios-stats"></i>
                    </div>
                </div>
                <!-- end stats -->

                <!-- stats -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="stats">
                        <span>Total Tag</span>
                        <p>{{ $totalTag }}</p>
                        <i class="icon ion-ios-chatbubbles"></i>
                    </div>
                </div>
                <!-- end stats -->

                <!-- stats -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="stats">
                        <span>Total Type</span>
                        <p>{{ $totalType }}</p>
                        <i class="icon ion-ios-star-half"></i>
                    </div>
                </div>
                <!-- end stats -->

                <!-- dashbox -->
                <div class="col-12 col-xl-6">
                    <div class="dashbox">
                        <div class="dashbox__title">
                            <h3><i class="icon ion-ios-trophy"></i> Top movies</h3>

                            <div class="dashbox__wrap">
                                <a href="{{ route('backend.movie.index') }}" class="dashbox__more">View All</a>
                            </div>
                        </div>

                        <div class="dashbox__table-wrap">
                            <table class="main__table main__table--dash">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>NAME</th>
                                        <th>RATING</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topMovies as $topMovie)
                                    <tr>
                                        <td>
                                            <div class="main__table-text">{{ $topMovie['id'] }}</div>
                                        </td>
                                        <td>
                                            <div class="main__table-text">{{ $topMovie['name'] }}</div>
                                        </td>
                                        <td>
                                            <div class="main__table-text main__table-text--rate"><i class="icon ion-ios-star"></i>{{ $topMovie['rate'] }}</div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- end dashbox -->

                <!-- dashbox -->
                <div class="col-12 col-xl-6">
                    <div class="dashbox">
                        <div class="dashbox__title">
                            <h3><i class="icon ion-ios-contacts"></i> Latest admins</h3>

                            <div class="dashbox__wrap">
                                <a class="dashbox__more" href="{{ route('backend.admin.index') }}">View All</a>
                            </div>
                        </div>

                        <div class="dashbox__table-wrap">
                            <table class="main__table main__table--dash">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>NAME</th>
                                        <th>EMAIL</th>
                                        <th>STATUS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($admins as $admin)
                                    <tr>
                                        <td>
                                            <div class="main__table-text">{{ $admin['id'] }}</div>
                                        </td>
                                        <td>
                                            <div class="main__table-text">{{ $admin['name'] }}</div>
                                        </td>
                                        <td>
                                            <div class="main__table-text main__table-text--grey">{{ $admin['email'] }}</div>
                                        </td>
                                        <td>
                                            @if($admin['is_active'])
                                            <div class="main__table-text main__table-text--green">Active</div>
                                            @else
                                            <div class="main__table-text main__table-text--red">Hidden</div>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- end dashbox -->
@endsection