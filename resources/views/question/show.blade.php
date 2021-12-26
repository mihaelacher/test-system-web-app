@extends('content', ['title' => 'Question'])
@section('sub-content')
    @php
        $isMultipleChoiceQuestion = $question->question_type_id === \App\Models\Question\QuestionType::MULTIPLE_CHOICE;
        $isSingleChoiceQuestion = $question->question_type_id === \App\Models\Question\QuestionType::SINGLE_CHOICE;
    @endphp
    <div class="form-container">
        @if($canEdit)
            <a class="btn-success btn" href="/questions/{{ $question->id }}/edit">EDIT</a>
            <a class="btn btn-danger" href="/questions/{{ $question->id }}/delete" data-method="delete"
               data-token="{{csrf_token()}}" data-confirm="Are you sure, you want to delete this question?">DELETE</a>
        @endif
        <div class="row">
            <div class="form-group mt-3">
                <label class="label-text">TEXT</label>
                <p>{{ $question->text }}</p>
            </div>
            <div class="form-group mt-3">
                <label class="label-text">INSTRUCTION</label>
                <p>{{ $question->instruction }}</p>
            </div>
            <div class="form-group mt-3">
                <label class="label-text">POINTS</label>
                <p>{{ $question->points }}</p>
            </div>
            <div class="form-group mt-3">
                <label class="label-text">TYPE</label>
                <p>{{ $question->type->name }}</p>
            </div>
            @if($isMultipleChoiceQuestion)
                <div class="form-group mt-3">
                    <label class="label-text">MAX MARKABLE ANSWERS</label>
                    <p>{{ $question->max_markable_answers }}</p>
                </div>
            @endif
            @if($isMultipleChoiceQuestion || $isSingleChoiceQuestion)
                @forelse($question->answers ?? [] as $answer)
                    <div class="col-md-12">
                        <div class="form-group col-md-1 mt-3">
                            <input class="disabled" disabled type="checkbox"
                                   @if($answer->is_correct) checked @endif />
                        </div>
                        <div class="form-group col-md-7 mt-3">
                            <p>{{ $answer->value }}</p>
                        </div>
                    </div>
                @empty
                    <h5>NO ANSWERS</h5>
                @endforelse
            @endif
        </div>
    </div>
@endsection
