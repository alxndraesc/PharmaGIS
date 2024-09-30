<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MainProduct;
use App\Models\Product;

class MainProductController extends Controller
{
    // Display the list of main products
    public function index()
    {
        // Get paginated list of approved main products
        $mainProducts = MainProduct::paginate(10);

        // Fetch the unapproved products (assumed by null 'general_id')
        $unapprovedProducts = Product::whereNull('general_id')->paginate(10);

        return view('admin.main_products', compact('mainProducts', 'unapprovedProducts'));
    }
    
    // Show the form to create a new main product
    public function create()
    {
        return view('admin.main_products_create');
    }

    // Store the new main product
    public function store(Request $request)
    {
        $request->validate([
            'brand_name' => 'required|string|max:255',
            'generic_name' => 'required|string|max:255',
            'dosage' => 'required|string|max:255',
            'form' => 'required|string|max:255',
            'age_group' => 'required|in:Children,Teen,Adult,Senior,General',
            'over_the_counter' => 'boolean',
        ]);

        // Find the max existing general_id, default to 10000000 if none exists
        $generalId = MainProduct::max('general_id') ?? 10000000;  // Start from 10000000
        $generalId++;  // Increment for the new product

        MainProduct::create([
            'general_id' => $generalId,
            'brand_name' => $request->brand_name,
            'generic_name' => $request->generic_name,
            'dosage' => $request->dosage,
            'form' => $request->form,
            'age_group' => $request->age_group,
            'over_the_counter' => $request->over_the_counter,
        ]);

        return redirect()->route('main_products.index')->with('success', 'Product added to the centralized list successfully!');
    }

    // Approve a product
    public function approveProduct($id)
    {
        // Find the product added by the pharmacy
        $product = Product::findOrFail($id);

        // Check if a corresponding main product exists with the same attributes
        $mainProduct = MainProduct::where('brand_name', $product->brand_name)
            ->where('generic_name', $product->generic_name)
            ->where('dosage', $product->dosage)
            ->where('form', $product->form)
            ->where('age_group', $product->age_group)
            ->where('over_the_counter', $product->over_the_counter)
            ->first();

        if (!$mainProduct) {
            // Create new main product entry if it does not exist
            $mainProduct = MainProduct::create([
                'brand_name' => $product->brand_name,
                'generic_name' => $product->generic_name,
                'dosage' => $product->dosage,
                'form' => $product->form,
                'age_group' => $product->age_group,
                'over_the_counter' => $product->over_the_counter,
            ]);
        }

        // Set the general ID on the pharmacy's product to the main product's ID
        $product->general_id = $mainProduct->id;
        $product->save();

        return redirect()->route('main_products.index')->with('success', 'Product approved successfully.');
    }

    // Approve multiple products
    public function approveMultipleProducts(Request $request)
{
    $request->validate([
        'products' => 'required|array', // Ensure products array is passed
        'products.*' => 'exists:products,id', // Ensure each product exists in the database
    ]);

    foreach ($request->input('products') as $productId) {
        // Find the product added by the pharmacy
        $product = Product::findOrFail($productId);

        // Check if a corresponding main product exists with the same attributes
        $mainProduct = MainProduct::where('brand_name', $product->brand_name)
            ->where('generic_name', $product->generic_name)
            ->where('dosage', $product->dosage)
            ->where('form', $product->form)
            ->where('age_group', $product->age_group)
            ->where('over_the_counter', $product->over_the_counter)
            ->first();

        if (!$mainProduct) {
            // Create new main product entry if it does not exist
            $mainProduct = MainProduct::create([
                'brand_name' => $product->brand_name,
                'generic_name' => $product->generic_name,
                'dosage' => $product->dosage,
                'form' => $product->form,
                'age_group' => $product->age_group,
                'over_the_counter' => $product->over_the_counter,
            ]);
        }

        // Set the general ID on the pharmacy's product to the main product's ID
        $product->general_id = $mainProduct->id;
        $product->save();
    }

    return redirect()->back()->with('success', 'Selected products approved successfully!');
}


    // Show the form to edit a main product
    public function edit($id)
    {
        $mainProduct = MainProduct::findOrFail($id);

        return view('admin.main_products_edit', compact('mainProduct'));
    }

    // Update an existing main product
    public function update(Request $request, $id)
    {
        $request->validate([
            'brand_name' => 'required|string|max:255',
            'generic_name' => 'required|string|max:255',
            'dosage' => 'required|string|max:255',
            'form' => 'required|string|max:255',
            'age_group' => 'required|in:Children,Teen,Adult,Senior,General',
            'over_the_counter' => 'boolean',
        ]);

        $mainProduct = MainProduct::findOrFail($id);

        $mainProduct->update([
            'brand_name' => $request->brand_name,
            'generic_name' => $request->generic_name,
            'dosage' => $request->dosage,
            'form' => $request->form,
            'age_group' => $request->age_group,
            'over_the_counter' => $request->over_the_counter,
        ]);

        return redirect()->route('main_products.index')->with('success', 'Product updated successfully!');
    }

    // Delete a main product
    public function destroy($id)
    {
        $mainProduct = MainProduct::findOrFail($id);
        $mainProduct->delete();

        return redirect()->route('main_products.index')->with('success', 'Product deleted successfully!');
    }
}
