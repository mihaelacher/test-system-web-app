@extends('page-sidebar')
@section('content')
<div class="portlet">
    <a class="btn btn-primary" href="/questions/create">Create new</a>
    <div class="portlet-title">
        <div class="caption">All questions </div>
    </div>

    <div class="table-responsive">
        <table id="questionsIndexTable" class="table table-striped table-hover table-bordered">
            <thead>
             <tr>
                 <th>Title</th>
                 <th>Question</th>
                 <th>Points</th>
                 <th>Type</th>
             </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
@endsection
