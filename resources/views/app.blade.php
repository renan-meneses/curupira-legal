<!doctype html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ config('app.name', 'Curupira Legal') }}</title>
        @viteReactRefresh
        @vite(['src/main.tsx', 'src/styles.css'])
    </head>
    <body>
        <div id="root"></div>
    </body>
</html>
