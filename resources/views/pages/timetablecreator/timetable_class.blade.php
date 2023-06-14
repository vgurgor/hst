<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Haftalık Ders Programı</title>
    <style>
        body {
            background: rgb(204,204,204);
            font-family:'Arial';
            text-align: center;
        }
        page {
            background: white;
            display: block;
            margin: 0 auto;
            margin-bottom: 0.5cm;
            box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
        }
        page[size="A4"] {
            width: 21cm;
            height: 29.7cm;
        }
        page[size="A4"][layout="landscape"] {
            width: 29.7cm;
            height: 21cm;
        }
        page[size="A3"] {
            width: 29.7cm;
            height: 42cm;
        }
        page[size="A3"][layout="landscape"] {
            width: 42cm;
            height: 29.7cm;
        }
        page[size="A5"] {
            width: 14.8cm;
            height: 21cm;
        }
        page[size="A5"][layout="landscape"] {
            width: 21cm;
            height: 14.8cm;
        }
        @media print {
            body, page {
                margin: 0;
                box-shadow: none
            }
        }

        h1 {
            text-align: center;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #dddddd;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        .class-table {
            margin-top: 20px;
        }

        .class-table th, .class-table td {
            text-align: center;
        }

        .class-table th {
            width: 25%;
        }
    </style>
</head>
<body>
<page size="A4">
<div style="padding:30px">
    <h2 style="">{{$generalInfo["classroom_name"]}} <br>Haftalık Ders Programı</h2>
    <br>

    <table>
        <thead>
        <tr>
            <th>Kampüs: {{$generalInfo["campus_name"]}}</th>

            <th>Şube: {{$generalInfo["branch_name"]}}</th>
        </tr>
        <tr>
        </tr>
        </thead>
    </table>
    <table>
        <thead>
            <tr>
                <th> </th>
                @foreach ($lessonhours as $lessonhour)
                    <td><small>{{$lessonhour["start"]}} <br> {{$lessonhour["end"]}}</small></td>
                @endforeach
            </tr>
        </thead>
    <tbody>
        @foreach ($lessonsTable as $day=> $lessonA)
            <tr>
                <td><small>{{$day}}</small></td>
                @foreach ($lessonA as $lessonB)
                    <td><small>{{$lessonB}}</small></td>
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>

    <br>
    <br>
    <br>
    <br>
    <br>
    <h4>Ders Listesi</h4>
    <table class="class-table">
        <thead>
        <tr>
            <th><small>Ders Adı</small></th>
            <th><small>Branş</small></th>
            <th><small>Öğretmen</small></th>
            <th><small>Haftalık Ağırlık</small></th>
        </tr>
        </thead>
        <tbody>
        @foreach ($lessons as $lesson)
            <tr>
                <td><small>{{$lesson["name"]}}</small></td>
                <td><small>{{$lesson["major_name"]}}</small></td>
                <td><small>{{$lesson["teacher"]}}</small></td>
                <td><small>{{$lesson["weekly_frequency"]}}</small></td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
</page>
</body>
</html>
