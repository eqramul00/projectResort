<x-app-layout>
    <div class="card">
        <div class="card-header">
            <div>
                <span class="page-title-badge">EXPENSE MANAGEMENT</span>
                <h2>Expenses</h2>
                <p class="muted">Track all resort operating expenses.</p>
            </div>
            <a href="{{ route('expenses.create') }}" class="btn btn-primary">+ Add Expense</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Note</th>
                    <th style="width:240px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expenses as $expense)
                    <tr>
                        <td>#{{ $expense->id }}</td>
                        <td>{{ $expense->title }}</td>
                        <td>৳ {{ number_format($expense->amount, 2) }}</td>
                        <td>{{ $expense->expense_date }}</td>
                        <td>{{ $expense->note ?: '-' }}</td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('expenses.show', $expense) }}" class="btn btn-secondary">View</a>
                                <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-warning">Edit</a>

                                <form method="POST" action="{{ route('expenses.destroy', $expense) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this expense?')">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="muted">No expenses found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>