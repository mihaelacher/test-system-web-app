@extends('content', ['title' => 'Create Test'])
@section('sub-content')
    <div class="form-container">
        <form id="testForm" action="/tests/store" method="post" role="form" novalidate>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <button type="submit" class="btnSubmitForm btn-success btn">SUBMIT</button>
            <div class="row">
                <div class="form-group mt-3">
                    <label class="label-text" for="name">NAME</label>
                    <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
                </div>
                <div class="form-group mt-3">
                    <label class="label-text" for="intro_text">INTRO TEXT</label>
                    <input type="text" name="intro_text" class="form-control" value="{{ old('intro_text') }}">
                </div>
                <div class="form-group mt-3">
                    <label class="label-text" for="max_duration">MAX DURATION</label>
                    <input type="number" class="form-control" name="max_duration" required value="{{ old('max_duration') }}">
                </div>
                <div class="form-group mt-3">
                    <label class="label-text" for="is_visible_for_admins">IS PUBLIC</label>
                    <select class="form-control" name="is_visible_for_admins">
                        <option value="0" @if(old('is_visible_for_admins') == 0) selected @endif>NO</option>
                        <option value="1" @if(old('is_visible_for_admins') == 1) selected @endif>YES</option>
                    </select>
                </div>
                @include('question.blocks.index-table')
            </div>
        </form>
    </div>
@endsection
