<div class="flex items-center space-x-2" wire:poll.1s="updateTime">
    <i class="bi bi-clock text-gray-500 dark:text-gray-400"></i>
    <div class="flex flex-col items-end text-sm">
        <div class="font-mono text-lg font-semibold text-gray-900 dark:text-white">
            {{ $currentTime }}
        </div>
        <div class="text-xs text-gray-500 dark:text-gray-400">
            {{ $currentDate }}
        </div>
    </div>
</div>
