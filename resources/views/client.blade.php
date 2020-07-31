<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clients</title>
</head>
<body>
    <form action="{{ url('/oauth/clients') }}" method="POST">
        <p>
            <input type="text" name="name">
        </p>
        <p>
            <input type="text" name="redirect">
        </p>
        <p>
            <input type="submit" name="send" value="Enviar">
        </p>
        {{ csrf_field() }}
    </form>
</body>
</html>