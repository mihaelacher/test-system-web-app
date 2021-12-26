@extends('content', ['title' => 'Test Execution'])
@section('sub-content')
    <div class="form-container">
        @if($showEvaluateBtn)
            <a class="btn btn-success" href="/testexecution/evaluate/{{ $testExecution->id }}">Evaluate</a>
        @endif
        <div class="row">
            <div class="form-group mt-3">
                <label class="label-text">START TIME</label>
                <p>{{ $testExecution->start_time }}</p>
            </div>
            <div class="form-group mt-3">
                <label class="label-text">END TIME</label>
                <p>{{ $testExecution->end_time }}</p>
            </div>
            <div class="form-group mt-3">
                <label class="label-text">RESULT POINTS</label>
                <p>{{ $testExecution->result_points }}</p>
            </div>
            @forelse($questions as $index => $question)
                <div class="form-group mt-3">
                    <label class="label-text">QUESTION {{ $index + 1 }}</label>
                    <p>{{ $question->text }}</p>
                </div>
                @if(in_array($question->question_type_id, \App\Models\Question\QuestionType::OPEN_QUESTIONS))
                    <div class="col-md-12">
                        <label class="col-md-3 control-label left">Answer:</label>
                        <div class="col-md-8">
                            <p class="form-control-plaintext">
                                {{ $question->response_text_short }}
                            </p>
                        </div>
                    </div>
                @else
            @foreach($question->answers as $answer)
                    @php
                        $testExecutionAnswers = explode(',', $question->answer_ids);
                    @endphp
                <div class="col-md-12">
                    <div class="form-group col-md-1 mt-3">
                        <input class="disabled" disabled type="checkbox"
                               @if(in_array($answer->id, $testExecutionAnswers)) checked @endif />
                    </div>
                    <div class="form-group col-md-7 mt-3">
                        <p @if($answer->is_correct ===1) class="text-success" @endif>{{ $answer->value }}</p>
                    </div>
                </div>
                @endforeach
                    @endif
            @empty
                <h5>NO ANSWERS</h5>
            @endforelse
    </div>
@endsection
