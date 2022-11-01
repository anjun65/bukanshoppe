<div>
    <h1 class="text-2xl font-semibold text-gray-900">Product</h1>

    <div class="py-4 space-y-4">
        <!-- Top Bar -->
        <div class="flex justify-between">
            <div class="w-2/4 flex space-x-4">
                <x-input.text wire:model="filters.name" placeholder="Search Product..." />

                <x-button.link wire:click="toggleShowFilters">@if ($showFilters) Hide @endif Advanced Search...</x-button.link>
            </div>

            <div class="space-x-2 flex items-center">
                <x-input.group borderless paddingless for="perPage" label="Per Page">
                    <x-input.select wire:model="perPage" id="perPage">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </x-input.select>
                </x-input.group>

                <x-dropdown label="Bulk Actions">
                    <x-dropdown.item type="button" wire:click="exportSelected" class="flex items-center space-x-2">
                        <x-icon.download class="text-cool-gray-400"/> <span>Export</span>
                    </x-dropdown.item>

                    <x-dropdown.item type="button" wire:click="$toggle('showDeleteModal')" class="flex items-center space-x-2">
                        <x-icon.trash class="text-cool-gray-400"/> <span>Delete</span>
                    </x-dropdown.item>
                </x-dropdown>

                <livewire:import.product />

                <x-button.primary wire:click="create"><x-icon.plus/> New</x-button.primary>
            </div>
        </div>

        <!-- Advanced Search -->
        <div>
            @if ($showFilters)
            <div class="bg-white p-4 rounded shadow-inner flex relative">
                <div class="w-1/2 pr-2 space-y-4">
                    

                    <x-input.group inline for="filter-rate-min" label="Minimum Rating">
                        <x-input.text wire:model.lazy="filters.rate-min" id="filter-rate-min" />
                    </x-input.group>

                    <x-input.group inline for="filter-rate-max" label="Maximum Rating">
                        <x-input.text wire:model.lazy="filters.rate-max" id="filter-rate-max" />
                    </x-input.group>
                </div>

                <div class="w-1/2 pl-2 space-y-4">
                    <x-input.group inline for="filter-categories" label="Categories">
                        <x-input.select wire:model.lazy="filters.categories" id="filter-categories">
                            @forelse ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @empty
                                <option value="">No Category Exist</option>
                            @endforelse
                        </x-input.select>
                    </x-input.group>
                    
                    <x-button.link wire:click="resetFilters" class="absolute right-0 bottom-0 p-4">Reset Filters</x-button.link>
                </div>
            </div>
            @endif
        </div>

        <!-- Products Table -->
        <div class="flex-col space-y-4">
            <x-table>
                <x-slot name="head">
                    <x-table.heading class="pr-0 w-8">
                        <x-input.checkbox wire:model="selectPage" />
                    </x-table.heading>
                    <x-table.heading sortable multi-column wire:click="sortBy('name')" :direction="$sorts['name'] ?? null" class="w-full">Name</x-table.heading>
                    <x-table.heading sortable multi-column wire:click="sortBy('categories_id')" :direction="$sorts['categories_id'] ?? null">Category</x-table.heading>
                    <x-table.heading sortable multi-column wire:click="sortBy('price')" :direction="$sorts['price'] ?? null">Price</x-table.heading>
                    <x-table.heading sortable multi-column wire:click="sortBy('stock')" :direction="$sorts['slug'] ?? null">Slug</x-table.heading>
                    <x-table.heading sortable multi-column wire:click="sortBy('rating')" :direction="$sorts['rating'] ?? null">Rating</x-table.heading>
                    <x-table.heading sortable multi-column wire:click="sortBy('description')" :direction="$sorts['description'] ?? null">Description</x-table.heading>
                    <x-table.heading />
                </x-slot>

                <x-slot name="body">
                    @if ($selectPage)
                    <x-table.row class="bg-cool-gray-200" wire:key="row-message">
                        <x-table.cell colspan="6">
                            @unless ($selectAll)
                            <div>
                                <span>You have selected <strong>{{ $items->count() }}</strong> data, do you want to select all <strong>{{ $items->total() }}</strong>?</span>
                                <x-button.link wire:click="selectAll" class="ml-1 text-blue-600">Select All</x-button.link>
                            </div>
                            @else
                            <span>You are currently selecting all <strong>{{ $items->total() }}</strong> data.</span>
                            @endif
                        </x-table.cell>
                    </x-table.row>
                    @endif

                    @forelse ($items as $item)
                    <x-table.row wire:loading.class.delay="opacity-50" wire:key="row-{{ $item->id }}">
                        <x-table.cell class="pr-0">
                            <x-input.checkbox wire:model="selected" value="{{ $item->id }}" />
                        </x-table.cell>

                        <x-table.cell>
                            <span class="text-cool-gray-900 font-medium">${{ $item->name }} </span> USD
                        </x-table.cell>

                        <x-table.cell>
                            <span class="text-cool-gray-900 font-medium">${{ $item->category->name }} </span> USD
                        </x-table.cell>

                        <x-table.cell>
                            <span href="#" class="inline-flex space-x-2 truncate text-sm leading-5">
                                <x-icon.cash class="text-cool-gray-400"/>

                                <p class="text-cool-gray-600 truncate">
                                    Rp. {{ $item->price }}
                                </p>
                            </span>
                        </x-table.cell>

                        <x-table.cell>
                            <span class="text-cool-gray-900 font-medium">{{ $item->stock }}</span>
                        </x-table.cell>


                        <x-table.cell>
                            <span class="text-cool-gray-900 font-medium">{{ $item->rating }}</span>
                        </x-table.cell>

                        <x-table.cell>
                            <span class="text-cool-gray-900 font-medium">{{ $item->description }}</span>
                        </x-table.cell>

                        <x-table.cell>
                            <x-button.link wire:click="edit({{ $item->id }})">Edit</x-button.link>
                        </x-table.cell>
                    </x-table.row>
                    @empty
                    <x-table.row>
                        <x-table.cell colspan="8">
                            <div class="flex justify-center items-center space-x-2">
                                <x-icon.inbox class="h-8 w-8 text-cool-gray-400" />
                                <span class="font-medium py-8 text-cool-gray-400 text-xl">No Product found...</span>
                            </div>
                        </x-table.cell>
                    </x-table.row>
                    @endforelse
                </x-slot>
            </x-table>

            <div>
                {{ $items->links() }}
            </div>
        </div>
    </div>

    <!-- Delete Product Modal -->
    <form wire:submit.prevent="deleteSelected">
        <x-modal.confirmation wire:model.defer="showDeleteModal">
            <x-slot name="title">Delete Product</x-slot>

            <x-slot name="content">
                <div class="py-8 text-cool-gray-700">Are you sure you? This action is irreversible.</div>
            </x-slot>

            <x-slot name="footer">
                <x-button.secondary wire:click="$set('showDeleteModal', false)">Cancel</x-button.secondary>

                <x-button.primary type="submit">Delete</x-button.primary>
            </x-slot>
        </x-modal.confirmation>
    </form>

    <!-- Save Product Modal -->
    <form wire:submit.prevent="save">
        <x-modal.dialog wire:model.defer="showEditModal">
            <x-slot name="title">Edit Product</x-slot>

            <x-slot name="content">
                <x-input.group for="name" label="Name" :error="$errors->first('editing.name')">
                    <x-input.text wire:model="editing.name" id="name" placeholder="Name" />
                </x-input.group>

                <x-input.group for="category" label="Category" :error="$errors->first('editing.categories_id')">
                    <x-input.select wire:model="editing.categories_id" id="category">
                        @forelse ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @empty
                            <option value="">No Category Exist</option>
                        @endforelse
                    </x-input.select>
                </x-input.group>

                <x-input.group for="price" label="Price" :error="$errors->first('editing.price')">
                    <x-input.text wire:model="editing.price" id="price" placeholder="Price" />
                </x-input.group>

                <x-input.group for="stock" label="Stock" :error="$errors->first('editing.stock')">
                    <x-input.text wire:model="editing.stock" id="stock" placeholder="Stock" />
                </x-input.group>

                <x-input.group for="description" label="Description" :error="$errors->first('editing.description')">
                    <textarea wire:model="editing.description" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Description ...."></textarea>
                </x-input.group>
            </x-slot>

            <x-slot name="footer">
                <x-button.secondary wire:click="$set('showEditModal', false)">Cancel</x-button.secondary>

                <x-button.primary type="submit">Save</x-button.primary>
            </x-slot>
        </x-modal.dialog>
    </form>
</div>
