<!DOCTYPE html>
<html>

<head>
    <title></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        table {
            font-family: 'Helvetica';
            /* Ganti dengan font yang didukung oleh dompdf */
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>

<body>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama Acara</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Selesai</th>
                <th>Total Tamu</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($list_rents as $rent)
            <tr>
                <td>{{$rent['no'] }}</td>
                <td>{{$rent['event_name'] }}</td>
                <td>{{ indoDate($rent['date_start']) }}<br> {{$rent['time_start']}} WIB</td>
                <td>{{ indoDate($rent['date_end']) }}<br> {{ $rent['time_end'] }} WIB</td>
                <td>{{ $rent['total_guest'] }}</td>
                <td>{{ $rent['status'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>