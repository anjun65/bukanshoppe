<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Http\Livewire\DataTable\WithSorting;
use App\Http\Livewire\DataTable\WithCachedRows;
use App\Http\Livewire\DataTable\WithBulkActions;
use App\Http\Livewire\DataTable\WithPerPagePagination;

use App\Models\Product as ProductModel;
use App\Models\Categories;
use Illuminate\Support\Facades\Auth;

class Product extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows;

    public $showDeleteModal = false;
    public $showEditModal = false;
    public $showFilters = false;
    public $filters = [
        'name' => '',
        'categories' => '',
        'rate-min' => null,
        'rate-max' => null,
    ];

    public ProductModel $editing;

    protected $queryString = ['sorts'];

    protected $listeners = ['refreshTransactions' => '$refresh'];

    public function rules() { return [
        'editing.name' => 'required',
        'editing.categories_id' => 'required',
        'editing.price' => 'required',
        'editing.description' => 'required',
        'editing.stock' => 'required',
        'editing.stock' => 'required',
        'editing.rating' => 'required',
    ]; }

    public function mount() { $this->editing = $this->makeBlankTransaction(); }
    public function updatedFilters() { $this->resetPage(); }

    public function exportSelected()
    {
        return response()->streamDownload(function () {
            echo $this->selectedRowsQuery->toCsv();
        }, 'products.csv');
    }

    public function deleteSelected()
    {
        $deleteCount = $this->selectedRowsQuery->count();

        $this->selectedRowsQuery->delete();

        $this->showDeleteModal = false;

        $this->notify('You\'ve deleted '.$deleteCount.' data');
    }

    public function makeBlankTransaction()
    {
        return ProductModel::make();
    }

    public function toggleShowFilters()
    {
        $this->useCachedRows();

        $this->showFilters = ! $this->showFilters;
    }

    public function create()
    {
        $this->useCachedRows();

        if ($this->editing->getKey()) $this->editing = $this->makeBlankTransaction();

        $this->showEditModal = true;
    }

    public function edit(ProductModel $transaction)
    {
        $this->useCachedRows();

        if ($this->editing->isNot($transaction)) $this->editing = $transaction;

        $this->showEditModal = true;
    }

    public function save()
    {
        $this->validate();

        $this->editing->save();

        $this->showEditModal = false;
    }

    public function resetFilters() { $this->reset('filters'); }

    public function getRowsQueryProperty()
    {
        $query = ProductModel::query()
            ->when($this->filters['categories'], fn($query, $categories) => $query->where('categories_id', $categories))
            ->when($this->filters['rate-min'], fn($query, $rate) => $query->where('rating', '>=', $rate))
            ->when($this->filters['rate-max'], fn($query, $rate) => $query->where('rating', '<=', $rate))
            ->when($this->filters['name'], fn($query, $name) => $query->where('name', 'like', '%'.$name.'%'));

        return $this->applySorting($query);
    }

    public function getRowsProperty()
    {
        return $this->cache(function () {
            return $this->applyPagination($this->rowsQuery);
        });
    }

    public function render()
    {
        $categories = Categories::all();

        return view('livewire.admin.product', [
            'items' => $this->rows,
            'categories' => $categories,
        ]);
    }
}
