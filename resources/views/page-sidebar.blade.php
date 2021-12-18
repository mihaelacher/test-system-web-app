<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Test System Web App</title>
    <!-- Core theme CSS (includes Bootstrap) TODO: not working must see WHY-->
    <link href="http://test-system-web-app/css/app.css" rel="stylesheet" />
</head>
<body>
<div class="d-flex" id="wrapper">
    <!-- Sidebar-->
    <div class="border-end bg-white" id="sidebar-wrapper">
        <div class="sidebar-heading border-bottom bg-light">Test System Web App</div>
        <div class="list-group list-group-flush">
            <a class="list-group-item list-group-item-action list-group-item-light p-3" href="/questions/index">Questions</a>
            <a class="list-group-item list-group-item-action list-group-item-light p-3" href="/tests/index">Tests</a>
            <a class="list-group-item list-group-item-action list-group-item-light p-3" href="#!">Users</a>
            <a class="list-group-item list-group-item-action list-group-item-light p-3" href="#!">Profile</a>
        </div>
    </div>
    <!-- Page content wrapper-->
    <div id="page-content-wrapper">
        <!-- Top navigation-->
        <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
            <div class="container-fluid">
              {{--  <button class="btn btn-primary" id="sidebarToggle">Toggle Menu</button>--}}
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                        <li class="nav-item active"><a class="nav-link" href="/">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="/auth/logout">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Page content-->
        @yield('content')
    </div>
</div>
@include('blocks.jsresources')
</body>
</html>
