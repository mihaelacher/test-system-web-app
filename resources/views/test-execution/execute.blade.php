@extends('content', ['title' => 'Execute Test'])
@section('sub-content')
    <form id="executionForm" action="/testexecution/{{ $testExecutionId }}/submit" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" value="{{ $timeRemainingInSec }}" id="timeRemainingInSec">
        <div class="form-container">
            <div>
                <button id="finishExecution" type="submit" class="btn-success btn">Submit</button>
                <div class="label-text big fw-bold">
                    TIME REMAINING:
                    <span class="big fw-bold text-danger" id="testCountDown">
                        {{ gmdate('H:i:s', $timeRemainingInSec) }}
                    </span>
                </div>
            </div>
            @foreach($questions ?? [] as $index => $question)
                @php
                    $questionTypeId = $question->question_type_id;
                @endphp
                <div class="row">
                    <div class="form-group mt-5">
                        <div class="label-text fw-bold">QUESTION {{ $index + 1 }}.
                            <span class="text-black"> {{ $question->text }}</span>
                        </div>
                    </div>
                    <div class="form-group mt-1">
                        <div class="label-text fw-bold">INSTRUCTION:
                            <p class="text-black">
                                {{ $question->instruction }}
                                @if(in_array($questionTypeId ,\App\Models\Question\QuestionType::CLOSED_QUESTIONS))
                                    <span
                                        class="text-danger">(max answers: {{ $question->max_markable_answers }})</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    @switch($questionTypeId)
                        @case(\App\Models\Question\QuestionType::TEXT_SHORT)
                        <textarea class="js-question-answers" data-question_id="{{ $question->id }}" rows="2"
                                  cols="150" name="open_answer[]" data-question_type_id="{{ $questionTypeId }}">
                            {{ $question->response_text_short }}
                        </textarea>
                        @break
                        @case(\App\Models\Question\QuestionType::TEXT_LONG)
                        <textarea class="js-question-answers" data-question_id="{{ $question->id }}" rows="6"
                                  cols="150" name="open_answer[]" data-question_type_id="{{ $questionTypeId }}">
                            {{ $question->response_text_long }}
                        </textarea>
                        @break
                        @case(\App\Models\Question\QuestionType::NUMERIC)
                        <input type="number" class="js-question-answers" data-question_id="{{ $question->id }}"
                               name="open_answer[]" data-question_type_id="{{ $questionTypeId }}"
                               value="{{ $question->response_numeric }}"/>
                        @break
                        @default
                        @php
                            $givenAnswerIds = explode(',', $question->closed_question_answers);
                        @endphp
                        @foreach($question->answers ?? [] as $answer)
                            <div class="col-md-12">
                                <div class="form-group col-md-1 mt-3">
                                    <input name="correct_answer" class="js-question-answers"
                                           @if ($questionTypeId === \App\Models\Question\QuestionType::MULTIPLE_CHOICE)
                                           type="checkbox"
                                           @else
                                           type="radio"
                                           @endif
                                           @if(in_array($answer->id, $givenAnswerIds)) checked @endif
                                           data-question_id="{{ $question->id }}" data-answer_id="{{ $answer->id }}"/>
                                </div>
                                <div class="form-group col-md-7 mt-3">
                                    <p>{{ $answer->value }}</p>
                                </div>
                            </div>
                        @endforeach
                        @break
                    @endswitch
                </div>
            @endforeach
        </div>
    </form>
@endsection
