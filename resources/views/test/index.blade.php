@extends('page-sidebar', ['title' => 'All Tests'])
@section('content')
    <div class="portlet">
        @if($currentUser->is_admin)
            <a class="btn btn-success" href="/tests/create">Create new</a>
        @endif

        <div class="table-responsive">
            <table id="testsIndexTable" class="table table-striped table-hover table-bordered">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Intro Text</th>
                    <th>Max Duration</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
@endsection
