@extends('ship::personal-task-manager')

@section('title', 'Personal Task Manager')

@section('content')
    <section class="card hero">
        <p class="eyebrow">Make room for your next step</p>
        <h1>Small steps. Clear progress.</h1>
        <p class="lead">Keep your priorities in one calm, focused place. Add tasks, track what is done, and move forward at your own pace.</p>
        @guest('web')
            <div class="button-row">
                <a class="button" href="{{ route('login.form') }}">Log in</a>
                <a class="button secondary" href="{{ route('register') }}">Create an account</a>
            </div>
        @endguest
        @auth('web')
            <div class="button-row"><a class="button" href="{{ route('tasks') }}">Go to my tasks</a></div>
        @endauth
    </section>
@endsection
