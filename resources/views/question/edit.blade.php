@extends('content', ['title' => 'Edit Question'])
@section('sub-content')
    @php
        $singleChoiceType =  \App\Models\Question\QuestionType::SINGLE_CHOICE;
        $multipleChoiceType = \App\Models\Question\QuestionType::MULTIPLE_CHOICE;
        $questionType = $question->question_type_id;
        $isClosed = $questionType === $singleChoiceType || $questionType === $multipleChoiceType;
    @endphp
    <input id="js-single-choice-type" type="hidden" value="{{ $singleChoiceType }}">
    <input id="js-multiple-choice-type" type="hidden" value="{{ $multipleChoiceType }}">
    <div class="form-container">
        <form id="questionForm" action="/questions/{{ $question->id }}/update" method="post" role="form">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <button type="submit" class="btn-success btn">SAVE</button>
            <div class="row">
                <div class="form-group mt-3">
                    <label class="label-text" for="text">TEXT</label>
                    <input type="text" name="text" class="form-control" required value="{{ $question->text }}">
                </div>
                <div class="form-group mt-3">
                    <label class="label-text" for="instruction">INSTRUCTION</label>
                    <textarea class="form-control" name="instruction" rows="3">{{ $question->instruction }}</textarea>
                </div>
                <div class="form-group mt-3">
                    <label class="label-text" for="points">POINTS</label>
                    <input type="number" class="form-control" name="points" required value="{{ $question->points }}">
                </div>
                <div class="form-group mt-3">
                    <label class="label-text" for="type">TYPE</label>
                    <select id="js-question-type" class="form-control" name="type">
                        @foreach($questionTypes as $type)
                            <option  @if($questionType === $type->id) selected @endif
                            value="{{ $type->id }}"> {{ $type->name }} </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mt-3 @if($questionType !== $multipleChoiceType) hidden @endif js-max-markable-answers">
                    <label class="label-text" for="max_markable_answers">MAX MARKABLE ANSWERS</label>
                    <input type="number" class="form-control" name="max_markable_answers"
                            value="{{ $question->max_markable_answers }}">
                </div>
            </div>
            <div class="row">
            @forelse($question->answers ?? [] as $answer)
                <div class="js-answer-container col-md-12">
                    <div class="form-group col-md-1 mt-5">
                        <input class="js-correct-answer" name="correct_answer[]"
                               type="{{ $questionType === $multipleChoiceType ? 'checkbox' : 'radio' }}"
                               @if($answer->is_correct) checked @endif>
                    </div>
                    <div class="form-group col-md-7 mt-3">
                        <label class="label-text" for="value">ANSWER TEXT</label>
                        <input type="text" class="form-control" name="value[]" value="{{ $answer->value }}">
                    </div>
                    <button class="mt-5 btn-danger js-remove-answer-container-btn" type="button">-</button>
                </div>
            @empty
                @include('question.blocks.answer-container', ['hide' => !$isClosed, 'answer' => null])
            @endforelse
            </div>
        </form>
        <button id="js-clone-answer-container-btn" class="btn btn-success @if(!$isClosed) hidden @endif">Add Answer</button>
    </div>
@endsection
