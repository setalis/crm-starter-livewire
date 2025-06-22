<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="flex min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Platform')" class="grid">
                    <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>
                </flux:navlist.group>

                @if(auth()->user()->hasAdminAccess())
                    <flux:navlist.group heading="Справочники" expandable :expanded="request()->routeIs('admin.units.*') || request()->routeIs('admin.elements.*') || request()->routeIs('admin.products.*')">
                        <flux:navlist.item icon="scale" :href="route('admin.units.index')" :current="request()->routeIs('admin.units.index')" wire:navigate>
                            {{ __('Единицы измерения') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="squares-2x2" :href="route('admin.elements.index')" :current="request()->routeIs('admin.elements.index')" wire:navigate>
                            {{ __('Элементы') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="cube" :href="route('admin.products.index')" :current="request()->routeIs('admin.products.index')" wire:navigate>
                            {{ __('Продукты') }}
                        </flux:navlist.item>
                    </flux:navlist.group>

                    <flux:navlist.group heading="Операции" expandable :expanded="request()->routeIs('admin.operations.*')">
                        <flux:navlist.item icon="document-text" :href="route('admin.operations.index')" :current="request()->routeIs('admin.operations.index')" wire:navigate>
                            {{ __('Все операции') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="plus-circle" :href="route('admin.operations.create', 'purchase')" :current="request()->routeIs('admin.operations.create') && request()->route('type') == 'purchase'" wire:navigate>
                            {{ __('Покупка') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="arrow-up-right" :href="route('admin.operations.create', 'sale')" :current="request()->routeIs('admin.operations.create') && request()->route('type') == 'sale'" wire:navigate>
                            {{ __('Продажа') }}
                        </flux:navlist.item>
                    </flux:navlist.group>

                    <flux:navlist.group heading="Склад" expandable :expanded="request()->routeIs('admin.warehouse.*') || request()->routeIs('admin.conversions.*') || request()->routeIs('admin.recounts.*')">
                        <flux:navlist.item icon="building-storefront" :href="route('admin.warehouse.stock.index')" :current="request()->routeIs('admin.warehouse.stock.index')" wire:navigate>
                            {{ __('Остатки') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="truck" :href="route('admin.shipments.index')" :current="request()->routeIs('admin.shipments.*')" wire:navigate>
                            {{ __('Отгрузки') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="arrow-path" :href="route('admin.conversions.index')" :current="request()->routeIs('admin.conversions.*')" wire:navigate>
                            {{ __('Конвертация продуктов') }}
                        </flux:navlist.item>
                        @can('recounts.view')
                            <flux:navlist.item icon="clipboard-document-check" :href="route('admin.recounts.index')" :current="request()->routeIs('admin.recounts.*')" wire:navigate>
                                {{ __('Переучеты склад') }}
                            </flux:navlist.item>
                        @endcan
                    </flux:navlist.group>

                    <flux:navlist.group heading="Финансы" expandable :expanded="request()->routeIs('admin.cash-register.*') || request()->routeIs('admin.cash-recounts.*')">
                        <flux:navlist.item icon="banknotes" :href="route('admin.cash-register.index')" :current="request()->routeIs('admin.cash-register.index')" wire:navigate>
                            {{ __('Касса') }}
                        </flux:navlist.item>
                        @can('cash_recounts.view')
                            <flux:navlist.item icon="receipt-percent" :href="route('admin.cash-recounts.index')" :current="request()->routeIs('admin.cash-recounts.*')" wire:navigate>
                                {{ __('Переучеты касс') }}
                            </flux:navlist.item>
                        @endcan
                    </flux:navlist.group>

                    @if(auth()->user()->hasAnyPermission(['users.view', 'roles.view', 'permissions.view', 'sections.view']))
                    <flux:navlist.group heading="Администрирование" expandable :expanded="request()->routeIs('admin.users.*') || request()->routeIs('admin.roles.*') || request()->routeIs('admin.permissions.*') || request()->routeIs('admin.sections.*') || request()->routeIs('admin.comments.*')">
                        @can('users.view')
                        <flux:navlist.item icon="users" :href="route('admin.users.index')" :current="request()->routeIs('admin.users.*')" wire:navigate>
                            {{ __('Пользователи') }}
                        </flux:navlist.item>
                        @endcan
                        @can('roles.view')
                        <flux:navlist.item icon="shield-check" :href="route('admin.roles.index')" :current="request()->routeIs('admin.roles.*')" wire:navigate>
                            {{ __('Роли') }}
                        </flux:navlist.item>
                        @endcan
                        @can('permissions.view')
                        <flux:navlist.item icon="key" :href="route('admin.permissions.index')" :current="request()->routeIs('admin.permissions.*')" wire:navigate>
                            {{ __('Разрешения') }}
                        </flux:navlist.item>
                        @endcan
                        @can('sections.view')
                        <flux:navlist.item icon="folder" :href="route('admin.sections.index')" :current="request()->routeIs('admin.sections.*')" wire:navigate>
                            {{ __('Разделы') }}
                        </flux:navlist.item>
                        @endcan
                        @can('comments.view')
                        <flux:navlist.item icon="chat-bubble-left-right" :href="route('admin.comments.index')" :current="request()->routeIs('admin.comments.*')" wire:navigate>
                            {{ __('Комментарии') }}
                        </flux:navlist.item>
                        @endcan
                        @can('settings.manage')
                        <flux:navlist.item icon="cog-6-tooth" :href="route('settings.application')" :current="request()->routeIs('settings.application')" wire:navigate>
                            {{ __('Настройки приложения') }}
                        </flux:navlist.item>
                        @endcan
                    </flux:navlist.group>
                    @endif

                    <flux:navlist.group heading="Отчеты" expandable :expanded="request()->routeIs('admin.reports.*')">
                        <flux:navlist.item icon="chart-bar" :href="route('admin.reports.index')" :current="request()->routeIs('admin.reports.*')" wire:navigate>
                            {{ __('Отчеты по движению') }}
                        </flux:navlist.item>
                    </flux:navlist.group>
                @else
                    <!-- Навигация для менеджеров -->
                    <flux:navlist.group heading="Товары" expandable :expanded="request()->routeIs('manager.products.*')">
                        <flux:navlist.item icon="cube" :href="route('manager.products.index')" :current="request()->routeIs('manager.products.index')" wire:navigate>
                            {{ __('Продукты') }}
                        </flux:navlist.item>
                    </flux:navlist.group>

                    <flux:navlist.group heading="Операции" expandable :expanded="request()->routeIs('manager.operations.*')">
                        <flux:navlist.item icon="document-text" :href="route('manager.operations.index')" :current="request()->routeIs('manager.operations.index')" wire:navigate>
                            {{ __('Все операции') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="plus-circle" :href="route('manager.operations.create', 'purchase')" :current="request()->routeIs('manager.operations.create') && request()->route('type') == 'purchase'" wire:navigate>
                            {{ __('Покупка') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="arrow-up-right" :href="route('manager.operations.create', 'sale')" :current="request()->routeIs('manager.operations.create') && request()->route('type') == 'sale'" wire:navigate>
                            {{ __('Продажа') }}
                        </flux:navlist.item>
                    </flux:navlist.group>

                    <flux:navlist.group heading="Склад" expandable :expanded="request()->routeIs('manager.warehouse.*') || request()->routeIs('manager.shipments.*') || request()->routeIs('manager.recounts.*')">
                        <flux:navlist.item icon="building-storefront" :href="route('manager.warehouse.stock.index')" :current="request()->routeIs('manager.warehouse.stock.index')" wire:navigate>
                            {{ __('Остатки') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="truck" :href="route('manager.shipments.index')" :current="request()->routeIs('manager.shipments.*')" wire:navigate>
                            {{ __('Отгрузки') }}
                        </flux:navlist.item>
                        @can('recounts.view')
                            <flux:navlist.item icon="clipboard-document-check" :href="route('manager.recounts.index')" :current="request()->routeIs('manager.recounts.*')" wire:navigate>
                                {{ __('Переучеты складских остатков') }}
                            </flux:navlist.item>
                        @endcan
                    </flux:navlist.group>

                    <flux:navlist.group heading="Финансы" expandable :expanded="request()->routeIs('manager.cash-register.*') || request()->routeIs('manager.cash-recounts.*')">
                        <flux:navlist.item icon="banknotes" :href="route('manager.cash-register.index')" :current="request()->routeIs('manager.cash-register.index')" wire:navigate>
                            {{ __('Касса') }}
                        </flux:navlist.item>
                        @can('cash_recounts.view')
                            <flux:navlist.item icon="receipt-percent" :href="route('manager.cash-recounts.index')" :current="request()->routeIs('manager.cash-recounts.*')" wire:navigate>
                                {{ __('Переучеты касс') }}
                            </flux:navlist.item>
                        @endcan
                    </flux:navlist.group>

                    <flux:navlist.group heading="Отчеты" expandable :expanded="request()->routeIs('manager.reports.*')">
                        <flux:navlist.item icon="chart-bar" :href="route('manager.reports.index')" :current="request()->routeIs('manager.reports.*')" wire:navigate>
                            {{ __('Отчеты по движению') }}
                        </flux:navlist.item>
                    </flux:navlist.group>
                @endif
            </flux:navlist>

            <flux:spacer />

            <flux:navlist variant="outline">
                <flux:navlist.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                {{ __('Repository') }}
                </flux:navlist.item>

                <flux:navlist.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire" target="_blank">
                {{ __('Documentation') }}
                </flux:navlist.item>
            </flux:navlist>


        </flux:sidebar>

        <div class="flex flex-1 flex-col">
            <!-- Mobile Header -->
            <flux:header class="lg:hidden">
                <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
                <flux:spacer />
                <flux:dropdown position="top" align="end">
                    <flux:profile
                        :initials="auth()->user()->initials()"
                        icon-trailing="chevron-down"
                    />

                    <flux:menu>
                        <flux:menu.radio.group>
                            <div class="p-0 text-sm font-normal">
                                <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                    <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                        <span
                                            class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                        >
                                            {{ auth()->user()->initials() }}
                                        </span>
                                    </span>

                                    <div class="grid flex-1 text-start text-sm leading-tight">
                                        <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                        <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                    </div>
                                </div>
                            </div>
                        </flux:menu.radio.group>

                        <flux:menu.separator />

                        <flux:menu.radio.group>
                            <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                        </flux:menu.radio.group>

                        <flux:menu.separator />

                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                                {{ __('Log Out') }}
                            </flux:menu.item>
                        </form>
                    </flux:menu>
                </flux:dropdown>
            </flux:header>

            <!-- Desktop Header with Quick Actions -->
            <flux:header class="hidden lg:block bg-white lg:bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700">
                <flux:navbar class="w-full">
                    <!-- Быстрые действия слева -->
                    @if(auth()->user()->hasAdminAccess())
                        <flux:button :href="route('admin.operations.create', 'purchase')" variant="filled" size="sm" icon="plus" style="background-color: #059669; border-color: #059669;" class="!bg-green-600 hover:!bg-green-700 !text-white" wire:navigate>
                            Покупка
                        </flux:button>
                        <flux:button :href="route('admin.operations.create', 'sale')" variant="filled" size="sm" icon="arrow-up-right" style="background-color: #2563eb; border-color: #2563eb;" class="!bg-blue-600 hover:!bg-blue-700 !text-white" wire:navigate>
                            Продажа  
                        </flux:button>
                    @else
                        <flux:button :href="route('manager.operations.create', 'purchase')" variant="filled" size="sm" icon="plus" style="background-color: #059669; border-color: #059669;" class="!bg-green-600 hover:!bg-green-700 !text-white" wire:navigate>
                            Покупка
                        </flux:button>
                        <flux:button :href="route('manager.operations.create', 'sale')" variant="filled" size="sm" icon="arrow-up-right" style="background-color: #2563eb; border-color: #2563eb;" class="!bg-blue-600 hover:!bg-blue-700 !text-white" wire:navigate>
                            Продажа
                        </flux:button>
                    @endif

                    <flux:spacer />

                    <!-- Комментарии и часы -->
                    <div class="flex items-center space-x-4">
                        @if(auth()->user()->hasAdminAccess())
                            <livewire:admin.components.comments-notification />
                        @endif
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            <livewire:admin.components.live-clock />
                        </div>
                    </div>

                    <!-- Профиль пользователя справа -->
                    <flux:dropdown position="bottom" align="end">
                        <flux:profile
                            :name="auth()->user()->name"
                            :initials="auth()->user()->initials()"
                            icon:trailing="chevrons-up-down"
                        />

                        <flux:menu class="w-[220px]">
                            <flux:menu.radio.group>
                                <div class="p-0 text-sm font-normal">
                                    <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                        <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                            <span
                                                class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                            >
                                                {{ auth()->user()->initials() }}
                                            </span>
                                        </span>

                                        <div class="grid flex-1 text-start text-sm leading-tight">
                                            <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                            <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                        </div>
                                    </div>
                                </div>
                            </flux:menu.radio.group>

                            <flux:menu.separator />

                            <flux:menu.radio.group>
                                <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                            </flux:menu.radio.group>

                            <flux:menu.separator />

                            <form method="POST" action="{{ route('logout') }}" class="w-full">
                                @csrf
                                <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                                    {{ __('Log Out') }}
                                </flux:menu.item>
                            </form>
                        </flux:menu>
                    </flux:dropdown>
                </flux:navbar>
            </flux:header>

            {{ $slot }}
        </div>

        @livewireScripts
        @fluxScripts
        <script src="https://cdn.jsdelivr.net/gh/livewire/sortable@v1.x.x/dist/livewire-sortable.js"></script>
    </body>
</html>
