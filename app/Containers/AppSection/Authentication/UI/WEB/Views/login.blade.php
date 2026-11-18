@extends('ship::personal-task-manager')

@section('title', 'Log in')

@section('content')
    <section class="card narrow">
        <p class="eyebrow">Welcome back</p>
        <h1>Log in to your account</h1>
        <p class="muted">Pick up where you left off.</p>

        @if (request()->boolean('expired'))
            <p class="alert alert-error" role="alert">Your session expired. Please log in again.</p>
        @endif
        @if ($errors->any())
            <div class="alert alert-error" role="alert">
                @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="field">
                <label for="email">Email address</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="username" required autofocus>
                @error('email')<p class="field-error">{{ $message }}</p>@enderror
            </div>
            <div class="field">
                <label for="password">Password</label>
                <input id="password" name="password" type="password" autocomplete="current-password" required>
                @error('password')<p class="field-error">{{ $message }}</p>@enderror
            </div>
            <div class="field"><label><input type="checkbox" name="remember" value="1" @checked(old('remember'))> Keep me signed in</label></div>
            <button class="button" type="submit">Log in</button>
        </form>
        <p class="muted" style="margin:1.25rem 0 0">New here? <a href="{{ route('register') }}">Create an account</a></p>
    </section>
@endsection
