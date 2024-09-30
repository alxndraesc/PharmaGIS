<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('pharmacy_id', auth()->user()->pharmacy->id)
            ->with('products')  // Eager load products
            ->get();
        $products = Product::where('pharmacy_id', auth()->user()->pharmacy->id)->get(); // Get all existing products for assignment
        return view('categories.index', compact('categories', 'products'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:30',
        ]);
    
        // Create new category
        $category = new Category([
            'name' => $request->input('name'),
            'pharmacy_id' => auth()->user()->pharmacy->id,
        ]);
        $category->save();
    
        // Assign existing products to this new category
        if ($request->has('products')) {
            foreach ($request->input('products') as $productId) {
                $product = Product::find($productId);
                if ($product) {
                    $product->update(['category_id' => $category->id]);
                }
            }
        }
    
        return redirect()->route('categories.index')->with('success', 'Category created and products assigned successfully.');
    }
    
    public function edit(Category $category)
    {
        // Get all products for assigning to this category
        $products = Product::where('pharmacy_id', auth()->user()->pharmacy->id)->get();
        return view('categories.edit', compact('category', 'products'));
    }
    
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:30|unique:categories,name,' . $category->id,
        ]);
    
        // Update category name
        $category->update($request->only('name'));
    
        // Update product assignments
        if ($request->has('products')) {
            $category->products()->update(['category_id' => null]); // Reset previous assignments
            foreach ($request->input('products') as $productId) {
                $product = Product::find($productId);
                if ($product) {
                    $product->update(['category_id' => $category->id]);
                }
            }
        }
    
        return redirect()->route('categories.index')->with('success', 'Category updated and products reassigned successfully.');
    }
    
    public function destroy(Category $category)
    {
        $category->products()->update(['category_id' => null]); // Detach products
        $category->delete();
    
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }

    public function addProduct(Request $request, $categoryId)
{
    $category = Category::find($categoryId);
    $product = Product::find($request->product_id);

    // Associate the product with the category
    $product->category_id = $categoryId;
    $product->save();

    return redirect()->route('categories.index')->with('success', 'Product added successfully.');
}

// Remove product from category
public function removeProduct($categoryId, $productId)
{
    $product = Product::find($productId);

    // Disassociate the product from the category
    $product->category_id = null;
    $product->save();

    return redirect()->route('categories.index')->with('success', 'Product removed successfully.');
}
    
}
