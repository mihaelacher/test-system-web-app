@extends('page-sidebar')
@section('content')
<div class="portlet">
    <a class="btn btn-primary" href="/questions/create">Create new</a>
    <div class="portlet-title">
        <div class="caption">All questions </div>
    </div>

   @include('question.index-table')
</div>
@endsection
