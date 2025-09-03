<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Elenco Lavoratori</title>
    <style>
        table {
            border-collapse: collapse;
            width: 80%;
            margin: 20px auto;
        }
        th, td {
            padding: 8px 12px;
            border: 1px solid #ccc;
        }
        th {
            background-color: #f2f2f2;
        }
        h2 {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <h2>Elenco Lavoratori</h2>

    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Cognome</th>
                <th>Codice Fiscale</th>
                <th>Telefono</th>
                <th>Email</th>
                <th>Data Assunzione</th>
                <th>Attivo</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lavoratori as $lavoratore)
                <tr>
                    <td>{{ $lavoratore->nome }}</td>
                    <td>{{ $lavoratore->cognome }}</td>
                    <td>{{ $lavoratore->codice_fiscale }}</td>
                    <td>{{ $lavoratore->telefono }}</td>
                    <td>{{ $lavoratore->email }}</td>
                    <td>{{ $lavoratore->data_assunzione }}</td>
                    <td>{{ $lavoratore->attivo ? '✔️' : '❌' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
