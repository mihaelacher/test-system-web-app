<!-- Core theme JS-->
<script src="http://test-system-web-app/js/app.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.3/datatables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.3/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment-with-locales.min.js" integrity="sha512-LGXaggshOkD/at6PFNcp2V2unf9LzFq6LE+sChH7ceMTDP0g2kn6Vxwgg7wkPP7AAtX+lmPqPdxB47A0Nz0cMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.css" integrity="sha512-mQ8Fj7epKOfW0M7CwuuxdPtzpmtIB5rI4rl76MSd3mm5dCYBKjzPk7EU/2buhPMs0KmC6YOPR/MQlQwpkdNcpQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" integrity="sha512-WWc9iSr5tHo+AliwUnAQN1RfGK9AnpiOFbmboA0A0VJeooe69YR2rLgHw13KxF1bOSLmke+SNnLWxmZd8RTESQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

@if (\Illuminate\Support\Facades\Request::is('questions/*') || \Illuminate\Support\Facades\Request::is('tests/*'))
    <script src="http://test-system-web-app/js/questions.js"></script>
@endif

@if (\Illuminate\Support\Facades\Request::is('tests/*'))
    <script src="http://test-system-web-app/js/tests.js"></script>
@endif

@if (\Illuminate\Support\Facades\Request::is('users/*') || \Illuminate\Support\Facades\Request::is('tests/inviteUsers/*') )
    <script src="http://test-system-web-app/js/users.js"></script>
@endif

@if (\Illuminate\Support\Facades\Request::is('testexecution/*'))
    <script src="http://test-system-web-app/js/testexecution.js"></script>
@endif

