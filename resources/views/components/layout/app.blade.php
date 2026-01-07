<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{{isset($title) ? $title : "Page en cours"}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @vite(["resources/css/normalize.css", 'resources/css/app.css', 'resources/js/app.js', 'resources/css/form.css'])

    @stack('styles')
</head>
<body>


<x-header />

<div class="container">

<main>
    {{$slot}}
</main>

</div>

<x-footer />

@stack('scripts')

</body>
</html>
