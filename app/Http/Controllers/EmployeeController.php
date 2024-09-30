<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Inventory;

class EmployeeController extends Controller
{
    public function employeeProducts()
    {
        $products = Auth::user()->pharmacy->products;

        $inventoryData = Inventory::select('product_id', DB::raw('SUM(quantity) as total_quantity'))
        ->whereIn('product_id', $products->pluck('id'))
        ->groupBy('product_id')
        ->pluck('total_quantity', 'product_id')
        ->toArray();
        return view('pharmacy.employee.products', compact('products', 'inventoryData'));
    }
   
    public function employeeSearch(Request $request)
{
    $query = $request->input('query');
    $pharmacy = Auth::user()->pharmacy;

    // Initial product search
    $products = $pharmacy->products()
        ->where(function ($q) use ($query) {
            $q->where('product_name', 'LIKE', "%{$query}%")
              ->orWhere('brand_name', 'LIKE', "%{$query}%")
              ->orWhere('generic_name', 'LIKE', "%{$query}%")
              ->orWhere('dosage', 'LIKE', "%{$query}%")
              ->orWhere('form', 'LIKE', "%{$query}%")
              ->orWhere('description', 'LIKE', "%{$query}%")
              ->orWhere('price', 'LIKE', "%{$query}%")
              ->orWhere('age_group', 'LIKE', "%{$query}%");
        });

    // Get all matched products
    $products = $products->get();

    // Get total inventory quantity for each product (ignoring expiration dates)
    $inventoryData = Inventory::select('product_id', DB::raw('SUM(quantity) as total_quantity'))
        ->whereIn('product_id', $products->pluck('id'))
        ->groupBy('product_id')
        ->pluck('total_quantity', 'product_id')
        ->toArray();

    return view('pharmacy.employee.products', compact('products', 'inventoryData'));
}
    
    public function employeeExpiryStatus()
{
    // Get the current date
    $today = now()->startOfDay();
    $expiringDate = $today->copy()->addDays(30); // Customize the number of days as needed

    // Retrieve products that are expiring within the next 30 days
    $expiringProducts = Inventory::where('pharmacy_id', Auth::user()->pharmacy->id)
        ->where('expiration_date', '>=', $today)
        ->where('expiration_date', '<=', $expiringDate)
        ->get();

    // Retrieve products that have already expired
    $expiredProducts = Inventory::where('pharmacy_id', Auth::user()->pharmacy->id)
        ->where('expiration_date', '<', $today)
        ->get();

    return view('pharmacy.employee.products-expiry', compact('expiringProducts', 'expiredProducts'));
}

}