@extends('content', ['title' => 'Execute Test'])
@section('sub-content')
    <form id="executionForm" action="/testexecution/{{ $testExecutionId }}/submit" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" value="{{ $timeRemainingInSec }}" id="timeRemainingInSec">
        <input type="hidden" value="{{ $testExecutionId }}" id="testExecutionId">
        <div class="form-container">
            <div>
                <button id="finishExecution" type="submit" class="btn-success btn">Submit</button>
                <div class="label-text big fw-bold">
                    TIME REMAINING: <span class="big fw-bold text-danger" id="testCountDown">{{ $remainingTime }}</span>
                </div>
            </div>
            @foreach($questions ?? [] as $index => $question)
                @php
                    $questionTypeId = $question->question_type_id;
                 //   dd(\Illuminate\Support\Facades\Request::flash())
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
                                  cols="150" name="open_answer[]">{{ old('open_answer.' . $index) }}</textarea>
                        @break
                        @case(\App\Models\Question\QuestionType::TEXT_LONG)
                        <textarea class="js-question-answers" data-question_id="{{ $question->id }}" rows="6"
                                  cols="150" name="open_answer[]">{{ old('open_answer.' . $index) }}</textarea>
                        @break
                        @case(\App\Models\Question\QuestionType::NUMERIC)
                        <input type="number" class="js-question-answers" data-question_id="{{ $question->id }}"
                               name="open_answer[]" value="{{ old('open_answer.' . $index) }}"/>
                        @break
                        @default
                        @php
                            $answers = explode(',', $question->answers);
                        @endphp
                        @foreach($answers ?? [] as $answer)
                            @php
                                $answerIdValueArr = explode('-', $answer);
                                $id = $answerIdValueArr[0];
                                $value = $answerIdValueArr[1];
                            @endphp
                            <div class="col-md-12">
                                <div class="form-group col-md-1 mt-3">
                                    <input name="correct_answer" class="js-question-answers"
                                           @if ($questionTypeId === \App\Models\Question\QuestionType::MULTIPLE_CHOICE)
                                           type="checkbox"
                                           @else
                                           type="radio"
                                           @endif
                                           data-question_id="{{ $question->id }}" data-answer_id="{{ $id }}"/>
                                </div>
                                <div class="form-group col-md-7 mt-3">
                                    <p>{{ $value }}</p>
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
