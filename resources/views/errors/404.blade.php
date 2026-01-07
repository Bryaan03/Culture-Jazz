<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>404</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @vite(["resources/css/normalize.css", 'resources/css/404.css', 'resources/js/app.js'])
</head>
<body>
<main>
    <div id="error">Erreur 404, il semblerait que d'autres partitions méritent le détour...</div>
    <div id="image"><img src="{{Vite::asset('resources/images/illustration_banniere.png')}}" alt="dessin"</div>
</main>

</body>
</html>
