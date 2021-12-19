@php
$tableId = $tableId ?? 'questionsIndexTable';
@endphp
<div class="table-responsive">
    <table id="{{ $tableId }}" class="table table-striped table-hover table-bordered">
        <thead>
        <tr>
            <th>Title</th>
            <th>Question</th>
            <th>Points</th>
            <th>Type</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
