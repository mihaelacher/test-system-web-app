@extends('page-sidebar', ['title' => 'All questions'])
@section('content')
        <a class="btn btn-success" href="/questions/create">Create new</a>

    @include('question.index-table')

@endsection
