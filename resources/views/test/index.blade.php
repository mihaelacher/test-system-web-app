@extends('content', ['title' => 'All Tests'])
@section('sub-content')
    <div class="portlet">
        @can('create', \App\Models\Test\Test::class)
            <a class="btn btn-success" href="/tests/create">Create new</a>
        @endcan

        <div class="table-responsive">
            <table id="testsIndexTable" class="table table-striped table-hover table-bordered">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Intro Text</th>
                    <th>Max Duration</th>
                    <th>Operations</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    @include('test.blocks.test-modal')
@endsection
