@extends('content', ['title' => 'Edit Test'])
@section('sub-content')
    <div class="form-container">
        <form id="testForm" action="/tests/{{ $test->id }}/update" method="post" role="form" novalidate>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input id="js-is-edit" type="hidden" value="1">
            <button type="submit" class="btnSubmitForm btn-success btn">SUBMIT</button>
            <div class="row">
                <div class="form-group mt-3">
                    <label class="label-text" for="name">NAME</label>
                    <input type="text" name="name" class="form-control" required
                           value="{{ old('name') ?? $test->name }}">
                </div>
                <div class="form-group mt-3">
                    <label class="label-text" for="intro_text">INTRO TEXT</label>
                    <input type="text" name="intro_text" class="form-control"
                           value="{{ old('intro_text') ?? $test->intro_text }}">
                </div>
                <div class="form-group mt-3">
                    <label class="label-text" for="max_duration">MAX DURATION</label>
                    <input type="number" class="form-control" name="max_duration" required
                           value="{{ old('max_duration') ?? $test->max_duration }}">
                </div>
                @php
                    $isVisibleForAdmins = old('is_public') ?? $test->is_public;
                @endphp
                <div class=" form-group mt-3">
                    <label class="label-text" for="is_public">IS PUBLIC</label>
                    <select class="form-control" name="is_public">
                        <option value="0" @if(!$isVisibleForAdmins) selected @endif>NO</option>
                        <option value="1" @if($isVisibleForAdmins) selected @endif>YES</option>
                    </select>
                </div>
                @include('question.blocks.index-table', ['showOperations' => false])
            </div>
        </form>
    </div>
@endsection
