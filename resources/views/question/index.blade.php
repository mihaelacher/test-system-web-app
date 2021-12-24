@extends('page-sidebar', ['title' => 'All questions'])
@section('content')
        <a class="btn btn-success" style="margin-bottom: 20px" href="/questions/create">Create new</a>

    @include('question.index-table')

@endsection
