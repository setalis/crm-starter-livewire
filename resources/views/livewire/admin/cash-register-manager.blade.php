<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Касса') }}
        </h2>
    </x-slot>

    <div class="py-6 lg:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4 lg:p-6">
                
                @if (session()->has('message'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('message') }}</span>
                    </div>
                @endif

                @if (session()->has('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                <!-- Cash Register Selection and Info -->
                <div class="mb-6">
                    <!-- Header with responsive layout -->
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 space-y-2 sm:space-y-0">
                        <h3 class="text-lg font-medium text-gray-900">Управление кассой</h3>
                        <button wire:click="showCreateRegisterModalAction" class="w-full sm:w-auto bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">
                            Создать новую кассу
                        </button>
                    </div>

                    @if($cashRegisters && $cashRegisters->count() > 0)
                        <!-- Register Selection -->
                        @if($cashRegisters && $cashRegisters->count() > 1)
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Выберите кассу:</label>
                            <select wire:change="selectRegister($event.target.value)" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                @if($cashRegisters)
                                        @foreach($cashRegisters as $register)
                                        <option value="{{ $register->id }}" {{ $selectedRegister && $selectedRegister->id == $register->id ? 'selected' : '' }}>
                                            {{ $register->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        @endif

                        @if($selectedRegister)
                        <!-- Current Balance and Actions - Mobile Responsive -->
                        <div class="bg-gray-50 p-4 lg:p-6 rounded-lg mb-6">
                            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center space-y-4 lg:space-y-0">
                                <div class="text-center lg:text-left">
                                    <h4 class="text-xl font-semibold text-gray-900">{{ $selectedRegister->name }}</h4>
                                    @if($selectedRegister->description)
                                        <p class="text-sm text-gray-600 mt-1">{{ $selectedRegister->description }}</p>
                                    @endif
                                    <p class="text-2xl lg:text-3xl font-bold text-green-600 mt-2">{{ \App\Helpers\Settings::formatPrice($selectedRegister->balance) }}</p>
                                </div>
                                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                                    <button wire:click="showAddMoneyModalAction" class="w-full sm:w-auto bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">
                                        Добавить деньги
                                    </button>
                                    <button wire:click="showWithdrawMoneyModalAction" class="w-full sm:w-auto bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm">
                                        Снять деньги
                                    </button>
                                    @if($cashRegisters && $cashRegisters->count() > 1)
                                    <button wire:click="showDeleteRegisterModalAction({{ $selectedRegister->id }})" class="w-full sm:w-auto bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm">
                                        Удалить кассу
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Transactions List - Mobile Responsive -->
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 space-y-2 sm:space-y-0">
                            <h4 class="text-lg font-medium text-gray-900">История операций</h4>
                            
                            <!-- Transaction Type Filter -->
                            <div class="flex space-x-1 bg-gray-100 rounded-lg p-1">
                                <button 
                                    wire:click="setTransactionFilter('all')"
                                    class="px-3 py-1 text-sm font-medium rounded-md transition-colors duration-200 flex items-center space-x-1
                                        {{ $transactionTypeFilter === 'all' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    <span>Все</span>
                                </button>
                                <button 
                                    wire:click="setTransactionFilter('money_only')"
                                    class="px-3 py-1 text-sm font-medium rounded-md transition-colors duration-200 flex items-center space-x-1
                                        {{ $transactionTypeFilter === 'money_only' ? 'bg-white text-blue-700 shadow-sm' : 'text-gray-600 hover:text-blue-600' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Деньги</span>
                                </button>
                                <button 
                                    wire:click="setTransactionFilter('income')"
                                    class="px-3 py-1 text-sm font-medium rounded-md transition-colors duration-200 flex items-center space-x-1
                                        {{ $transactionTypeFilter === 'income' ? 'bg-white text-green-700 shadow-sm' : 'text-gray-600 hover:text-green-600' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    <span>Пополнения</span>
                                </button>
                                <button 
                                    wire:click="setTransactionFilter('expense')"
                                    class="px-3 py-1 text-sm font-medium rounded-md transition-colors duration-200 flex items-center space-x-1
                                        {{ $transactionTypeFilter === 'expense' ? 'bg-white text-red-700 shadow-sm' : 'text-gray-600 hover:text-red-600' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                    </svg>
                                    <span>Снятия</span>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Desktop Table -->
                        <div class="hidden lg:block overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Дата</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Тип</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Сумма</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Описание</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Пользователь</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Баланс после</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($this->transactions as $transaction)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ \App\Helpers\Settings::formatDateTime($transaction->created_at) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                                {{ $transaction->type === 'income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $transaction->type === 'income' ? 'Приход' : 'Расход' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium 
                                            {{ $transaction->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $transaction->type === 'income' ? '+' : '-' }}{{ \App\Helpers\Settings::formatPrice($transaction->amount) }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            {{ $transaction->description }}
                                            @if($transaction->operation)
                                                <br><small class="text-gray-500">Операция: {{ $transaction->operation->operation_number }}</small>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $transaction->user ? $transaction->user->name : 'Пользователь не найден (ID: ' . $transaction->user_id . ')' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ \App\Helpers\Settings::formatPrice($transaction->balance_after) }}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                            Транзакции отсутствуют
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile Cards -->
                        <div class="lg:hidden space-y-4">
                            @forelse($this->transactions as $transaction)
                            <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2 mb-2">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                                {{ $transaction->type === 'income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $transaction->type === 'income' ? 'Приход' : 'Расход' }}
                                            </span>
                                            <span class="text-xs text-gray-500">
                                                {{ \App\Helpers\Settings::formatDateTime($transaction->created_at) }}
                                            </span>
                                        </div>
                                        <p class="text-lg font-semibold 
                                            {{ $transaction->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $transaction->type === 'income' ? '+' : '-' }}{{ \App\Helpers\Settings::formatPrice($transaction->amount) }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-gray-500">Баланс после</p>
                                        <p class="text-sm font-medium text-gray-900">{{ \App\Helpers\Settings::formatPrice($transaction->balance_after) }}</p>
                                    </div>
                                </div>
                                
                                <div class="border-t border-gray-100 pt-3">
                                    <p class="text-sm text-gray-900 mb-2">{{ $transaction->description }}</p>
                                    @if($transaction->operation)
                                        <p class="text-xs text-gray-500 mb-1">Операция: {{ $transaction->operation->operation_number }}</p>
                                    @endif
                                    <p class="text-xs text-gray-500">
                                        Пользователь: {{ $transaction->user ? $transaction->user->name : 'Не найден (ID: ' . $transaction->user_id . ')' }}
                                    </p>
                                </div>
                            </div>
                            @empty
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 text-center">
                                <p class="text-gray-500">Транзакции отсутствуют</p>
                            </div>
                            @endforelse
                        </div>
                        
                        <div class="mt-4">
                            {{ $this->transactions->links() }}
                        </div>
                        @endif
                    @else
                        <div class="text-center py-8">
                            <div class="mb-4">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                </svg>
                            </div>
                            <p class="text-gray-500 mb-4">Касса не создана</p>
                            <button wire:click="showCreateRegisterModalAction" class="w-full sm:w-auto bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Создать кассу
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Add Money Modal -->
    @if($showAddMoneyModal)
    <div class="fixed z-50 inset-0 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>​
            <div class="inline-block align-middle bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all my-8 max-w-lg w-full mx-4 sm:mx-0">
                <form wire:submit.prevent="addMoney">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                            Добавить деньги в кассу
                        </h3>
                        
                        <div class="mb-4">
                            <label for="amount" class="block text-sm font-medium text-gray-700">Сумма</label>
                            <input type="number" step="0.01" wire:model="amount" id="amount" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-base" required>
                            @error('amount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Описание</label>
                            <textarea wire:model="description" id="description" rows="3" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-base"></textarea>
                            @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 flex flex-col-reverse sm:flex-row space-y-reverse space-y-2 sm:space-y-0 sm:space-x-2">
                        <button type="button" wire:click="closeModals" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:w-auto sm:text-sm">
                            Отмена
                        </button>
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:w-auto sm:text-sm">
                            Добавить
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Withdraw Money Modal -->
    @if($showWithdrawMoneyModal)
    <div class="fixed z-50 inset-0 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>​
            <div class="inline-block align-middle bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all my-8 max-w-lg w-full mx-4 sm:mx-0">
                <form wire:submit.prevent="withdrawMoney">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                            Снять деньги из кассы
                        </h3>
                        
                        <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded">
                            <p class="text-sm text-yellow-800">
                                Доступно в кассе: <strong>{{ \App\Helpers\Settings::formatPrice($selectedRegister ? $selectedRegister->balance : 0) }}</strong>
                            </p>
                        </div>

                        <div class="mb-4">
                            <label for="amount" class="block text-sm font-medium text-gray-700">Сумма</label>
                            <input type="number" step="0.01" wire:model="amount" id="amount" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-base" required>
                            @error('amount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Описание</label>
                            <textarea wire:model="description" id="description" rows="3" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-base"></textarea>
                            @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 flex flex-col-reverse sm:flex-row space-y-reverse space-y-2 sm:space-y-0 sm:space-x-2">
                        <button type="button" wire:click="closeModals" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:w-auto sm:text-sm">
                            Отмена
                        </button>
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:w-auto sm:text-sm">
                            Снять
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Create Register Modal -->
    @if($showCreateRegisterModal)
    <div class="fixed z-50 inset-0 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>​
            <div class="inline-block align-middle bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all my-8 max-w-lg w-full mx-4 sm:mx-0">
                <form wire:submit.prevent="createRegister">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                            Создать новую кассу
                        </h3>
                        
                        <div class="mb-4">
                            <label for="registerName" class="block text-sm font-medium text-gray-700">Название кассы</label>
                            <input type="text" wire:model="registerName" id="registerName" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-base" required>
                            @error('registerName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="registerDescription" class="block text-sm font-medium text-gray-700">Описание</label>
                            <textarea wire:model="registerDescription" id="registerDescription" rows="3" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-base"></textarea>
                            @error('registerDescription') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 flex flex-col-reverse sm:flex-row space-y-reverse space-y-2 sm:space-y-0 sm:space-x-2">
                        <button type="button" wire:click="closeModals" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:w-auto sm:text-sm">
                            Отмена
                        </button>
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:w-auto sm:text-sm">
                            Создать
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Delete Register Modal -->
    @if($showDeleteRegisterModal && $registerToDelete)
    <div class="fixed z-50 inset-0 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>​
            <div class="inline-block align-middle bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all my-8 max-w-lg w-full mx-4 sm:mx-0">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Удалить кассу
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Вы уверены, что хотите удалить кассу "<strong>{{ $registerToDelete->name }}</strong>"?
                                </p>
                                @if($registerToDelete->balance != 0)
                                <p class="text-sm text-red-600 mt-2">
                                    <strong>Внимание:</strong> В кассе остается {{ \App\Helpers\Settings::formatPrice($registerToDelete->balance) }}
                                </p>
                                @endif
                                <p class="text-xs text-gray-400 mt-2">
                                    Если в кассе есть транзакции, она будет деактивирована вместо удаления для сохранения истории операций.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 flex flex-col-reverse sm:flex-row space-y-reverse space-y-2 sm:space-y-0 sm:space-x-2">
                    <button type="button" wire:click="closeModals" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:w-auto sm:text-sm">
                        Отмена
                    </button>
                    <button type="button" wire:click="deleteRegister" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:w-auto sm:text-sm">
                        Удалить
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
