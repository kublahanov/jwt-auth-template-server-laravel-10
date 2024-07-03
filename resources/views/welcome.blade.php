<x-layouts.index>
    <x-laravel-top-menu>
    </x-laravel-top-menu>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
        <x-laravel-card-link href="/api">
            <x-slot:icon>api</x-slot:icon>
            <x-slot:title>REST API list</x-slot:title>
            <x-slot:text>
                List of all API routes.
            </x-slot:text>
        </x-laravel-card-link>

        <x-laravel-card-link href="/example">
            <x-slot:icon>code_blocks</x-slot:icon>
            <x-slot:title>HTTP example page</x-slot:title>
            <x-slot:text>
                Result of executing console command
                <code class="bg-gray-200 px-1">php artisan x-test:http</code>
                - downloaded page from http://example.com/.
            </x-slot:text>
        </x-laravel-card-link>

        <x-laravel-card-link href="/mailable">
            <x-slot:icon>mail</x-slot:icon>
            <x-slot:title>Mail example page</x-slot:title>
            <x-slot:text>
                Example of mailable test template ('mail.test-mail').
            </x-slot:text>
        </x-laravel-card-link>

        <x-laravel-card-link href="/mailable">
            <x-slot:icon>mail</x-slot:icon>
            <x-slot:title>Mail example page</x-slot:title>
            <x-slot:text>
                Example of mailable test template ('mail.test-mail').
            </x-slot:text>
        </x-laravel-card-link>

        <x-laravel-card-link href="/mailable">
            <x-slot:icon>mail</x-slot:icon>
            <x-slot:title>Mail example page</x-slot:title>
            <x-slot:text>
                Example of mailable test template ('mail.test-mail').
            </x-slot:text>
        </x-laravel-card-link>

        <x-laravel-card-link href="/mailable">
            <x-slot:icon>mail</x-slot:icon>
            <x-slot:title>Mail example page</x-slot:title>
            <x-slot:text>
                Example of mailable test template ('mail.test-mail').
            </x-slot:text>
        </x-laravel-card-link>

        <x-laravel-card-link href="/mailable">
            <x-slot:icon>mail</x-slot:icon>
            <x-slot:title>Mail example page</x-slot:title>
            <x-slot:text>
                Example of mailable test template ('mail.test-mail').
            </x-slot:text>
        </x-laravel-card-link>

        <x-laravel-card-link href="/mailable">
            <x-slot:icon>mail</x-slot:icon>
            <x-slot:title>Mail example page</x-slot:title>
            <x-slot:text>
                Example of mailable test template ('mail.test-mail').
            </x-slot:text>
        </x-laravel-card-link>

        <x-laravel-card-link href="/mailable">
            <x-slot:icon>mail</x-slot:icon>
            <x-slot:title>Mail example page</x-slot:title>
            <x-slot:text>
                Example of mailable test template ('mail.test-mail').
            </x-slot:text>
        </x-laravel-card-link>
    </div>

    <x-laravel-footer></x-laravel-footer>
</x-layouts.index>
