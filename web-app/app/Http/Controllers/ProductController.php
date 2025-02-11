<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\View\View;

class ProductController extends Controller
{
    //displays all products
    public function index() {

        return view('products.index', ['products' => Product::paginate(3)]);
    }

    //displays page for an individual item
    public function show(string $id) {

        $product = Product::find($id);
        return view('products.show', ['product' => $product]);
    }
}
