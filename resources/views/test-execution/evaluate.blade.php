@extends('content', ['title' => 'Evaluate Test'])
@section('sub-content')
    <form id="executionForm" action="/testexecution/{{ $testExecutionId }}/evaluate" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-container">
            <button type="submit" class="btn-success btn">Submit</button>
            <div class="row">
            @foreach($questions ?? [] as $index => $question)
                    <div class="form-group mt-5">
                        <div class="label-text fw-bold">QUESTION {{ $index + 1 }}.
                            <span class="text-black"> {{ $question->text }}</span>
                        </div>
                    </div>
                    <div class="form-group mt-3">
                        <label class="label-text">ANSWER</label>
                        <p>{{ $question->response_text_short ?? $question->response_text_long ?? $question->response_numeric }}</p>
                    </div>
                <div class="form-group mt-3">
                    <label class="label-text">POINTS:</label>
                        <input max="{{ $question->points }}" type="number" name="points[]">
                </div>
            @endforeach
            </div>
        </div>
    </form>
@endsection
