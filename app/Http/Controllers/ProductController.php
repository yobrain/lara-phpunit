<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index():View
    {
        $products = Product::paginate(10);
        return view('products.index', ['products'=> $products]);
    }
}
