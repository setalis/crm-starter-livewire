<section class="w-full">
    @include('partials.settings-heading')

    <div>
        <flux:heading>{{ __('Appearance') }}</flux:heading>
        <flux:subheading>{{ __('Customize the appearance of the application') }}</flux:subheading>
        
        <div x-data class="flex space-x-4 mt-4">
            <label class="flex items-center cursor-pointer">
                <input type="radio" value="light" x-model="$flux.appearance" class="sr-only" />
                <div class="flex items-center px-4 py-2 border rounded-lg transition-colors" 
                     :class="$flux.appearance === 'light' ? 'bg-blue-50 border-blue-200 text-blue-700' : 'border-gray-200 hover:bg-gray-50'">
                    <flux:icon name="sun" class="w-4 h-4 mr-2" />
                    {{ __('Light') }}
                </div>
            </label>
            
            <label class="flex items-center cursor-pointer">
                <input type="radio" value="dark" x-model="$flux.appearance" class="sr-only" />
                <div class="flex items-center px-4 py-2 border rounded-lg transition-colors"
                     :class="$flux.appearance === 'dark' ? 'bg-blue-50 border-blue-200 text-blue-700' : 'border-gray-200 hover:bg-gray-50'">
                    <flux:icon name="moon" class="w-4 h-4 mr-2" />
                    {{ __('Dark') }}
                </div>
            </label>
            
            <label class="flex items-center cursor-pointer">
                <input type="radio" value="system" x-model="$flux.appearance" class="sr-only" />
                <div class="flex items-center px-4 py-2 border rounded-lg transition-colors"
                     :class="$flux.appearance === 'system' ? 'bg-blue-50 border-blue-200 text-blue-700' : 'border-gray-200 hover:bg-gray-50'">
                    <flux:icon name="computer-desktop" class="w-4 h-4 mr-2" />
                    {{ __('System') }}
                </div>
            </label>
        </div>
    </div>
</section>
