@extends('content', ['title' => 'All Users'])
@section('sub-content')
        <a class="btn btn-success" href="/users/create">Create new</a>

        @include('user.blocks.index-table', ['showOperations' => true])
@endsection
