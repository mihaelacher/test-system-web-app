@extends('page-sidebar')
@section('content')
    <form id="testForm" action="/tests/update/{{ $test->id }}" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input id="js-test-id" type="hidden" value="{{ $test->id }}">
        <input id="js-is-edit" type="hidden" value="1">
        <button type="submit" class="btn-primary btn">Submit</button>
        <div>
            <div class="form-group">
                <label class="col-md-2 control-label">Name:</label>
                <div class="col-md-9">
                    <input type="text" class="form-control placeholder-no-fix" placeholder="Name"
                           autocomplete="off" name="name" value="{{ $test->name }}"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Intro text:</label>
                <div class="col-md-9">
                    <input type="text" class="form-control placeholder-no-fix" placeholder="Intro text"
                           autocomplete="off" name="intro_text" value="{{ $test->intro_text }}"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Max duration:</label>
                <div class="col-md-9">
                    <input class="form-control placeholder-no-fix" placeholder="In minutes"
                           autocomplete="off" name="max_duration" value="{{ $test->max_duration }}"/>
                </div>
            </div>
            @php
            $isVisibleForAdmins = $test->is_visible_for_admins;
            @endphp
            <div class="form-group">
                <label class="col-md-2 control-label">Is visible for other admins:</label>
                <div class="col-md-9">
                    <select class="form-control" name="is_visible_for_admins">
                        <option value="0" @if(!$isVisibleForAdmins) selected @endif> No </option>
                        <option value="1" @if($isVisibleForAdmins) selected @endif> Yes </option>
                    </select>
                </div>
            </div>
        </div>
        @if($hasQuestions)
            <div id="questionsTable" class="col-md-12 mt-5">
                @include('question.index-table', ['tableId' => 'testQuestionsIndexTable'])
            </div>
        @endif
    </form>
    @if(!$hasQuestions)
        <button id="questionsLoadBtn" class="col-md-3 btn-secondary btn">Select questions from Database</button>
    @endif
@endsection
