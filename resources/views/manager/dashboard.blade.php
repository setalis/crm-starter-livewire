<x-layouts.app>
<div class="space-y-6">
    <flux:heading size="xl">Панель менеджера</flux:heading>
    
    <!-- Быстрые действия -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <flux:subheading>Быстрая покупка</flux:subheading>
                </div>
                <div class="p-2 bg-green-100 dark:bg-green-900/20 rounded-lg">
                    <flux:icon name="plus" size="lg" class="text-green-500" />
                </div>
            </div>
            <div class="mt-4">
                <flux:button :href="route('manager.operations.create', 'purchase')" variant="filled" size="sm" wire:navigate>
                    Создать покупку
                </flux:button>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <flux:subheading>Быстрая продажа</flux:subheading>
                </div>
                <div class="p-2 bg-blue-100 dark:bg-blue-900/20 rounded-lg">
                    <flux:icon name="arrow-up-right" size="lg" class="text-blue-500" />
                </div>
            </div>
            <div class="mt-4">
                <flux:button :href="route('manager.operations.create', 'sale')" variant="filled" size="sm" wire:navigate>
                    Создать продажу
                </flux:button>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <flux:subheading>Склад</flux:subheading>
                </div>
                <div class="p-2 bg-purple-100 dark:bg-purple-900/20 rounded-lg">
                    <flux:icon name="cube" size="lg" class="text-purple-500" />
                </div>
            </div>
            <div class="mt-4">
                <flux:button :href="route('manager.warehouse.stock.index')" variant="filled" size="sm" wire:navigate>
                    Просмотреть склад
                </flux:button>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <flux:subheading>Касса</flux:subheading>
                </div>
                <div class="p-2 bg-yellow-100 dark:bg-yellow-900/20 rounded-lg">
                    <flux:icon name="banknotes" size="lg" class="text-yellow-500" />
                </div>
            </div>
            <div class="mt-4">
                <flux:button :href="route('manager.cash-register.index')" variant="filled" size="sm" wire:navigate>
                    Управление кассой
                </flux:button>
            </div>
        </div>
    </div>

    <!-- Информационные карточки -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
            <div class="border-b border-zinc-200 dark:border-zinc-700 p-4">
                <flux:heading size="lg">Последние операции</flux:heading>
            </div>
            <div class="p-6 text-center text-zinc-500 dark:text-zinc-400">
                <flux:icon name="document-text" size="lg" class="mx-auto mb-2" />
                <p>Здесь будут отображаться последние операции</p>
                <flux:button :href="route('manager.operations.index')" variant="ghost" size="sm" class="mt-2" wire:navigate>
                    Все операции
                </flux:button>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
            <div class="border-b border-zinc-200 dark:border-zinc-700 p-4">
                <flux:heading size="lg">Товары</flux:heading>
            </div>
            <div class="p-6 text-center text-zinc-500 dark:text-zinc-400">
                <flux:icon name="cube" size="lg" class="mx-auto mb-2" />
                <p>Управление товарами и их остатками</p>
                <flux:button :href="route('manager.products.index')" variant="ghost" size="sm" class="mt-2" wire:navigate>
                    Все товары
                </flux:button>
            </div>
        </div>
    </div>

    <!-- Информационное сообщение -->
    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
        <div class="flex items-start space-x-3">
            <flux:icon name="information-circle" class="text-blue-500 mt-1" />
            <div class="text-sm text-blue-800 dark:text-blue-200">
                <p class="font-medium">Добро пожаловать в панель менеджера!</p>
                <p class="mt-1">Здесь вы можете быстро создавать операции покупки и продажи, управлять складом и кассой.</p>
            </div>
        </div>
    </div>
</div>
</x-layouts.app> 