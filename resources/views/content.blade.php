@extends('page-sidebar')
@section('content')
    <main id="main">
        <section id="about" class="about">
            <div class="container">
                <div class="section-title">
                    <h2>{{ $title }}</h2>
                </div>
                @include('blocks.session-msg')
                @include('blocks.validation-error')
                @yield('sub-content')
            </div>
        </section>
    </main>
@endsection
