<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feature Management</title>
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --success: #22c55e;
            --danger: #ef4444;
            --border: #e2e8f0;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: var(--bg);
            color: var(--text-main);
            margin: 0;
            padding: 2rem;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        h1 {
            font-size: 1.875rem;
            font-weight: 700;
            margin: 0;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid transparent;
        }
        .btn-primary {
            background-color: var(--primary);
            color: white;
        }
        .btn-primary:hover {
            background-color: var(--primary-hover);
        }
        .btn-outline {
            border-color: var(--border);
            background: white;
            color: var(--text-main);
        }
        .btn-outline:hover {
            background-color: var(--bg);
        }
        .btn-danger {
            color: var(--danger);
        }
        .alert {
            padding: 1rem;
            border-radius: 0.5rem;
            background-color: #f0fdf4;
            color: #166534;
            margin-bottom: 1.5rem;
            border: 1px solid #bbf7d0;
        }
        .card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th {
            text-align: left;
            padding: 0.75rem 1.5rem;
            background: #f1f5f9;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border);
        }
        .table td {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border);
        }
        .badge {
            display: inline-flex;
            padding: 0.25rem 0.625rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-danger { background: #fee2e2; color: #991b1b; }
        .actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        form { margin: 0; }
        .empty {
            padding: 3rem;
            text-align: center;
            color: var(--text-muted);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Feature Management</h1>
            <a href="{{ route('features.create') }}" class="btn btn-primary">Create New Feature</a>
        </div>

        @if(session('success'))
            <div class="alert">{{ session('success') }}</div>
        @endif

        <div class="card">
            <table class="table">
                <thead>
                    <tr>
                        <th>Feature Key</th>
                        <th>Status</th>
                        <th>Rules</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($features as $feature)
                        <tr>
                            <td style="font-weight: 600;">{{ $feature->key }}</td>
                            <td>
                                <span class="badge {{ $feature->enabled ? 'badge-success' : 'badge-danger' }}">
                                    {{ $feature->enabled ? 'Enabled' : 'Disabled' }}
                                </span>
                            </td>
                            <td style="color: var(--text-muted); font-size: 0.875rem;">
                                {{ count($feature->rules) }} registered
                            </td>
                            <td class="actions">
                                <form action="{{ route('features.toggle', $feature) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-outline" style="font-size: 0.75rem;">Toggle</button>
                                </form>
                                <a href="{{ route('features.edit', $feature) }}" class="btn btn-outline" style="font-size: 0.75rem; color: var(--primary);">Edit</a>
                                <form action="{{ route('features.destroy', $feature) }}" method="POST" onsubmit="return confirm('Delete this feature?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="background: none; border: none; font-size: 0.75rem; font-weight: 600; cursor: pointer;">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="empty">No features found. Start by creating one.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
