<a href="{{ $attributes->get('href') }}"
   class="scale-100 p-5 bg-white from-gray-700/50 via-transparent rounded-lg shadow-2xl shadow-gray-500/20 motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500">
    <div class="flex">
        <div class="flex-none h-10 w-10 bg-red-50 rounded-full flex justify-center items-center">
            <span class="material-symbols-outlined">
                {{ $icon }}
            </span>
        </div>
        <div class="flex grow items-center">
            <h2 class="px-4 text-xl font-semibold text-gray-900">
                {{ $title }}
            </h2>
        </div>
        <div class="flex-none h-10 w-10 flex justify-center items-center">
            <span class="material-symbols-outlined text-red-500">
                arrow_forward
            </span>
        </div>
    </div>
    <div class="row">
        <p class="mt-4 text-gray-500 text-sm leading-relaxed">
            {{ $text }}
        </p>
    </div>
</a>
