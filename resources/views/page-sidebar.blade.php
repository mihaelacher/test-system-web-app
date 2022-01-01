<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Test System Web App</title>
    @include('blocks.cssresources')
</head>
<body>
    <!-- ======= Header ======= -->
    <header id="header" class="d-flex align-items-center">
        <div class="container d-flex align-items-center">

            <h1 class="logo me-auto"><a href="/">Home</a></h1>

            <nav id="navbar" class="navbar">
                <ul>
                    @can('viewAny', \App\Models\Question\Question::class)
                        <li><a class="nav-link scrollto" href="/questions/index">QUESTIONS</a></li>
                    @endcan

                    @can('viewAny', \App\Models\Authorization\User::class)
                        <li><a class="nav-link scrollto" href="/users/index">USERS</a></li>
                    @endcan

                    <li><a class="nav-link scrollto " href="/tests/index">TESTS</a></li>
                    <li><a class="nav-link scrollto" href="/testexecution/index">EXECUTED TESTS</a></li>
                    {{-- TODO <li><a class="nav-link scrollto" href="#contact">Profile</a></li>--}}
                    <li><a class="getstarted scrollto" href="/auth/logout">LOGOUT</a></li>
                </ul>
                <i class="bi bi-list mobile-nav-toggle"></i>
            </nav><!-- .navbar -->

        </div>
    </header>
    <!-- End Header -->
    @yield('content')

    @include('blocks.jsresources')
</body>
</html>
