@extends('page-sidebar', ['title' => 'Evaluate Test'])
@section('content')
    <form id="executionForm" action="/testexecution/{{ $testExecutionId }}/evaluate" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <button type="submit" class="btn-success btn">Submit</button>
        <div class="container">
            @foreach($questions ?? [] as $index => $question)
                <div>
                    <div class="col-md-12">
                        <label class="col-md-3 control-label">
                            {{ $index + 1 . ': ' . $question->text }}
                        </label>
                    </div>
                </div>
                <div class="col-md-12">
                    <label class="col-md-3 control-label left">Answer:</label>
                    <div class="col-md-8">
                        <p class="form-control-plaintext">
                            {{ $question->response_text_short }}
                        </p>
                    </div>
                </div>
                <div class="col-md-12">
                    <label class="col-md-3 control-label left">Points:</label>
                    <div class="col-md-8">
                        <input max="{{ $question->points }}" type="number" name="points[]">
                    </div>
                </div>
            @endforeach
        </div>
    </form>
@endsection
