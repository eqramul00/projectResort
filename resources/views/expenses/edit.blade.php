<x-app-layout>
    <div class="card">
        <div class="card-header">
            <div>
                <span class="page-title-badge">UPDATE EXPENSE</span>
                <h2>Edit Expense</h2>
                <p class="muted">Update expense details.</p>
            </div>
            <a href="{{ route('expenses.index') }}" class="btn btn-secondary">Back</a>
        </div>

        <form method="POST" action="{{ route('expenses.update', $expense) }}">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group">
                    <label>Title</label>
                    <input name="title" value="{{ old('title', $expense->title) }}">
                    @error('title') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label>Amount</label>
                    <input type="number" step="0.01" name="amount" value="{{ old('amount', $expense->amount) }}">
                    @error('amount') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group full">
                    <label>Expense Date</label>
                    <input type="date" name="expense_date" value="{{ old('expense_date', $expense->expense_date) }}">
                    @error('expense_date') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group full">
                    <label>Note</label>
                    <textarea name="note" rows="4">{{ old('note', $expense->note) }}</textarea>
                    @error('note') <div class="field-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div style="margin-top: 20px;">
                <button type="submit" class="btn btn-primary">Update Expense</button>
            </div>
        </form>
    </div>
</x-app-layout>