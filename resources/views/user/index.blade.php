@extends('page-sidebar', ['title' => 'All Users'])
@section('content')
    <div class="portlet">
        <a class="btn btn-success" href="/users/create">Create new</a>

        @include('user.index-table')
    </div>
@endsection
