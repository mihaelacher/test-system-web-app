<!-- Core theme JS-->
<script src="http://test-system-web-app/js/app.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.3/datatables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.3/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js"></script>

@if (\Illuminate\Support\Facades\Request::is('questions/*') || \Illuminate\Support\Facades\Request::is('tests/*'))
    <script src="http://test-system-web-app/js/questions.js"></script>
@endif

@if (\Illuminate\Support\Facades\Request::is('tests/*'))
    <script src="http://test-system-web-app/js/tests.js"></script>
@endif

