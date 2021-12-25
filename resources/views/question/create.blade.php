@extends('content', ['title' => 'Create Question'])
@section('sub-content')
    @php
        $multipleChoiceType = \App\Models\Question\QuestionType::MULTIPLE_CHOICE;
    @endphp
    <input id="js-single-choice-type" type="hidden" value="{{ \App\Models\Question\QuestionType::SINGLE_CHOICE }}">
    <input id="js-multiple-choice-type" type="hidden" value="{{ $multipleChoiceType }}">
    <div class="form-container">
        <form id="questionForm" action="/questions/store" method="post" role="form" novalidate>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <button type="submit" class="btnSubmitForm btn-success btn">SUBMIT</button>
            <div class="row">
                <div class="form-group mt-3">
                    <label class="label-text" for="text">TEXT</label>
                    <input type="text" name="text" class="form-control" required>
                </div>
                <div class="form-group mt-3">
                    <label class="label-text" for="instruction">INSTRUCTION</label>
                    <textarea class="form-control" name="instruction" rows="3"></textarea>
                </div>
                <div class="form-group mt-3">
                    <label class="label-text" for="points">POINTS</label>
                    <input type="number" class="form-control" name="points" >
                </div>
                <div class="form-group mt-3">
                    <label class="label-text" for="type">TYPE</label>
                    <select id="js-question-type" class="form-control" name="type">
                        @foreach($questionTypes as $type)
                            <option value="{{ $type->id }}"> {{ $type->name }} </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mt-3 hidden js-max-markable-answers">
                    <label class="label-text" for="max_markable_answers">MAX MARKABLE ANSWERS</label>
                    <input type="number" class="form-control" name="max_markable_answers">
                </div>
            </div>
            @include('question.blocks.answer-container', ['answer' => null, 'questionType' => null, 'hide' => false])
        </form>
        <button id="js-clone-answer-container-btn" class="btn btn-success">Add Answer</button>
    </div>
@endsection
