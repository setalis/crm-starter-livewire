<div class="flex-1 overflow-hidden">
    <flux:main class="px-6 pb-16">
        <div class="mx-auto max-w-4xl">
            <flux:heading size="xl" class="mb-2">{{ __('Настройки приложения') }}</flux:heading>
            <flux:subheading class="mb-8">{{ __('Настройте основные параметры вашего приложения') }}</flux:subheading>
            
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
                <form wire:submit="save" class="space-y-6">
            
            {{-- Сообщения об успехе/ошибке --}}
            @if (session()->has('success'))
                <div class="mb-6 rounded-md bg-green-50 p-4 border border-green-200 dark:bg-green-900/20 dark:border-green-800">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800 dark:text-green-200">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="mb-6 rounded-md bg-red-50 p-4 border border-red-200 dark:bg-red-900/20 dark:border-red-800">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800 dark:text-red-200">
                                {{ session('error') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Название компании --}}
            <div>
                <flux:input 
                    wire:model="company_name" 
                    :label="__('Название компании')" 
                    type="text" 
                    required 
                    autocomplete="organization"
                    :placeholder="__('Введите название вашей компании')"
                />
                @error('company_name')
                    <flux:text class="mt-1 text-red-600 text-sm">{{ $message }}</flux:text>
                @enderror
            </div>

            {{-- Логотип компании --}}
            <div>
                <flux:label>{{ __('Логотип компании') }}</flux:label>
                
                {{-- Текущий логотип --}}
                @if($current_logo)
                    <div class="mt-2 mb-4">
                        <flux:text class="text-sm text-gray-600 mb-2">{{ __('Текущий логотип:') }}</flux:text>
                        <div class="flex items-center space-x-4">
                            <img src="{{ Storage::url($current_logo) }}" alt="Company Logo" class="h-16 w-auto object-contain border rounded">
                            <flux:button 
                                type="button" 
                                variant="danger" 
                                size="sm"
                                wire:click="removeLogo"
                                wire:confirm="Вы уверены, что хотите удалить логотип?"
                            >
                                {{ __('Удалить') }}
                            </flux:button>
                        </div>
                    </div>
                @endif

                {{-- Загрузка нового логотипа --}}
                <flux:input 
                    type="file" 
                    wire:model="company_logo" 
                    accept="image/*"
                    :label="$current_logo ? __('Загрузить новый логотип') : __('Загрузить логотип')"
                />
                
                @if($company_logo)
                    <div class="mt-2">
                        <flux:text class="text-sm text-gray-600">{{ __('Предварительный просмотр:') }}</flux:text>
                        <img src="{{ $company_logo->temporaryUrl() }}" alt="Preview" class="mt-1 h-16 w-auto object-contain border rounded">
                    </div>
                @endif

                @error('company_logo')
                    <flux:text class="mt-1 text-red-600 text-sm">{{ $message }}</flux:text>
                @enderror
                
                <flux:text class="mt-1 text-gray-500 text-sm">
                    {{ __('Рекомендуемый размер: 200x200px. Максимальный размер файла: 2MB. Поддерживаемые форматы: JPG, PNG, GIF.') }}
                </flux:text>
            </div>

            {{-- Валюта --}}
            <div>
                <flux:select 
                    wire:model="currency" 
                    :label="__('Валюта')" 
                    required
                    :placeholder="__('Выберите валюту')"
                >
                    @foreach($this->getCurrencies() as $code => $name)
                        <option value="{{ $code }}">{{ $name }}</option>
                    @endforeach
                </flux:select>
                @error('currency')
                    <flux:text class="mt-1 text-red-600 text-sm">{{ $message }}</flux:text>
                @enderror
            </div>

            {{-- Часовой пояс --}}
            <div>
                <flux:select 
                    wire:model="timezone" 
                    :label="__('Часовой пояс')" 
                    required
                    :placeholder="__('Выберите часовой пояс')"
                >
                    @foreach($this->getTimezones() as $zone => $name)
                        <option value="{{ $zone }}">{{ $name }}</option>
                    @endforeach
                </flux:select>
                @error('timezone')
                    <flux:text class="mt-1 text-red-600 text-sm">{{ $message }}</flux:text>
                @enderror
            </div>

                    {{-- Кнопки действий --}}
                    <div class="flex items-center gap-4 pt-4">
                        <flux:button 
                            variant="primary" 
                            type="submit" 
                            class="w-full md:w-auto"
                            wire:loading.attr="disabled"
                        >
                            <span wire:loading.remove wire:target="save">{{ __('Сохранить настройки') }}</span>
                            <span wire:loading wire:target="save">{{ __('Сохранение...') }}</span>
                        </flux:button>

                        <x-action-message class="ml-3" on="settings-updated">
                            {{ __('Сохранено.') }}
                        </x-action-message>
                    </div>
                </form>
            </div>
        </div>
    </flux:main>
</div> 