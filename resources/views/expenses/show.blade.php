<x-app-layout>
    <div class="card">
        <div class="card-header">
            <div>
                <span class="page-title-badge">EXPENSE DETAILS</span>
                <h2>{{ $expense->title }}</h2>
                <p class="muted">Detailed expense record.</p>
            </div>
            <div class="actions">
                <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-warning">Edit</a>
                <a href="{{ route('expenses.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </div>

        <div class="detail-list">
            <div class="detail-item">
                <h4>Title</h4>
                <p>{{ $expense->title }}</p>
            </div>

            <div class="detail-item">
                <h4>Amount</h4>
                <p>৳ {{ number_format($expense->amount, 2) }}</p>
            </div>

            <div class="detail-item">
                <h4>Expense Date</h4>
                <p>{{ $expense->expense_date }}</p>
            </div>

            <div class="detail-item">
                <h4>Note</h4>
                <p>{{ $expense->note ?: '-' }}</p>
            </div>
        </div>
    </div>
</x-app-layout>