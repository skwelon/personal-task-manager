<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Personal Task Manager')</title>
    <style>
        :root { color-scheme: light; --ink: #172033; --muted: #68748a; --line: #e3e8f0; --surface: #fff; --canvas: #f4f6fb; --brand: #4658c8; --brand-dark: #3545ad; --green: #14805e; --red: #b42335; }
        * { box-sizing: border-box; }
        body { min-height: 100vh; margin: 0; background: var(--canvas); color: var(--ink); font: 16px/1.55 Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
        a { color: var(--brand); text-decoration: none; }
        a:hover { color: var(--brand-dark); }
        button, input, textarea, select { font: inherit; }
        .site-header { background: var(--surface); border-bottom: 1px solid var(--line); }
        .nav-wrap { width: min(1100px, calc(100% - 2rem)); min-height: 72px; margin: auto; display: flex; align-items: center; justify-content: space-between; gap: 1rem; }
        .brand { color: var(--ink); font-size: 1.05rem; font-weight: 750; letter-spacing: -.02em; }
        .brand-mark { display: inline-grid; width: 2rem; height: 2rem; margin-right: .55rem; place-items: center; border-radius: .7rem; background: #e9ecff; color: var(--brand); }
        .nav-links { display: flex; align-items: center; gap: .5rem; }
        .nav-links a, .nav-links button { padding: .55rem .8rem; border: 0; border-radius: .6rem; background: transparent; color: var(--muted); font-weight: 650; cursor: pointer; }
        .nav-links a:hover, .nav-links button:hover { background: #f0f2ff; color: var(--brand); }
        .nav-links form { margin: 0; }
        .page-shell { width: min(1000px, calc(100% - 2rem)); margin: 3rem auto 5rem; }
        .card { padding: clamp(1.25rem, 4vw, 2.25rem); border: 1px solid var(--line); border-radius: 1.15rem; background: var(--surface); box-shadow: 0 14px 40px #26345a0b; }
        .narrow { width: min(100%, 520px); margin: 0 auto; }
        .hero { padding: clamp(2rem, 7vw, 4.5rem); background: radial-gradient(ellipse at 85% 10%, #e6e9ff 0, transparent 40%), var(--surface); }
        h1, h2, h3, p { margin-top: 0; }
        h1 { margin-bottom: .55rem; font-size: clamp(1.8rem, 5vw, 2.55rem); line-height: 1.15; letter-spacing: -.04em; }
        h2 { font-size: 1.25rem; letter-spacing: -.02em; }
        .lead { max-width: 42rem; margin-bottom: 1.6rem; color: var(--muted); font-size: 1.1rem; }
        .eyebrow { margin-bottom: .75rem; color: var(--brand); font-size: .76rem; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; }
        .button, button.button { display: inline-flex; min-height: 44px; align-items: center; justify-content: center; gap: .45rem; padding: .65rem 1rem; border: 1px solid transparent; border-radius: .7rem; background: var(--brand); color: #fff; font-weight: 700; cursor: pointer; transition: background .15s, transform .15s; }
        .button:hover, button.button:hover { transform: translateY(-1px); background: var(--brand-dark); color: #fff; }
        .button.secondary, button.button.secondary { border-color: var(--line); background: #fff; color: var(--ink); }
        .button.secondary:hover, button.button.secondary:hover { background: #f7f8fc; }
        button:disabled { opacity: .6; cursor: wait; transform: none; }
        .button-row, .actions { display: flex; flex-wrap: wrap; align-items: center; gap: .65rem; }
        .field { margin: 1rem 0; }
        .field label { display: block; margin-bottom: .4rem; font-size: .9rem; font-weight: 700; }
        .field input, .field textarea, select { width: 100%; min-height: 45px; padding: .7rem .8rem; border: 1px solid #cfd6e2; border-radius: .65rem; background: #fff; color: var(--ink); outline: none; }
        .field input[type="checkbox"] { width: auto; min-height: 0; accent-color: var(--brand); }
        .field textarea { min-height: 130px; resize: vertical; }
        .field input:focus, .field textarea:focus, select:focus { border-color: var(--brand); box-shadow: 0 0 0 3px #4658c81c; }
        .muted { color: var(--muted); }
        .error, .alert-error { color: var(--red); }
        .success, .alert-success { color: var(--green); }
        .alert { margin: 1rem 0; padding: .8rem 1rem; border: 1px solid currentColor; border-radius: .7rem; background: #fff; }
        .field-error { margin: .35rem 0 0; color: var(--red); font-size: .86rem; }
        .task { margin: .8rem 0; padding: 1.15rem; border: 1px solid var(--line); border-radius: .85rem; background: #fff; }
        .task h3 { margin: 0 0 .3rem; font-size: 1.05rem; }
        .task p { margin-bottom: .8rem; }
        .task.completed { border-left: 4px solid #31a47e; }
        .task.completed h3 { color: var(--muted); text-decoration: line-through; }
        .status-pill { display: inline-flex; padding: .2rem .6rem; border-radius: 99px; background: #fff2d7; color: #8b5e00; font-size: .78rem; font-weight: 750; }
        .task.completed .status-pill { background: #e5f6ef; color: #126b50; }
        .section-heading { display: flex; flex-wrap: wrap; align-items: end; justify-content: space-between; gap: 1rem; margin-bottom: 1.5rem; }
        .filter-wrap { width: min(100%, 220px); }
        .site-footer { padding: 1rem; color: var(--muted); text-align: center; font-size: .85rem; }
        [hidden] { display: none !important; }
        @media (max-width: 560px) { .nav-wrap { min-height: 64px; } .brand { font-size: .95rem; } .nav-links { gap: 0; } .nav-links a, .nav-links button { padding: .5rem; font-size: .9rem; } .page-shell { margin-top: 1.25rem; } .section-heading { align-items: stretch; } .filter-wrap { width: 100%; } }
    </style>
    @stack('styles')
</head>
<body>
    <header class="site-header">
        <div class="nav-wrap">
            <a class="brand" href="{{ url('/') }}"><span class="brand-mark" aria-hidden="true">✓</span>Personal Task Manager</a>
            <nav class="nav-links" aria-label="Main navigation">
                @guest('web')
                    <a href="{{ route('login.form') }}">Login</a>
                    <a href="{{ route('register') }}">Register</a>
                @endguest
                @auth('web')
                    <a href="{{ route('tasks') }}">Tasks</a>
                    <form action="{{ route('logout') }}" method="POST">@csrf<button type="submit">Log out</button></form>
                @endauth
            </nav>
        </div>
    </header>
    <main class="page-shell">
        @if (session('status'))
            <p class="alert alert-success" role="status">{{ session('status') }}</p>
        @endif
        @yield('content')
    </main>
    <footer class="site-footer">A calmer way to keep track of what matters.</footer>
    <script>
        window.TaskManager = {
            accessToken: {{ \Illuminate\Support\Js::from(session('api_access_token')) }},
            apiBaseUrl: {{ \Illuminate\Support\Js::from(rtrim(config('apiato.api.url'), '/') . '/v1') }}
        };
        window.TaskManager.request = async (path, options = {}) => {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
            const url = `${window.TaskManager.apiBaseUrl}${path}`;
            const endSession = async () => {
                try {
                    await fetch('/logout', { method: 'POST', headers: { Accept: 'application/json', 'X-CSRF-TOKEN': csrfToken } });
                } catch (_) {}
                window.location.assign('/login?expired=1');
            };
            const send = () => {
                const headers = { Accept: 'application/json', Authorization: `Bearer ${window.TaskManager.accessToken}`, ...(options.headers || {}) };
                if (options.body !== undefined && options.body !== null) headers['Content-Type'] = 'application/json';
                return fetch(url, { ...options, headers });
            };
            const readPayload = async response => response.status === 204 ? {} : response.json().catch(() => ({}));
            let response;
            let payload;
            try {
                response = await send();
                payload = await readPayload(response);
            } catch (error) {
                throw new Error(`Could not reach the API at ${window.TaskManager.apiBaseUrl}. Check that the API host is available and allows requests from this site.`);
            }
            if (response.status === 401) {
                let refreshPayload;
                try {
                    const refreshResponse = await fetch('/session/refresh', {
                        method: 'POST',
                        headers: { Accept: 'application/json', 'X-CSRF-TOKEN': csrfToken }
                    });
                    refreshPayload = await readPayload(refreshResponse);
                    if (!refreshResponse.ok || !refreshPayload.access_token) throw new Error('Session refresh failed.');
                } catch (error) {
                    await endSession();
                    throw new Error('Your session expired. Please log in again.');
                }
                window.TaskManager.accessToken = refreshPayload.access_token;
                try {
                    response = await send();
                    payload = await readPayload(response);
                } catch (error) {
                    throw new Error(`Could not reach the API at ${window.TaskManager.apiBaseUrl}. Check that the API host is available and allows requests from this site.`);
                }
                if (response.status === 401) {
                    await endSession();
                    throw new Error('Your session expired. Please log in again.');
                }
            }
            if (!response.ok) {
                const validationMessages = Object.values(payload.errors || {}).flat();
                const message = response.status === 404
                    ? 'Task not found or you do not have access to it.'
                    : response.status === 422
                        ? (validationMessages.join(' ') || payload.message || 'Please check the submitted information.')
                        : (payload.message || `API error (${response.status}).`);
                const error = new Error(message);
                error.status = response.status;
                error.payload = payload;
                throw error;
            }
            return payload;
        };
    </script>
    @stack('scripts')
</body>
</html>
