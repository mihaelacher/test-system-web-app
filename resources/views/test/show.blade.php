@extends('page-sidebar')
@section('content')
    <div class="container">
        <input id="js-test-id" type="hidden" value="{{ $test->id }}">
        @if($currentUser->is_admin)
            <a class="btn btn-primary" href="/tests/edit/{{ $test->id }}">Edit</a>
            <a class="btn btn-secondary" href="/tests/inviteUsers/{{ $test->id }}">Invite users to participate</a>
        @elseif($showStartBtn)
            <a class="btn btn-primary" href="/testexecution/start/{{ $test->id }}">Start</a>
        @endif
        <h1>Test:</h1>
        <div>
            <label class="col-md-3 control-label left">Name:</label>
            <div class="col-md-8">
                <p class="form-control-plaintext">
                    {{ $test->name }}
                </p>
            </div>
        </div>
        <div>
            <label class="col-md-3 control-label left">Intro text:</label>
            <div class="col-md-8">
                <p class="form-control-plaintext">
                    {{ $test->intro_text }}
                </p>
            </div>
        </div>
        <div>
            <label class="col-md-3 control-label left">Max duration:</label>
            <div class="col-md-8">
                <p class="form-control-plaintext">
                    {{ $test->max_duration }}
                </p>
            </div>
        </div>
        @if($hasQuestions && $currentUser->is_admin)
            <div class="col-md-12 mt-5">
                @include('question.index-table', ['tableId' => 'testQuestionsIndexTable'])
            </div>
        @endif
    </div>
@endsection
