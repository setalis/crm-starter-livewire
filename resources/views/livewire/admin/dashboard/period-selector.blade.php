<div class="bg-white dark:bg-zinc-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-3">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-2">
            <i class="bi bi-calendar3 text-base text-blue-600"></i>
            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Период анализа</h3>
        </div>
        <div class="flex items-center space-x-3">
            <!-- Селектор периода -->
            <select wire:model.live="selectedPeriod" 
                    class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                @foreach($this->getPeriodOptions() as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
            <div class="text-sm text-gray-500 dark:text-gray-400">
                {{ ($periodData = $this->getPeriodDates()) ? $periodData['label'] : 'Период не задан' }}
            </div>
        </div>
    </div>
</div> 