<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Arial, sans-serif; background: #f4f6f9; color: #202938; }
        .container { max-width: 900px; margin: 40px auto; padding: 0 18px; }
        .card { background: white; padding: 24px; border-radius: 12px; margin-bottom: 20px; box-shadow: 0 3px 14px #0000000d; }
        h1 { margin-top: 0; } label { display: block; margin: 12px 0 6px; font-weight: bold; }
        input { width: 100%; padding: 11px; border: 1px solid #ccd2dc; border-radius: 7px; }
        button { margin-top: 16px; background: #2457c5; color: white; border: 0; padding: 11px 18px; border-radius: 7px; cursor: pointer; }
        table { width: 100%; border-collapse: collapse; } th, td { text-align: left; padding: 12px 8px; border-bottom: 1px solid #e8ebf0; }
        .success { background: #e7f7ed; padding: 12px; border-radius: 7px; margin-bottom: 15px; }
        .error { color: #b42318; font-size: 14px; }
        .muted { color: #667085; }
    </style>
</head>
<body>
<div class="container">
    <h1>Expense Tracker</h1>
    <p class="muted">Aplikasi sederhana untuk mencatat pengeluaran harian.</p>

    <section class="card">
        <h2>Tambah Pengeluaran</h2>
        @if (session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="error">
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif
        <form action="{{ route('expenses.store') }}" method="POST">
            @csrf
            <label for="description">Keterangan</label>
            <input id="description" name="description" value="{{ old('description') }}" placeholder="Contoh: Makan siang" required>

            <label for="amount">Jumlah (Rp)</label>
            <input id="amount" name="amount" type="number" min="1" step="0.01" value="{{ old('amount') }}" required>

            <label for="expense_date">Tanggal</label>
            <input id="expense_date" name="expense_date" type="date" value="{{ old('expense_date', date('Y-m-d')) }}" required>

            <button type="submit">Simpan Pengeluaran</button>
        </form>
    </section>

    <section class="card">
        <h2>Daftar Pengeluaran</h2>
        <table>
            <thead><tr><th>Keterangan</th><th>Jumlah</th><th>Tanggal</th></tr></thead>
            <tbody>
            @forelse ($expenses as $expense)
                <tr>
                    <td>{{ $expense->description }}</td>
                    <td>Rp {{ number_format((float) $expense->amount, 0, ',', '.') }}</td>
                    <td>{{ $expense->expense_date->format('d-m-Y') }}</td>
                </tr>
            @empty
                <tr><td colspan="3" class="muted">Belum ada pengeluaran.</td></tr>
            @endforelse
            </tbody>
        </table>
    </section>
    <p class="muted">API: GET /health · GET /api/expenses · POST /api/expenses</p>
</div>
</body>
</html>
