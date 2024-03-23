<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Report Tamu Meeting</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <table class="table">
        <thead>
            <tr>
                <th scope="col" width="25">No</th>
                <th scope="col">Nama</th>
                <th scope="col" width="60">Jabatan</th>
                <th scope="col" widht="60">Unit Kerja</th>
                <th scope="col" width="100">Waktu Absen</th>
                {{-- <th scope="col" width="100">Tanda Tangan</th> --}}
            </tr>
        </thead>
        <tbody>
            @foreach ($guests as $guest)
                <tr>
                    <td>{{ $guest['no'] }}</td>
                    <td>{{ $guest['name'] }}</td>
                    <td>{{ $guest['position'] }}</td>
                    <td>{{ $guest['work_unit'] }}</td>
                    <td>{{ Carbon\Carbon::parse($guest['created_at'])->format('Y-m-d H:i:s') }}</td>
                    {{-- <td>
                        <img src="{{ $guest['signature'] }}" width="100" alt="">
                    </td> --}}
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
