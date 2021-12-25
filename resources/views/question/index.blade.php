@extends('content', ['title' => 'All questions'])
@section('sub-content')
        <a class="btn btn-success" style="margin-bottom: 20px" href="/questions/create">Create new</a>

    @include('question.blocks.index-table')

@endsection
