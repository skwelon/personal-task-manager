@extends('ship::personal-task-manager')

@section('title', 'Create an account')

@section('content')
    <section class="card narrow">
        <p class="eyebrow">Get started</p>
        <h1>Create your account</h1>
        <p class="muted">Set up your space for the tasks ahead.</p>

        @if ($errors->any())
            <div class="alert alert-error" role="alert">
                @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
        @endif

        <form action="{{ route('register.store') }}" method="POST">
            @csrf
            <div class="field">
                <label for="name">Name</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" autocomplete="name" required autofocus>
                @error('name')<p class="field-error">{{ $message }}</p>@enderror
            </div>
            <div class="field">
                <label for="email">Email address</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required>
                @error('email')<p class="field-error">{{ $message }}</p>@enderror
            </div>
            <div class="field">
                <label for="password">Password</label>
                <input id="password" name="password" type="password" autocomplete="new-password" required>
                @error('password')<p class="field-error">{{ $message }}</p>@enderror
            </div>
            <div class="field">
                <label for="password_confirmation">Confirm password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required>
            </div>
            <button class="button" type="submit">Create account</button>
        </form>
        <p class="muted" style="margin:1.25rem 0 0">Already have an account? <a href="{{ route('login.form') }}">Log in</a></p>
    </section>
@endsection
