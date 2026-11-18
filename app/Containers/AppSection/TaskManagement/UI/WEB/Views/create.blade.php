@extends('ship::personal-task-manager')
@section('title', 'Create Task')
@section('content')
    <section class="card narrow">
    <p class="eyebrow">A new step forward</p>
    <h1>Create a task</h1>
    <p class="muted">Capture what you want to get done.</p>
    <form id="create-task-form">
        <div class="field">
            <label for="title">Task title</label>
            <input type="text" id="title" name="title" maxlength="150" required aria-describedby="title-error" placeholder="e.g. Review project plan">
            <p class="field-error" id="title-error" hidden></p>
        </div>
        <div class="field">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="5" aria-describedby="description-error" placeholder="Add a few details (optional)"></textarea>
            <p class="field-error" id="description-error" hidden></p>
        </div>
        <p class="error" id="form-error" role="alert" hidden></p>
        <button class="button" type="submit" id="submit-button">Create task</button>
        <span id="loading" class="muted" hidden>Creating...</span>
    </form>
    <p style="margin:1.25rem 0 0"><a href="{{ url('/tasks') }}">Back to tasks</a></p>
    </section>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('create-task-form');
    const button = document.getElementById('submit-button');
    const loading = document.getElementById('loading');
    const formError = document.getElementById('form-error');
    const titleError = document.getElementById('title-error');
    const descriptionError = document.getElementById('description-error');
    const clearErrors = () => [formError, titleError, descriptionError].forEach(el => { el.hidden = true; el.textContent = ''; });

    form.addEventListener('submit', async event => {
        event.preventDefault();
        clearErrors();
        button.disabled = true;
        loading.hidden = false;
        try {
            await window.TaskManager.request('/tasks', {
                method: 'POST',
                body: JSON.stringify({
                    title: document.getElementById('title').value.trim(),
                    description: document.getElementById('description').value.trim() || null
                })
            });
            window.location.assign('/tasks');
        } catch (error) {
            if (error.status === 422) {
                const errors = error.payload?.errors || {};
                if (errors.title) { titleError.textContent = [].concat(errors.title).join(' '); titleError.hidden = false; }
                if (errors.description) { descriptionError.textContent = [].concat(errors.description).join(' '); descriptionError.hidden = false; }
                const messages = Object.values(errors).flat();
                formError.textContent = messages.length ? messages.join(' ') : error.message;
                formError.hidden = false;
                return;
            }
            formError.textContent = error.message || 'Could not create the task.';
            formError.hidden = false;
        } finally {
            button.disabled = false;
            loading.hidden = true;
        }
    });
});
</script>
@endpush
