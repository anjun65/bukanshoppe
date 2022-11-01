<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Product;

class ProductList extends Component
{
    public function render()
    {
        $items = Product::with(['galleries'])->latest()->take(8)->get();

        return view('livewire.product-list',[
            'items' => $items,
        ]);
    }
}
