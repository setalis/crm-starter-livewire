<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Operations') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                
                @if (session()->has('message'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('message') }}</span>
                    </div>
                @endif

                @if(empty($operations))
                    <button wire:click="addNewOperation('purchase')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-3">
                        Create New Operation
                    </button>
                @else
                    <button wire:click="showOperationsCart" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded my-3">
                        Continue with {{ count($operations) }} open operation(s)
                    </button>
                @endif
                
                @if($isModal)
                    @include('livewire.admin.operations.create')
                @endif
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="hidden md:table-header-group">
                            <tr class="bg-gray-100 text-center">
                                <th class="px-4 py-2">Date</th>
                                <th class="px-4 py-2">Number</th>
                                <th class="px-4 py-2">Type</th>
                                <th class="px-4 py-2">User</th>
                                <th class="px-4 py-2">Items</th>
                                <th class="px-4 py-2 text-right">Total Amount</th>
                                <th class="px-4 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="block md:table-row-group">
                            @foreach($operationsList as $operation)
                            <tr class="block md:table-row border-b md:border-none mb-4 md:mb-0">
                                <td data-label="Date" class="flex items-center justify-between md:table-cell p-2 md:px-4 md:py-2 md:border md:text-center">{{ $operation->created_at->format('d-m-Y H:i') }}</td>
                                <td data-label="Number" class="flex items-center justify-between md:table-cell p-2 md:px-4 md:py-2 md:border md:text-center">{{ $operation->operation_number }}</td>
                                <td data-label="Type" class="flex items-center justify-between md:table-cell p-2 md:px-4 md:py-2 md:border md:text-center">{{ ucfirst($operation->type) }}</td>
                                <td data-label="User" class="flex items-center justify-between md:table-cell p-2 md:px-4 md:py-2 md:border md:text-center">{{ $operation->user->name }}</td>
                                <td data-label="Items" class="flex items-center justify-between md:table-cell p-2 md:px-4 md:py-2 md:border md:text-center">{{ $operation->items->count() }}</td>
                                <td data-label="Total Amount" class="flex items-center justify-between md:table-cell p-2 md:px-4 md:py-2 md:border md:text-right">{{ number_format($operation->total_amount, 2) }}</td>
                                <td data-label="Action" class="flex items-center justify-between md:table-cell p-2 md:px-4 md:py-2 md:border md:text-center">
                                    <div class="inline-block">
                                        <button wire:click="edit({{ $operation->id }})" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Edit</button>
                                        <button wire:click="delete({{ $operation->id }})" wire:confirm="Are you sure you want to delete this operation?" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Delete</button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $operationsList->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
