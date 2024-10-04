<!DOCTYPE html>
<html>
<head>
    <title>Логи</title>
</head>
<body>
    <h1>Логи</h1>
    <table>
        <thead>
            <tr>
                <th>Время</th>
                <th>Длительность</th>
                <th>IP</th>
                <th>URL</th>
                <th>Метод</th>
                <th>Входные данные</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
                <tr>
                    <td>{{ $log->time }}</td>
                    <td>{{ $log->duration }}</td>
                    <td>{{ $log->ip }}</td>
                    <td>{{ $log->url }}</td>
                    <td>{{ $log->method }}</td>
                    <td>{{ $log->input }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
