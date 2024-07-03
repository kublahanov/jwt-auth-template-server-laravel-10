<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ env('APP_NAME') }}</title>
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="/favicon.ico"/>
    <link rel="icon" type="image/png" href="/outline_api_black_24dp.png"/>
</head>
<body class="antialiased">
<div
    class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 selection:bg-red-500 selection:text-white">
    <div class="max-w-5xl mx-auto p-6 lg:p-8">
        {{ $slot }}
    </div>
</div>
</body>
</html>
