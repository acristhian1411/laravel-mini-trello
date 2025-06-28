<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{{ $title }}</title>
    </head>
    <body>
        <h1>Reporte de {{ $board->name }} en fecha {{ $date }}</h1>
        <p>
            {{ $board->description }}
        </p>
    </body>
</html>