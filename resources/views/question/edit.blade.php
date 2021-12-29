@extends('content', ['title' => 'Edit Question'])
@section('sub-content')
    @php
        $singleChoiceType =  \App\Models\Question\QuestionType::SINGLE_CHOICE;
        $multipleChoiceType = \App\Models\Question\QuestionType::MULTIPLE_CHOICE;
        $questionTypeId = old('type') ?? $question->question_type_id;
        $isClosed = (int) $questionTypeId === $singleChoiceType || (int) $questionTypeId === $multipleChoiceType;
    @endphp
    <input id="js-single-choice-type" type="hidden" value="{{ $singleChoiceType }}">
    <input id="js-multiple-choice-type" type="hidden" value="{{ $multipleChoiceType }}">
    <div class="form-container">
        <form id="questionForm" action="/questions/{{ $question->id }}/update" method="post" role="form">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <button type="submit" class="btn-success btn">SAVE</button>
            <div class="row">
                <div class="form-group mt-3 ">
                    <label class="label-text" for="text">TEXT</label>
                    <input type="text" name="text" class="form-control" required
                           value="{{ old('text') ?? $question->text }}">
                </div>
                <div class="form-group mt-3">
                    <label class="label-text" for="instruction">INSTRUCTION</label>
                    <textarea class="form-control" name="instruction" rows="3">
                        {{ old('instruction') ?? $question->instruction }}
                    </textarea>
                </div>
                <div class="form-group mt-3">
                    <label class="label-text" for="points">POINTS</label>
                    <input type="number" class="form-control" name="points" required
                           value="{{ old('points') ?? $question->points }}">
                </div>
                <div class="form-group mt-3">
                    <label class="label-text" for="type">TYPE</label>
                    <select id="js-question-type" class="form-control" name="type">
                        @foreach($questionTypes as $type)
                            <option  @if($questionTypeId === $type->id) selected @endif
                            value="{{ $type->id }}"> {{ $type->name }} </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mt-3 @if($questionTypeId !== $multipleChoiceType) hidden @endif js-max-markable-answers">
                    <label class="label-text" for="max_markable_answers">MAX MARKABLE ANSWERS</label>
                    <input type="number" class="form-control" name="max_markable_answers"
                            value="{{ old('max_markable_answers') ?? $question->max_markable_answers }}">
                </div>
            </div>
            <div class="row">
                @if(!empty(old()))
                    @forelse(old('value') as $index => $answer)
                        @include('question.blocks.answer-container',
                                    ['hide' => !$isClosed,
                                    'isChecked' => old('is_correct.' . $index),
                                    'value' => $answer])
                    @empty
                        @include('question.blocks.answer-container',['hide' => !$isClosed])
                    @endforelse
                @else
                    @forelse($question->answers ?? [] as $index => $answer)
                        @include('question.blocks.answer-container',
                                ['hide' => !$isClosed,
                                'isChecked' => $answer->is_correct,
                                'value' => $answer->value])
                    @empty
                        @include('question.blocks.answer-container',['hide' => !$isClosed])
                    @endforelse
                @endif
            </div>
        </form>
        <button id="js-clone-answer-container-btn" class="btn btn-success @if(!$isClosed) hidden @endif">Add Answer</button>
    </div>
@endsection
