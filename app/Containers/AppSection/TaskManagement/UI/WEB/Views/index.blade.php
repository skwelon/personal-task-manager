@extends('ship::personal-task-manager')
@section('title', 'My Tasks')
@section('content')
    <div class="section-heading">
        <div><p class="eyebrow">Your personal workspace</p><h1>My tasks</h1><p class="muted">A clear view of what is done and what comes next.</p></div>
        <a class="button" href="{{ url('/tasks/create') }}">+ Create task</a>
    </div>
    <div class="filter-wrap">
        <label for="task-filter">Filter:</label>
        <select id="task-filter">
            <option value="all">All tasks</option>
            <option value="0">Incomplete</option>
            <option value="1">Completed</option>
        </select>
    </div>
    <p id="tasks-loading" class="muted" hidden>Loading tasks...</p>
    <p id="tasks-error" class="error" role="alert" hidden></p>
    <p id="tasks-empty" hidden>No tasks yet.</p>
    <div id="tasks-list" aria-live="polite"></div>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const list = document.getElementById('tasks-list');
    const filter = document.getElementById('task-filter');
    const loading = document.getElementById('tasks-loading');
    const errorBox = document.getElementById('tasks-error');
    const empty = document.getElementById('tasks-empty');

    function extractTasks(payload) {
        if (Array.isArray(payload.data)) return payload.data;
        if (Array.isArray(payload.data?.data)) return payload.data.data;
        if (Array.isArray(payload.tasks)) return payload.tasks;
        return [];
    }
    function element(tag, text, className) {
        const node = document.createElement(tag);
        if (text !== undefined) node.textContent = text;
        if (className) node.className = className;
        return node;
    }
    async function api(url, options = {}) {
        return window.TaskManager.request(url, options);
    }
    function render(tasks) {
        list.replaceChildren();
        empty.hidden = tasks.length !== 0;
        tasks.forEach(task => {
            const card = element('article', undefined, `task${task.is_completed ? ' completed' : ''}`);
            const heading = element('h3', task.title || 'Untitled task');
            const description = element('p', task.description || 'No description', 'muted');
            const status = element('span', task.is_completed ? 'Completed' : 'Incomplete', 'status-pill');
            const actions = element('div', undefined, 'actions');
            const toggle = element('button', task.is_completed ? 'Reopen task' : 'Mark complete', 'button secondary');
            toggle.type = 'button';
            toggle.addEventListener('click', async () => {
                toggle.disabled = true;
                toggle.textContent = 'Saving…';
                try { await api(`/tasks/${encodeURIComponent(task.id)}`, { method: 'PATCH', body: JSON.stringify({ is_completed: !task.is_completed }) }); await loadTasks(); }
                catch (e) { showError(e.message); toggle.disabled = false; }
            });
            const edit = element('a', 'Edit', 'button secondary');
            edit.href = `/tasks/${encodeURIComponent(task.id)}/edit`;
            const remove = element('button', 'Delete', 'button secondary');
            remove.type = 'button';
            remove.addEventListener('click', async () => {
                if (!window.confirm('Are you sure you want to delete this task?')) return;
                remove.disabled = true;
                remove.textContent = 'Deleting…';
                try { await api(`/tasks/${encodeURIComponent(task.id)}`, { method: 'DELETE' }); card.remove(); await loadTasks(); }
                catch (e) { showError(e.message); remove.disabled = false; remove.textContent = 'Delete'; }
            });
            actions.append(toggle, edit, remove);
            card.append(heading, description, status, actions);
            list.append(card);
        });
    }
    function showError(message) { errorBox.textContent = message; errorBox.hidden = false; }
    async function loadTasks() {
        errorBox.hidden = true; loading.hidden = false; empty.hidden = true;
        try {
            const value = filter.value;
            const query = value === 'all' ? '' : `?is_completed=${encodeURIComponent(value)}`;
            const payload = await api(`/tasks${query}`);
            render(extractTasks(payload));
        } catch (e) { showError(e.message); }
        finally { loading.hidden = true; }
    }
    filter.addEventListener('change', loadTasks);
    loadTasks();
});
</script>
@endpush
