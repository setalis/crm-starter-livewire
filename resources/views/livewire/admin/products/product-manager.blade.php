<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                <button wire:click="create()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-3">Create New Product</button>
                @if($isModal)
                    @include('livewire.admin.products.create')
                @endif
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="hidden md:table-header-group">
                            <tr class="bg-gray-100 text-center">
                                <th class="px-4 py-2 w-20"></th>
                                <th class="px-4 py-2">Изображение</th>
                                <th class="px-4 py-2">№ п/п</th>
                                <th class="px-4 py-2 text-left">Наименование</th>
                                <th class="px-4 py-2">Тип</th>
                                <th class="px-4 py-2">Ед.изм.</th>
                                <th class="px-4 py-2">Опубликован</th>
                                <th class="px-4 py-2">Действия</th>
                            </tr>
                        </thead>
                        <tbody wire:sortable="updateProductOrder" class="block md:table-row-group">
                            @foreach($products as $product)
                            <tr wire:sortable.item="{{ $product->id }}" wire:key="product-{{ $product->id }}" class="block md:table-row border-b md:border-none mb-4 md:mb-0">
                                <td data-label="" class="flex items-center justify-center md:table-cell p-2 md:px-4 md:py-2 md:border md:text-center" wire:sortable.handle>
                                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                        <path d="M4 6h16M4 12h16m-7 6h7"></path>
                                    </svg>
                                </td>
                                <td data-label="Image" class="flex items-center justify-between md:table-cell p-2 md:px-4 md:py-2 md:border md:text-center">
                                    <span class="font-bold md:hidden mr-2">Изображение:</span>
                                    @if ($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-[79px] h-auto object-cover mx-auto">
                                    @endif
                                </td>
                                <td data-label="Position" class="flex items-center justify-between md:table-cell p-2 md:px-4 md:py-2 md:border md:text-center">
                                     <span class="font-bold md:hidden mr-2">Position:</span>
                                    {{ $product->position }}
                                </td>
                                <td data-label="Name" class="flex items-center justify-between md:table-cell p-2 md:px-4 md:py-2 md:border md:text-left">
                                     <span class="font-bold md:hidden mr-2">Name:</span>
                                    {{ $product->name }}
                                </td>
                                <td data-label="Тип" class="flex items-center justify-between md:table-cell p-2 md:px-4 md:py-2 md:border md:text-center">
                                     <span class="font-bold md:hidden mr-2">Тип:</span>
                                     <span @class([
                                        'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                        'bg-green-100 text-green-800' => $product->type === 'simple',
                                        'bg-blue-100 text-blue-800' => $product->type === 'composite',
                                    ])>
                                        {{ $product->type === 'simple' ? 'Простой' : 'Составной' }}
                                    </span>
                                </td>
                                <td data-label="Unit" class="flex items-center justify-between md:table-cell p-2 md:px-4 md:py-2 md:border md:text-center">
                                     <span class="font-bold md:hidden mr-2">Unit:</span>
                                    {{ $product->unit->short_name }}
                                </td>
                                <td data-label="Published" class="flex items-center justify-between md:table-cell p-2 md:px-4 md:py-2 md:border md:text-center">
                                    <span class="font-bold md:hidden mr-2">Published:</span>
                                    {{ $product->is_published ? 'Yes' : 'No' }}
                                </td>
                                <td data-label="Action" class="flex items-center justify-between md:table-cell p-2 md:px-4 md:py-2 md:border md:text-center">
                                    <span class="font-bold md:hidden mr-2">Action:</span>
                                    <div class="inline-block">
                                        <button wire:click="edit({{ $product->id }})" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Edit</button>
                                        <button wire:click="delete({{ $product->id }})" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Delete</button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
