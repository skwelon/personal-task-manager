@extends('ship::personal-task-manager')
@section('title', 'Edit Task')
@section('content')
    <section class="card narrow">
    <p class="eyebrow">Keep your list current</p>
    <h1>Edit task</h1>
    <p class="muted">Update the details or mark it complete.</p>
    <form id="edit-task-form">
        <div class="field">
            <label for="title">Task title</label>
            <input type="text" id="title" maxlength="150" required aria-describedby="title-error">
            <p class="field-error" id="title-error" hidden></p>
        </div>
        <div class="field">
            <label for="description">Description</label>
            <textarea id="description" rows="5" aria-describedby="description-error"></textarea>
            <p class="field-error" id="description-error" hidden></p>
        </div>
        <div class="field"><label><input type="checkbox" id="is-completed"> Completed</label></div>
        <p id="form-error" class="error" role="alert" hidden></p>
        <p id="form-success" class="success" role="status" hidden></p>
        <p id="loading" class="muted">Loading task...</p>
        <button class="button" type="submit" id="save-button" disabled>Save changes</button>
    </form>
    <p style="margin:1.25rem 0 0"><a href="{{ url('/tasks') }}">Back to tasks</a></p>
    </section>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const taskId = @json($taskId);
    const form = document.getElementById('edit-task-form');
    const title = document.getElementById('title');
    const description = document.getElementById('description');
    const completed = document.getElementById('is-completed');
    const loading = document.getElementById('loading');
    const errorBox = document.getElementById('form-error');
    const successBox = document.getElementById('form-success');
    const saveButton = document.getElementById('save-button');
    const titleError = document.getElementById('title-error');
    const descriptionError = document.getElementById('description-error');
    const apiUrl = `/tasks/${encodeURIComponent(taskId)}`;
    async function request(method = 'GET', body = null) {
        return window.TaskManager.request(apiUrl, { method, ...(body !== null ? { body: JSON.stringify(body) } : {}) });
    }
    function unwrap(payload) { return payload.data?.data || payload.data || payload; }
    async function loadTask() {
        try {
            const task = unwrap(await request());
            title.value = task.title || '';
            description.value = task.description || '';
            completed.checked = Boolean(task.is_completed);
            saveButton.disabled = false;
        } catch (e) {
            errorBox.textContent = e.status === 401 ? 'Your API session has expired. Please log in again at /login.' : e.message;
            errorBox.hidden = false;
        } finally { loading.hidden = true; }
    }
    form.addEventListener('submit', async event => {
        event.preventDefault();
        errorBox.hidden = true; successBox.hidden = true; titleError.hidden = true; descriptionError.hidden = true;
        saveButton.disabled = true; loading.hidden = false; loading.textContent = 'Saving...';
        try {
            const updatedTask = unwrap(await request('PATCH', { title: title.value.trim(), description: description.value.trim() || null, is_completed: completed.checked }));
            title.value = updatedTask.title || '';
            description.value = updatedTask.description || '';
            completed.checked = Boolean(updatedTask.is_completed);
            successBox.textContent = 'Changes saved.';
            successBox.hidden = false;
        } catch (e) {
            if (e.status === 422) {
                const errors = e.payload.errors || {};
                if (errors.title) { titleError.textContent = [].concat(errors.title).join(' '); titleError.hidden = false; }
                if (errors.description) { descriptionError.textContent = [].concat(errors.description).join(' '); descriptionError.hidden = false; }
            }
            errorBox.textContent = e.message || 'Could not save the task.';
            errorBox.hidden = false;
        } finally { saveButton.disabled = false; loading.hidden = true; loading.textContent = 'Loading task...'; }
    });
    loadTask();
});
</script>
@endpush
