<div class="mb-8 grid grid-cols-2 gap-4">
    <div class="flex">
        <div class="mr-4">
            <x-laravel-logo></x-laravel-logo>
        </div>
        <h1 class="text-2xl">
            <span class="text-red-500">Laravel</span>
            +
            <span class="text-blue-500">REST API</span>
            +
            <span class="text-orange-500">JWT-auth</span>
        </h1>
    </div>
    <div class="text-right">
        {{ $slot }}
    </div>
</div>
