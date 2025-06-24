<div class="fixed z-20 inset-0 overflow-y-auto ease-out duration-400">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>​
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
            <form>
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        {{-- Column 1 --}}
                        <div class="space-y-4">
                            <div class="mb-4">
                                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
                                <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" wire:model.blur="name">
                                @error('name') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>
                            <div class="mb-4">
                                <label for="type" class="block text-gray-700 text-sm font-bold mb-2">Type:</label>
                                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="type" wire:model.live="type">
                                    <option value="simple">Simple</option>
                                    <option value="composite">Composite</option>
                                </select>
                                @error('type') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>
                            <div class="mb-4">
                                <label for="unit_id" class="block text-gray-700 text-sm font-bold mb-2">Unit:</label>
                                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="unit_id" wire:model.live="unit_id">
                                    <option value="">Select Unit</option>
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                    @endforeach
                                </select>
                                @error('unit_id') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        {{-- Column 2 --}}
                        <div class="space-y-4">
                            <div class="mb-4">
                                <label for="purchase_price" class="block text-gray-700 text-sm font-bold mb-2">Purchase Price:</label>
                                <input type="number" step="0.01" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="purchase_price" wire:model.blur="purchase_price">
                                @error('purchase_price') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>
                            <div class="mb-4">
                                <label for="selling_price" class="block text-gray-700 text-sm font-bold mb-2">Selling Price:</label>
                                <input type="number" step="0.01" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="selling_price" wire:model.blur="selling_price">
                                @error('selling_price') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>
                            <div class="mb-4">
                                <label for="clogging" class="block text-gray-700 text-sm font-bold mb-2">Clogging (%):</label>
                                <input type="number" step="0.01" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="clogging" wire:model.blur="clogging">
                                @error('clogging') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        {{-- Column 3 --}}
                        <div class="space-y-4">
                            <div class="mb-4">
                                <label for="photo" class="block text-gray-700 text-sm font-bold mb-2">Изображение:</label>
                                
                                <!-- Зона перетаскивания файлов -->
                                <div class="relative" 
                                     x-data="fileUploader()" 
                                     x-init="init()">
                                    <div 
                                        class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-all duration-200 cursor-pointer"
                                        :class="{ 
                                            'border-blue-500 bg-blue-50': isDragging,
                                            'border-green-500 bg-green-50': isUploading
                                        }"
                                        @dragover.prevent="isDragging = true"
                                        @dragleave.prevent="isDragging = false"
                                        @drop.prevent="handleDrop($event)"
                                        @click="$refs.fileInput.click()"
                                    >
                                        @if ($photo)
                                            <!-- Предварительный просмотр загруженного изображения -->
                                            <div class="relative">
                                                <img src="{{ $photo->temporaryUrl() }}" class="max-w-full h-32 mx-auto rounded-lg shadow-md object-cover">
                                                <button 
                                                    type="button" 
                                                    wire:click="$set('photo', null)"
                                                    class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600 transition-colors"
                                                >
                                                    ×
                                                </button>
                                                <div class="mt-2 text-sm text-gray-600">
                                                    Нажмите для замены изображения
                                                </div>
                                            </div>
                                        @elseif ($image)
                                            <!-- Предварительный просмотр существующего изображения -->
                                            <div class="relative">
                                                <img src="{{ asset('storage/' . $image) }}" class="max-w-full h-32 mx-auto rounded-lg shadow-md object-cover">
                                                <button 
                                                    type="button" 
                                                    wire:click="$set('image', null)"
                                                    class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600 transition-colors"
                                                >
                                                    ×
                                                </button>
                                                <div class="mt-2 text-sm text-gray-600">
                                                    Нажмите для замены изображения
                                                </div>
                                            </div>
                                        @else
                                            <!-- Область для загрузки -->
                                            <div class="space-y-3" x-show="!isUploading">
                                                <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                                <div class="text-center">
                                                    <p class="text-gray-600 mb-1">
                                                        <span class="font-medium text-blue-600 hover:text-blue-700 cursor-pointer">
                                                            Нажмите для выбора файла
                                                        </span>
                                                    </p>
                                                    <p class="text-gray-500 mb-1">или перетащите изображение сюда</p>
                                                    <p class="text-xs text-gray-400">PNG, JPG, GIF до 1MB</p>
                                                </div>
                                            </div>

                                            <!-- Индикатор загрузки -->
                                            <div x-show="isUploading" class="space-y-3 text-center">
                                                <div class="mx-auto w-8 h-8">
                                                    <svg class="animate-spin w-full h-full text-blue-600" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                </div>
                                                <p class="text-sm text-blue-600">Загрузка...</p>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Скрытое поле для загрузки файлов -->
                                    <input 
                                        type="file" 
                                        x-ref="fileInput"
                                        class="hidden" 
                                        accept="image/*"
                                        wire:model="photo"
                                        @change="handleFileSelect($event)"
                                    >
                                </div>
                                
                                @error('photo') 
                                    <div class="mt-1 text-sm text-red-600 flex items-center space-x-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="is_published" class="block text-gray-700 text-sm font-bold mb-2">Опубликован:</label>
                                <input type="checkbox" id="is_published" wire:model.live="is_published">
                            </div>
                        </div>
                    </div>

                    {{-- Full-width sections --}}
                    <div class="col-span-1 md:col-span-3 mt-4">
                        @if($type === 'composite')
                        <div class="mb-4 border-t pt-4">
                            <h4 class="font-bold mb-2">Элементы состава</h4>

                            <div class="grid grid-cols-1 md:grid-cols-6 gap-4 items-end">
                                <div class="md:col-span-3">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Выберите элемент:</label>
                                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" wire:model.live="element_id_to_add">
                                        <option value="">Выберите элемент</option>
                                        @foreach($elements->whereNotIn('id', $selectedElements) as $element)
                                            <option value="{{ $element->id }}">{{ $element->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                @php
                                    $currentElement = $element_id_to_add ? $elements->find($element_id_to_add) : null;
                                @endphp

                                <div class="md:col-span-1">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Ед. изм.:</label>
                                    <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" value="{{ $currentElement->unit->short_name ?? '' }}" disabled>
                                </div>
                                <div class="md:col-span-1">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Цена:</label>
                                    <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" value="{{ $currentElement->price ?? '' }}" disabled>
                                </div>
                                <div class="md:col-span-1">
                                    <button type="button" wire:click="addElement" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full" @if(!$element_id_to_add) disabled @endif>Добавить</button>
                                </div>
                            </div>
                            @error('selectedElements') <span class="text-red-500 mt-2 block">{{ $message }}</span>@enderror

                            @if(!empty($selectedElements))
                                <div class="mt-4">
                                    <h5 class="font-semibold mb-2">Добавленные элементы:</h5>
                                    <ul class="list-disc list-inside">
                                        @foreach($selectedElements as $elementId)
                                            @php $el = $elements->find($elementId); @endphp
                                            <li class="flex justify-between items-center mb-1">
                                                <span>{{ $el->name }} ({{ $el->price }} / {{ $el->unit->short_name }})</span>
                                                <button type="button" wire:click="removeElement({{ $elementId }})" class="text-red-500 hover:text-red-700 text-sm">Удалить</button>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                        @endif

                        <div class="mb-4 border-t pt-4">
                            <h4 class="font-bold">Price Scale</h4>
                            <button type="button" wire:click="addPriceScale" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded text-sm mb-2">Add Price</button>
                            @foreach($priceScales as $index => $priceScale)
                            <div class="flex items-center mt-2">
                                <input type="number" placeholder="Threshold (kg)" wire:model.blur="priceScales.{{$index}}.threshold_kg" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-2">
                                <input type="number" placeholder="Price" wire:model.blur="priceScales.{{$index}}.price" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-2">
                                <button type="button" wire:click="removePriceScale({{$index}})" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded text-sm">Remove</button>
                            </div>
                            @error('priceScales.'.$index.'.threshold_kg') <span class="text-red-500">{{ $message }}</span>@enderror
                            @error('priceScales.'.$index.'.price') <span class="text-red-500">{{ $message }}</span>@enderror
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                        <button wire:click.prevent="store()" type="button" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                        Save
                        </button>
                    </span>
                    <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                        
                        <button wire:click="closeModal()" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                        Cancel
                        </button>
                    </span>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function fileUploader() {
    return {
        isDragging: false,
        isUploading: false,
        
        init() {
            // Инициализация компонента
        },
        
        handleDrop(e) {
            this.isDragging = false;
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                this.handleFile(files[0]);
            }
        },
        
        handleFileSelect(e) {
            const files = e.target.files;
            if (files.length > 0) {
                this.handleFile(files[0]);
            }
        },
        
        handleFile(file) {
            // Проверка типа файла
            if (!file.type.startsWith('image/')) {
                alert('Пожалуйста, выберите файл изображения');
                return;
            }
            
            // Проверка размера файла (1MB = 1024 * 1024 bytes)
            if (file.size > 1024 * 1024) {
                alert('Размер файла не должен превышать 1MB');
                return;
            }
            
            this.isUploading = true;
            
            // Livewire загрузка файла
            @this.upload('photo', file, 
                (uploadedFilename) => {
                    // Успешная загрузка
                    this.isUploading = false;
                },
                (error) => {
                    // Ошибка загрузки
                    this.isUploading = false;
                    alert('Ошибка загрузки файла');
                },
                (event) => {
                    // Прогресс загрузки (опционально)
                    // console.log('Прогресс:', event.detail.progress);
                }
            );
        }
    }
}
</script>