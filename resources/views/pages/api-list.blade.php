<?php

/* @var array $routes */
if (request()->has('json')):
    $json = str_replace('\\\\', '\\',
        json_encode($routes, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
    );
endif;
?>

<style>
    td {
        word-break: break-all;
        overflow-wrap: break-word;
    }

    .w-900 {
        width: 900px;
    }
</style>

<x-layouts.index>
    <x-top-menu>
        <x-top-menu-link href="{{ url('/') }}">
            Home
        </x-top-menu-link>

        @if (request()->has('json'))
            <x-top-menu-link href="{{ url('/api') }}">
                HTML-format
            </x-top-menu-link>
        @else
            <x-top-menu-link href="{{ url('/api') . '?json' }}">
                JSON-format
            </x-top-menu-link>
        @endif
    </x-top-menu>

    <h1 class="text-xl font-bold my-6">
        API routes list
    </h1>

    @if (request()->has('json'))
        <pre class="w-900 text-sm p-4 bg-slate-700 text-white rounded-lg"><?= $json ?></pre>
    @else
        <table class="w-900 border-collapse border text-sm">
            <thead>
            <tr>
                <th class="text-left border border-slate-300 py-1 px-2 bg-gray-200">URI</th>
                <th class="text-left border border-slate-300 py-1 px-2 bg-gray-200">Methods</th>
                <th class="text-left border border-slate-300 py-1 px-2 bg-gray-200">Name</th>
                <th class="text-left border border-slate-300 py-1 px-2 bg-gray-200">Action</th>
                <th class="text-left border border-slate-300 py-1 px-2 bg-gray-200">Middlewares</th>
                <th class="text-left border border-slate-300 py-1 px-2 bg-gray-200">Link (only for GET)</th>
            </tr>
            </thead>
            <tbody>
            @foreach($routes as $route)
                <tr>
                    <td class="border border-slate-300 py-1 px-2">{{ $route['uri'] }}</td>
                    <td class="border border-slate-300 py-1 px-2">{{ $route['methods'] }}</td>
                    <td class="border border-slate-300 py-1 px-2">{{ $route['name'] }}</td>
                    <td class="border border-slate-300 py-1 px-2">{{ $route['action'] }}</td>
                    <td class="border border-slate-300 py-1 px-2">{{ $route['middleware'] }}</td>
                    <td class="border border-slate-300 py-1 px-2">
                        <a class="text-blue-700" href="{{ $route['link'] }}">{{ $route['link'] }}</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</x-layouts.index>
