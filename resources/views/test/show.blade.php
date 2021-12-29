@extends('content', ['title' => 'Test'])
@section('sub-content')
    @php
        $testId = $test->id;
    @endphp
    <div class="form-container">
        <input id="js-test-id" type="hidden" value="{{ $testId }}">
        @can('update', $test)
            <a class="btn btn-success" href="/tests/{{ $testId }}/edit">Edit</a>
            <a class="btn btn-danger" href="/tests/{{ $testId }}/delete" data-method="post"
               data-token="{{csrf_token()}}" data-confirm="Are you sure, you want to delete this test?">DELETE</a>
        @endcan
        <a class="btn btn-secondary" href="/tests/{{ $testId }}/inviteUsers">Invite users to participate</a>
        <div class="row">
            <div class="form-group mt-3">
                <label class="label-text" for="name">NAME</label>
                <p>{{ $test->name }}</p>
            </div>
            <div class="form-group mt-3">
                <label class="label-text" for="name">INTRO TEXT</label>
                <p>{{ $test->intro_text }}</p>
            </div>
            <div class="form-group mt-3">
                <label class="label-text" for="name">MAX DURATION</label>
                <p>{{ $test->max_duration }}</p>
            </div>
            @include('question.blocks.index-table', ['showOperations' => false])
        </div>
    </div>
@endsection
