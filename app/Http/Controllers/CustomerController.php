<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function home()
    {
        return view('customer.home');
    }

    public function search(Request $request)
{
    $request->validate([
        'query' => 'required|string|max:50',
        'age_group' => 'nullable|string',
        'price' => 'nullable|string|in:asc,desc', // Validate sort_by_price input
    ]);

    $query = $request->input('query');
    $ageGroup = $request->input('age_group');
    $sortByPrice = $request->input('price');

    // Initial product search with an inner join to the Inventory table to ensure the product has quantity > 0
    $productsQuery = Product::where(function($q) use ($query) {
            $q->where('brand_name', 'LIKE', "%{$query}%")
              ->orWhere('generic_name', 'LIKE', "%{$query}%");
        })
        ->join('inventory', 'products.id', '=', 'inventory.product_id')
        ->where('inventory.quantity', '>', 0)  // Ensure the product is available (quantity > 0)
        ->when($ageGroup, function ($q) use ($ageGroup) {
            return $q->where('age_group', $ageGroup);
        });

    // Apply price sorting if selected
    if ($sortByPrice) {
        $productsQuery = $productsQuery->orderBy('price', $sortByPrice);
    }

    // Paginate the products query after filtering for availability
    $products = $productsQuery->select('products.*') // Select only product fields after join
        ->paginate(10);

    // If no products are found, find alternatives based on the description field
    $alternativeProducts = collect();
    if ($products->isEmpty()) {
        $alternativeProducts = $this->getAlternativeProducts($query, $ageGroup);
    }

    // Pass the paginated products and filtered available products to the view
    return view('customer.search_results', compact('products', 'alternativeProducts', 'query', 'ageGroup', 'sortByPrice'));
}


private function getAlternativeProducts($query, $ageGroup)
{
    // Find the description of the searched product
    $productDescription = Product::where('brand_name', 'LIKE', "%{$query}%")
        ->orWhere('generic_name', 'LIKE', "%{$query}%")
        ->value('description');

    if ($productDescription) {
        $alternativeProducts = Product::where('description', 'LIKE', "%{$productDescription}%")
            ->where('over_the_counter', true) 
            ->when($ageGroup, function ($q) use ($ageGroup) {
                return $q->where('age_group', $ageGroup);
            })
            ->get();

        $inventoryData = Inventory::select('product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->whereIn('product_id', $alternativeProducts->pluck('id'))
            ->groupBy('product_id')
            ->pluck('total_quantity', 'product_id')
            ->toArray();

        return $alternativeProducts->filter(function($product) use ($inventoryData) {
            return isset($inventoryData[$product->id]) && $inventoryData[$product->id] > 0;
        });
    }

    return collect();
}

    
public function edit()
{
    $user = Auth::user();
    return view('customer.edit', compact('user'));
}

public function update(Request $request)
{
    $user = Auth::user();
    $request->validate([
        'name' => 'required|string|max:30',
    ]);

    $user->name = $request->input('name');
    $user->save();

    return redirect()->route('customer.home')->with('status', 'Account information updated successfully!');
}
public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Check if the current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Update with the new password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password changed successfully!');
    }

public function deleteAccount(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'password' => 'required',
    ]);

    if (!Hash::check($request->password, $user->password)) {
        return back()->withErrors(['password' => 'The provided password does not match our records.']);
    }

    $user->delete();

    Auth::logout();

    return redirect('/')->with('status', 'Your account has been deleted successfully.');
}
}
