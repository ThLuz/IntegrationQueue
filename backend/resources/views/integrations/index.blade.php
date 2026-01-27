<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Fila de Integrações</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
        table { background: white; border-collapse: collapse; width: 100%; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: center; }
        th { background: #333; color: white; }
        .PENDING { color: orange; }
        .PROCESSING { color: blue; }
        .SUCCESS { color: green; }
        .ERROR { color: red; }
        form { margin-bottom: 20px; }
        input, select { padding: 5px; margin-right: 10px; }
        button { padding: 5px 10px; }
    </style>
</head>
<body>

<h1>Fila de Integração de Clientes</h1>

<!-- Formulário de filtros -->
<form method="GET" action="{{ url('/integrations') }}">
    <input type="text" name="external_id" placeholder="External ID" value="{{ request('external_id') }}">
    <select name="status">
        <option value="">TODOS</option>
        <option value="PENDING" {{ request('status') == 'PENDING' ? 'selected' : '' }}>PENDING</option>
        <option value="PROCESSING" {{ request('status') == 'PROCESSING' ? 'selected' : '' }}>PROCESSING</option>
        <option value="SUCCESS" {{ request('status') == 'SUCCESS' ? 'selected' : '' }}>SUCCESS</option>
        <option value="ERROR" {{ request('status') == 'ERROR' ? 'selected' : '' }}>ERROR</option>
    </select>
    <button type="submit">Filtrar</button>
    <a href="{{ url('/integrations') }}"><button type="button">Limpar</button></a>
</form>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>External ID</th>
            <th>Status</th>
            <th>Attempts</th>
            <th>Processed At</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($jobs as $job)
            <tr>
                <td>{{ $job->id }}</td>
                <td>{{ $job->external_id }}</td>
                <td class="{{ $job->status }}">{{ $job->status }}</td>
                <td>{{ $job->attempts }}</td>
                <td>{{ $job->processed_at?->format('d/m/Y H:i:s') ?? '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5">Nenhum job encontrado.</td>
            </tr>
        @endforelse
    </tbody>
</table>

</body>
</html>
