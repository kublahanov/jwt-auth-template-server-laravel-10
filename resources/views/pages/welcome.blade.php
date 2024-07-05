<x-layouts.index>
    <x-top-menu/>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-5">
        <x-card-link href="/api">
            <x-slot:icon>api</x-slot:icon>
            <x-slot:title>REST API list</x-slot:title>
            <x-slot:text>
                <p class="mb-2">
                    Show list of all API routes.
                </p>
                <p class="mb-2">
                    With HTTP header <code class="bg-gray-200 px-1">Content-Type: application/json</code>
                    - returns in JSON format. Otherwise, in HTML format: as a table or as a container with JSON.
                </p>
                <p>
                    Available GET-parameters:
                </p>
                <ul class="list-disc list-outside pl-4">
                    <li>
                        <b>json</b> - in HTML format show as container with JSON.
                    </li>
                    <li>
                        <b>only</b> - show in list only selected categories (separated by commas), e. g.:
                        <code class="bg-gray-200 px-1">only=auth,test</code>.
                    </li>
                </ul>
            </x-slot:text>
        </x-card-link>

        <x-card-link href="/example">
            <x-slot:icon>code_blocks</x-slot:icon>
            <x-slot:title>HTTP example page</x-slot:title>
            <x-slot:text>
                Result of executing console command
                <code class="bg-gray-200 px-1">php artisan x-test:http</code>
                - downloaded page from http://example.com/.
            </x-slot:text>
        </x-card-link>

        <x-card-link href="/mailable">
            <x-slot:icon>mail</x-slot:icon>
            <x-slot:title>Mail example page</x-slot:title>
            <x-slot:text>
                Example of mailable test template ('mail.test-mail').
            </x-slot:text>
        </x-card-link>
    </div>
</x-layouts.index>
