<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class IndexController extends Controller
{
    public function show($category, $product)
    {
        $category = Category::finidOrFail($category);
        $product = Product::finidOrFail($product);
        return $category->name. ' - ' . $product->name;
    }

    public function category($category)
    {
        $category = Category::finidOrFail($category);
        return $category->name;
    }
}
