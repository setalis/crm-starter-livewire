<x-layouts.app.sidebar :title="$title ?? null">
    <flux:main>
        {{ $slot }}
    </flux:main>   

    <flux:spacer />
</x-layouts.app.sidebar>
