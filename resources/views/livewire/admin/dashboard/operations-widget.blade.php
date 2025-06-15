<div class="h-full flex flex-col bg-white dark:bg-zinc-800 rounded-xl border border-neutral-200 dark:border-neutral-700">
    <!-- Заголовок -->
    <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center space-x-2">
            <i class="bi bi-arrow-left-right text-lg text-green-600"></i>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Операции</h3>
            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                {{ $recentOperations->count() }}
            </span>
        </div>
        <a href="{{ route('admin.operations.index') }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
            Все операции →
        </a>
    </div>

    <!-- Содержимое -->
    <div class="flex-1 overflow-y-auto p-4">
        <!-- Статистика за сегодня -->
        <div class="mb-4">
            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Сегодня</h4>
            <div class="grid grid-cols-2 gap-2">
                <div class="bg-green-50 dark:bg-green-900/20 p-2 rounded">
                    <div class="text-xs text-green-600 dark:text-green-400">Покупки</div>
                    <div class="text-lg font-bold text-green-700 dark:text-green-300">{{ $todayStats['purchases'] }}</div>
                </div>
                <div class="bg-blue-50 dark:bg-blue-900/20 p-2 rounded">
                    <div class="text-xs text-blue-600 dark:text-blue-400">Продажи</div>
                    <div class="text-lg font-bold text-blue-700 dark:text-blue-300">{{ $todayStats['sales'] }}</div>
                </div>
            </div>
            <div class="mt-2 text-center">
                <div class="text-xs text-gray-500 dark:text-gray-400">Оборот</div>
                <div class="text-sm font-bold text-gray-900 dark:text-white">{{ number_format($todayStats['amount'], 2) }} ₴</div>
            </div>
        </div>

        <!-- Последние операции -->
        @if($recentOperations->count() > 0)
            <div class="mb-4">
                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Последние операции</h4>
                <div class="space-y-2 max-h-40 overflow-y-auto">
                    @foreach($recentOperations as $operation)
                    <div class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-700 rounded">
                        <div class="flex-1">
                            <div class="flex items-center space-x-2">
                                <span class="text-xs font-mono text-gray-600 dark:text-gray-400">{{ $operation->operation_number }}</span>
                                <span @class([
                                    'inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium',
                                    'bg-green-100 text-green-800' => $operation->type === 'purchase',
                                    'bg-blue-100 text-blue-800' => $operation->type === 'sale',
                                ])>
                                    {{ $operation->type === 'purchase' ? 'Покупка' : 'Продажа' }}
                                </span>
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                {{ $operation->user->name }} • {{ $operation->items->count() }} поз.
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-bold text-gray-900 dark:text-white">
                                {{ number_format($operation->total_amount, 2) }} ₴
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $operation->created_at->format('H:i') }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Статистика за месяц -->
        <div class="mt-4 pt-3 border-t border-gray-200 dark:border-gray-600">
            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">За месяц</h4>
            <div class="grid grid-cols-3 gap-2 text-center">
                <div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Всего</div>
                    <div class="text-lg font-bold text-gray-900 dark:text-white">{{ $monthStats['total'] }}</div>
                </div>
                <div>
                    <div class="text-xs text-green-600 dark:text-green-400">Покупки</div>
                    <div class="text-lg font-bold text-green-700 dark:text-green-300">{{ $monthStats['purchases'] }}</div>
                </div>
                <div>
                    <div class="text-xs text-blue-600 dark:text-blue-400">Продажи</div>
                    <div class="text-lg font-bold text-blue-700 dark:text-blue-300">{{ $monthStats['sales'] }}</div>
                </div>
            </div>
            <div class="mt-2 text-center">
                <div class="text-xs text-gray-500 dark:text-gray-400">Общий оборот</div>
                <div class="text-lg font-bold text-green-600 dark:text-green-400">{{ number_format($monthStats['amount'], 2) }} ₴</div>
            </div>
        </div>
    </div>
</div>
