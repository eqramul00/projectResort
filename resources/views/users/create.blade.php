<x-app-layout>
    <div class="card">
        <div class="card-header">
            <div>
                <span class="page-title-badge">NEW STAFF ACCOUNT</span>
                <h2>Create User</h2>
                <p class="muted">Add a new admin, manager, or reception user.</p>
            </div>
            <a href="{{ route('users.roles') }}" class="btn btn-secondary">Back</a>
        </div>

        @if($errors->any())
            <div class="alert alert-error">
                <ul style="margin:0; padding-left:18px;">
                    @foreach($errors->all() as $error)
                        <li style="margin-bottom:6px;">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('users.store') }}">
            @csrf

            <div class="form-grid">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter full name">
                </div>

                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter email">
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Enter password">
                </div>

                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="password_confirmation" placeholder="Confirm password">
                </div>

                <div class="form-group full">
                    <label>Role</label>
                    <select name="role">
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>admin</option>
                        <option value="manager" {{ old('role') === 'manager' ? 'selected' : '' }}>manager</option>
                        <option value="reception" {{ old('role') === 'reception' ? 'selected' : '' }}>reception</option>
                    </select>
                </div>
            </div>

            <div style="margin-top: 20px;">
                <button type="submit" class="btn btn-primary">Create User</button>
            </div>
        </form>
    </div>
</x-app-layout>