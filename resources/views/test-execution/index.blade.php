@extends('content', ['title' => 'All Test Executions'])
@section('sub-content')
    <div class="portlet">
        <div class="table-responsive">
            <table id="testExecutionsIndexTable" class="table table-striped table-hover table-bordered">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Start time</th>
                    <th>End time</th>
                    <th>Result points</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
@endsection
