@extends('page-sidebar')
@section('content')
    <div class="portlet">
        <a class="btn btn-primary" href="/users/create">Create new</a>
        <div class="portlet-title">
            <div class="caption">All users</div>
        </div>

        @include('user.index-table')
    </div>
@endsection
