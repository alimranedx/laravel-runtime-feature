<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($feature) ? 'Edit' : 'Create' }} Feature</title>
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --input-bg: #ffffff;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: var(--bg);
            color: var(--text-main);
            margin: 0;
            padding: 2rem;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
        }
        .header {
            margin-bottom: 2rem;
        }
        h1 {
            font-size: 1.875rem;
            font-weight: 700;
            margin: 0;
        }
        .card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            padding: 2rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--text-main);
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 0.625rem;
            border: 1px solid var(--border);
            border-radius: 0.375rem;
            font-size: 0.875rem;
            box-sizing: border-box;
            background: var(--input-bg);
        }
        input:focus, textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.625rem 1.25rem;
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
        .btn-link {
            background: none;
            color: var(--text-muted);
            border: none;
        }
        .btn-link:hover {
            color: var(--text-main);
        }
        .footer-actions {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 1rem;
            margin-top: 2rem;
        }
        .error {
            color: #ef4444;
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }
        .help-text {
            color: var(--text-muted);
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ isset($feature) ? 'Edit' : 'Create' }} Feature</h1>
        </div>

        <div class="card">
            <form action="{{ isset($feature) ? route('features.update', $feature) : route('features.store') }}" method="POST">
                @csrf
                @if(isset($feature)) @method('PUT') @endif

                <div class="form-group">
                    <label for="key">Feature Key</label>
                    <input type="text" name="key" id="key" value="{{ old('key', $feature->key ?? '') }}" placeholder="e.g. beta_access" required>
                    @error('key') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group checkbox-group">
                    <input type="hidden" name="enabled" value="0">
                    <input type="checkbox" name="enabled" id="enabled" value="1" {{ old('enabled', $feature->enabled ?? false) ? 'checked' : '' }}>
                    <label for="enabled" style="margin-bottom: 0;">Enabled</label>
                </div>

                <div class="form-group">
                    <label for="value">Value (JSON)</label>
                    <textarea name="value" id="value" rows="5" placeholder='{"discount": 20}'>{{ old('value', (isset($feature) && !is_null($feature->value)) ? json_encode($feature->value, JSON_PRETTY_PRINT) : '') }}</textarea>
                    <div class="help-text">Optional JSON payload for this feature.</div>
                    @error('value') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="footer-actions">
                    <a href="{{ route('features.index') }}" class="btn btn-link">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        {{ isset($feature) ? 'Update' : 'Create' }} Feature
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
