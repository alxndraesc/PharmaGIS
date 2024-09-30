<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Pharmacy;
use App\Models\MainProduct;
use App\Models\User;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Category;
use App\Models\InventoryHistory;
use App\Models\PurchaseHistory;
use App\Models\Sale;

class PharmacyController extends Controller
{
    public function resubmitDocument()
{
    $pharmacy = Auth::user()->pharmacy;

    return view('pharmacy.resubmit', compact('pharmacy'));
}

public function updateDocuments(Request $request)
{
    $request->validate([
        'document1' => 'required|file|mimes:pdf,png,jpeg|max:5120',
        'document2' => 'required|file|mimes:pdf,png,jpeg|max:5120',
        'document3' => 'required|file|mimes:pdf,png,jpeg|max:5120',
    ]);

    $pharmacy = Auth::user()->pharmacy;

    // Handle file uploads
    if ($request->hasFile('document1')) {
        // Process and save the file
        $path1 = $request->file('document1')->store('documents', 'public');
        $pharmacy->document1_path = $path1;
    }

    if ($request->hasFile('document2')) {
        // Process and save the file
        $path2 = $request->file('document2')->store('documents', 'public');
        $pharmacy->document2_path = $path2;
    }

    if ($request->hasFile('document3')) {
        // Process and save the file
        $path3 = $request->file('document3')->store('documents', 'public');
        $pharmacy->document3_path = $path3;
    }

    // Reset approval status and review date
    $pharmacy->is_approved = false;  // Reset approval status
    $pharmacy->reviewed_at = null;   // Reset review date to null
    $pharmacy->save();

    return redirect()->route('pharmacy.dashboard')->with('success', 'Documents have been resubmitted successfully and are pending admin review.');
}

public function handleUploadDocuments()
{
    $pharmacy = Auth::user()->pharmacy;

    // Check if the pharmacy has been reviewed
    if (!$pharmacy->is_approved) {
        if ($pharmacy->reviewed_at) {
            // Pharmacy has been rejected, show resubmit documents view
            return view('pharmacy.resubmit');
        } else {
            // Pharmacy not yet approved nor rejected, show not approved view
            return view('pharmacy.not-approved');
        }
    }

    // If approved, redirect to the dashboard
    return redirect()->route('pharmacy.dashboard');
}

public function showDeleteConfirm()
{
    return view('pharmacy.delete-confirm');
}

    //Select sub-role in pharmacy account page 

    public function showSelectRoleForm()
    {
        return view('pharmacy.select-sub-role');
    }

    public function setRole(Request $request)
    {
        $request->validate([
            'sub_role' => 'nullable|in:owner,employee',
        ]);

        $user = Auth::user();
        $user->pharmacy->update(['sub_role' => $request->sub_role]);

        // Redirect based on the selected sub-role
        if ($request->sub_role === 'owner') {
            return redirect()->route('pharmacy.dashboard');
        } else {
            return redirect()->route('pharmacy.dashboard');
        }
    }
    public function switchRole(Request $request)
    {
        $request->validate([
            'sub_role' => 'nullable|in:owner,employee',
        ]);
    
        $user = auth()->user();
        
        // Update the sub-role or set it to null
        $user->pharmacy->sub_role = $request->sub_role; 
        $user->pharmacy->save();
    
        // Redirect based on the new sub-role or to the role selection page
        if ($user->pharmacy->sub_role === 'owner') {
            return redirect()->route('pharmacy.dashboard');
        } elseif ($user->pharmacy->sub_role === 'employee') {
            return redirect()->route('pharmacy.employee.dashboard');
        } else {
            return redirect()->route('pharmacy.selectRole');
    }
    }
    //password confirmation for pharmacy owner to access dashboard

    public function confirmPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string', 'regex:/^[a-zA-Z0-9]*$/',
            'sub_role' => 'required|in:owner,employee',
        ]);

        $user = Auth::user();

        if (Hash::check($request->password, $user->password)) {
            // Update the sub_role
            $user->pharmacy->sub_role = $request->sub_role;
            $user->pharmacy->save();

            // Redirect based on the sub-role after update
            if ($user->pharmacy->sub_role === 'owner') {
                return redirect()->route('pharmacy.dashboard');
            } elseif ($user->pharmacy->sub_role === 'employee') {
                return redirect()->route('pharmacy.dashboard');
            }
        } else {
            return redirect()->route('pharmacy.selectRole')->withErrors(['password' => 'Password is incorrect']);
        }
    }

    // Dashboard - for both owner employee to view

    // is functioning can search for product
    public function dashboard()
{
    $pharmacy = Auth::user()->pharmacy;

    if (!$pharmacy) {
        return redirect()->route('pharmacy.account')->with('error', 'Please complete your pharmacy profile.');
    }

    $pharmacyId = $pharmacy->id;

    $products = Product::leftJoin('inventory', function($join) use ($pharmacyId) {
        $join->on('products.id', '=', 'inventory.product_id')
             ->where('inventory.pharmacy_id', '=', $pharmacyId);
    })
    ->where('products.pharmacy_id', $pharmacyId)
    ->select(
        'products.id as product_id',
        'products.brand_name',
        'products.generic_name',
        'products.price',
        DB::raw('SUM(inventory.quantity) as total_quantity')
    )
    ->groupBy(
        'products.id',
        'products.brand_name',
        'products.generic_name',
        'products.price'
    )
    ->get();

    // Calculate today's total purchases
    $today = now()->format('Y-m-d');
    $totalPurchasesToday = PurchaseHistory::whereDate('purchased_at', $today)
                                          ->where('pharmacy_id', $pharmacyId)
                                          ->sum('total_cost');

    $totalProducts = Product::where('pharmacy_id', $pharmacyId)->count();

    $totalProductsSoldToday = PurchaseHistory::whereDate('purchased_at', $today)
                                             ->where('pharmacy_id', $pharmacyId)
                                             ->sum('quantity');

    $lowInStockCount = $products->where('total_quantity', '<=', 10)->count();  // Low in stock threshold is 10
    $outOfStockCount = $products->where('total_quantity', '<=', 0)->count();

    return view('pharmacy.dashboard', compact('pharmacy', 'products', 'today', 'totalPurchasesToday', 'totalProducts', 'totalProductsSoldToday', 'lowInStockCount', 'outOfStockCount'));
}


    // pending approval page for newly registered pharmacy account

    public function notApproved()
    {
        return view('pharmacy.not-approved');
    }

    // Account Management
    public function account()
    {
        $pharmacy = Auth::user()->pharmacy;
        return view('pharmacy.account', compact('pharmacy'));
    }
    
    protected function geocodeAddressWithMapbox($address)
{
    $client = new Client();
    $accessToken = 'pk.eyJ1IjoiYWx4bmRyYWUiLCJhIjoiY20wM2drNGNhMDd1cjJqcHhzbTV4NXdlaCJ9.KLPS-IT0Y9_IGEwqBRn00A';

    try {
        $response = $client->get('https://api.mapbox.com/geocoding/v5/mapbox.places/' . urlencode($address) . '.json', [
            'query' => [
                'access_token' => $accessToken,
                'limit' => 1
            ]
        ]);

        $data = json_decode($response->getBody(), true);

        if (!empty($data['features'])) {
            return [
                'latitude' => $data['features'][0]['geometry']['coordinates'][1],
                'longitude' => $data['features'][0]['geometry']['coordinates'][0]
            ];
        }

        return null;

    } catch (\Exception $e) {
        \Log::error('Geocoding error: ' . $e->getMessage());
        return null;
    }
}
    
public function updateAccount(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:39',
        'street' => 'required|string|max:60',
        'neighborhood' => 'required|string|max:60',
        'contact_number' => 'required|string|min:11|max:11',
    ]);

    $pharmacy = Auth::user()->pharmacy;

    // Combine street and neighborhood with "Gubat, Sorsogon, Philippines"
    $fullAddress = htmlspecialchars($request->street, ENT_QUOTES, 'UTF-8') . ', ' .
                   htmlspecialchars($request->neighborhood, ENT_QUOTES, 'UTF-8') . ', Gubat, Sorsogon, Philippines';

    // Geocode the updated address using Mapbox
    $geocode = $this->geocodeAddressWithMapbox($fullAddress);
    $latitude = $geocode ? $geocode['latitude'] : null;
    $longitude = $geocode ? $geocode['longitude'] : null;

    $updateData = [
        'name' => htmlspecialchars($request->name, ENT_QUOTES, 'UTF-8'),
        'address' => $fullAddress,
        'contact_number' => htmlspecialchars($request->contact_number, ENT_QUOTES, 'UTF-8'),
        'latitude' => $latitude,
        'longitude' => $longitude,
    ];

    // Update or create the pharmacy information
    if ($pharmacy) {
        $pharmacy->update($updateData);
    } else {
        Auth::user()->pharmacy()->create($updateData);
    }

    return redirect()->route('pharmacy.account')->with('success', 'Account updated successfully.');
}
    

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Check if current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Update with the new password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password changed successfully!');
    }

    public function showDeleteRequestForm()
    {
        return view('pharmacy.account.delete');
    }

    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        // Check if the password matches
        if (!Hash::check($request->password, Auth::user()->password)) {
            return back()->withErrors(['password' => 'The provided password does not match our records.']);
        }

        $user = Auth::user();
        $user->delete();

        Auth::logout();

        return redirect('/')->with('status', 'Your account has been deleted successfully.');
    }

    //Product Management
    public function products(Request $request)
{
    $pharmacy = Auth::user()->pharmacy;

    $products = $pharmacy->products()->paginate(10);
    $categories = $pharmacy->categories; // Fetch categories

    return view('pharmacy.products', compact('products', 'categories'));
}


    public function createProduct()
    {
        $categories = Category::all();
        return view('pharmacy.create-product', compact('categories'));
    }

    public function storeProduct(Request $request)
{
    $request->validate([
        'brand_name' => 'required|string|max:30',
        'generic_name' => 'required|string',
        'dosage' => 'required|string|max:20',
        'form' => 'required|string|max:20',
        'description' => 'required|string',
        'price' => 'required|numeric|min:0',
        'price_bought' => 'required|numeric|min:0',
        'age_group' => 'required|in:Children,Teen,Adult,Senior,General',
        'category_id' => 'nullable|exists:categories,id',
        'quantity' => 'required|integer|min:1',
        'expiration_date' => 'required|date|after:today',
        'over_the_counter' => 'boolean',
    ]);

    // Check if the main product exists based on all relevant fields, excluding product_name
    $mainProduct = MainProduct::where('brand_name', $request->brand_name)
        ->where('generic_name', $request->generic_name)
        ->where('dosage', $request->dosage)
        ->where('form', $request->form)
        ->where('age_group', $request->age_group)
        ->where('over_the_counter', $request->over_the_counter)
        ->first();

    // If the main product exists, set the general_id from the main product
    $generalId = $mainProduct ? $mainProduct->id : null;

    // Create the product data without product_name
    $productData = $request->only([
        'brand_name', 'generic_name', 'dosage', 'form', 
        'description', 'price', 'price_bought', 'age_group', 'over_the_counter'
    ]);
    $productData['category_id'] = $request->input('category_id');
    $productData['general_id'] = $generalId;  // Store the general_id if main product exists

    // Store the product in the products table for the pharmacy
    $product = Auth::user()->pharmacy->products()->create($productData);

    // Create the inventory record for the product
    $inventoryData = [
        'product_id' => $product->id,
        'pharmacy_id' => Auth::user()->pharmacy->id,
        'quantity' => $request->input('quantity'),
        'expiration_date' => $request->input('expiration_date')
    ];
    Inventory::create($inventoryData);

    return redirect()->route('pharmacy.products')->with('success', 'Product added successfully. Awaiting admin approval.');
}

    public function editProduct(Product $product)
    {
        $categories = Category::all();
        return view('pharmacy.edit-product', compact('product', 'categories'));
    }

    public function updateProduct(Request $request, Product $product)
{
    // Validate the incoming request data without product_name
    $request->validate([
        'brand_name' => 'required|string|max:30',
        'generic_name' => 'required|string',
        'dosage' => 'required|string|max:20',
        'form' => 'required|string|max:20',
        'description' => 'required|string',
        'price' => 'required|numeric|min:0',
        'price_bought' => 'required|numeric|min:0',
        'age_group' => 'required|in:Children,Teen,Adult,Senior,General',
        'category_id' => 'nullable|exists:categories,id',
        'over_the_counter' => 'sometimes|boolean',
    ]);

    // Prepare product data
    $productData = $request->only([
        'brand_name',
        'generic_name',
        'dosage',
        'form',
        'description',
        'price',
        'price_bought',
        'age_group',
        'category_id'
    ]);

    // Handle 'over_the_counter'
    $productData['over_the_counter'] = $request->input('over_the_counter', 0);

    // Check for existing main product with the same unique combination
    $mainProduct = MainProduct::where('brand_name', $request->brand_name)
        ->where('generic_name', $request->generic_name)
        ->where('dosage', $request->dosage)
        ->where('form', $request->form)
        ->first();

    // If no main product exists, notify the user but proceed with the update
    if (!$mainProduct) {
        // Log a warning or notify the user as appropriate
        \Log::warning('Main product not found for update:', [
            'brand_name' => $request->brand_name,
            'generic_name' => $request->generic_name,
            'dosage' => $request->dosage,
            'form' => $request->form,
        ]);
        // You might want to redirect or show a warning here
    } else {
        // Assign the general ID from the main product to the product
        $product->general_id = $mainProduct->id;
    }

    // Update the product with the new data
    $product->update($productData);

    return redirect()->route('pharmacy.products')->with('success', 'Product updated successfully.');
}


    public function deleteProduct(Product $product)
    {
        $product->delete();

        return redirect()->route('pharmacy.products')->with('success', 'Product deleted successfully.');
    }
    
    // Inventory Management
    public function inventory()
{
    $pharmacyId = Auth::user()->pharmacy->id;
    $products = Product::where('pharmacy_id', $pharmacyId)->get();
        $inventoryItems = Inventory::where('pharmacy_id', $pharmacyId)
            ->select('product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id')
            ->get();

    // Fetch products with expiration dates for detailed view
        $detailedInventoryItems = Inventory::where('pharmacy_id', $pharmacyId)
            ->with('product')
            ->get();

    return view('pharmacy.inventory', compact('products', 'inventoryItems'));
}

    // is functioning
    public function updateInventory(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:0',
        'status' => 'nullable|in:available,sold out,on order,discontinued',
        'expiration_date' => 'nullable|date|after_or_equal:today',
    ]);

    // Retrieve pharmacy ID of authenticated user
    $pharmacyId = Auth::user()->pharmacy->id;

    // Find the existing inventory record or create a new one with default values
    $inventory = Inventory::firstOrNew(
        ['pharmacy_id' => $pharmacyId, 'product_id' => $request->product_id, 'expiration_date' => $request->expiration_date]
    );

    // Update the quantity by adding the new amount to the current quantity
    $inventory->quantity += $request->quantity;

    // Determine status based on the updated quantity and provided status
    if ($inventory->quantity == 0) {
        $inventory->status = 'sold out';
    } else {
        $inventory->status = $request->status ?: 'available'; // Default to 'available' if no status is provided
    }

    // Save the inventory record
    $inventory->save();

    // Log the inventory update in the history table
    InventoryHistory::create([
        'pharmacy_id' => $pharmacyId,
        'product_id' => $inventory->product_id,
        'quantity' => $inventory->quantity,
        'status' => $inventory->status,
        'updated_at' => now(),
    ]);

    // Redirect back to inventory page with success message
    return redirect()->route('pharmacy.inventory')->with('success', 'Inventory updated successfully.');
}

    //Purchasing
    public function purchase(Request $request)
{
    $request->validate([
        'products' => 'required|array',
        'products.*' => 'exists:products,id',
        'quantities' => 'required|array',
        'quantities.*' => 'required|integer|min:1',
    ]);

    $pharmacy = Auth::user()->pharmacy;
    $pharmacyId = $pharmacy->id;
    $products = $request->input('products');
    $quantities = $request->input('quantities');

    $receiptItems = [];
    $totalCost = 0;
    $receiptNumber = 'REC-' . strtoupper(uniqid()); // Generate a unique receipt number

    // Initialize a variable to hold the ID of the newly created purchase
    $newPurchaseId = null;

    foreach ($products as $index => $productId) {
        $quantity = $quantities[$index];
        $product = Product::findOrFail($productId);
        $price = $product->price;
        $itemTotalCost = $price * $quantity;

        // Log the purchase in the purchase history table
        $purchase = PurchaseHistory::create([
            'pharmacy_id' => $pharmacyId,
            'product_id' => $productId,
            'quantity' => $quantity,
            'price' => $price,
            'total_cost' => $itemTotalCost,
            'purchased_at' => now(),
        ]);

        // Update the $newPurchaseId variable to the latest purchase record's ID
        $newPurchaseId = $purchase->id;

        // Update inventory for the product
        $inventories = Inventory::where('pharmacy_id', $pharmacyId)
            ->where('product_id', $productId)
            ->orderBy('expiration_date', 'asc') // Process oldest first
            ->get();

        $remainingQuantity = $quantity;

        foreach ($inventories as $inventory) {
            if ($remainingQuantity <= 0) break;

            if ($inventory->quantity >= $remainingQuantity) {
                $inventory->quantity -= $remainingQuantity;
                $inventory->status = $inventory->quantity == 0 ? 'sold out' : $inventory->status;
                $inventory->save();
                $remainingQuantity = 0;
            } else {
                $remainingQuantity -= $inventory->quantity;
                $inventory->quantity = 0;
                $inventory->status = 'sold out';
                $inventory->save();
            }
        }

        // Create sales record
        Sale::updateOrCreate(
            [
                'product_id' => $productId,
                'pharmacy_id' => $pharmacyId
            ],
            [
                'quantity' => DB::raw('quantity + ' . $quantity),
                'total_price' => DB::raw('total_price + ' . $itemTotalCost)
            ]
        );

        $receiptItems[] = [
            'name' => $product->brand_name,
            'quantity' => $quantity,
            'price' => $itemTotalCost,
        ];

        $totalCost += $itemTotalCost;
    }

    // Store receipt data in session for retrieval
    session()->flash('receipt', [
        'receiptNumber' => $receiptNumber,
        'pharmacyName' => $pharmacy->name,
        'address' => $pharmacy->address,
        'date' => now()->format('Y-m-d H:i:s'),
        'items' => $receiptItems,
        'totalCost' => $totalCost,
    ]);

    // Redirect to the receipt view with the new purchase ID
    return redirect()->route('receipt.show', ['id' => $newPurchaseId]);
}

public function showReceipt($id)
{
    // Retrieve the purchase history record
    $purchase = PurchaseHistory::with('product', 'pharmacy')->findOrFail($id);
    
    // Prepare receipt data
    $items = [
        [
            'name' => $purchase->product->brand_name,
            'quantity' => $purchase->quantity,
            'price' => $purchase->price,
        ]
    ];
    
    $totalCost = $purchase->total_cost;

    return view('pharmacy.receipt', [
        'receipt' => [
            'date' => $purchase->purchased_at->format('Y-m-d'),
            'pharmacyName' => $purchase->pharmacy->name,
            'address' => $purchase->pharmacy->address,
            'receiptNumber' => $id,
            'items' => $items,
            'totalCost' => $totalCost
        ]
    ]);
}

public function downloadPDF($id)
{
    $purchase = PurchaseHistory::with('product', 'pharmacy')->findOrFail($id);

    $items = [
        [
            'name' => $purchase->product->brand_name,
            'quantity' => $purchase->quantity,
            'price' => $purchase->price,
        ]
    ];

    $totalCost = $purchase->total_cost;

    $pdf = PDF::loadView('receipts.pdf', [
        'date' => $purchase->purchased_at->format('Y-m-d'),
        'pharmacyName' => $purchase->pharmacy->name,
        'address' => $purchase->pharmacy->address,
        'receiptNumber' => $id,
        'items' => $items,
        'totalCost' => $totalCost
    ]);

    return $pdf->download('receipt-' . $id . '.pdf');
}

    public function generatePDF($id)
{
    $purchase = PurchaseHistory::findOrFail($id);
    $pharmacy = $purchase->pharmacy;
    $items = $purchase->items;
    $totalCost = $items->sum('total_cost');

    $pdf = Pdf::loadView('receipts.pdf', [
        'pharmacyName' => $pharmacy->name,
        'pharmacyAddress' => $pharmacy->address,
        'receiptNumber' => $purchase->id,
        'date' => $purchase->created_at->format('Y-m-d'),
        'items' => $items,
        'totalCost' => $totalCost
    ]);

    return $pdf->download('receipt-' . $purchase->id . '.pdf');
}
/**
 * Determine the status based on the quantity.
 * Adjust this method based on your business logic for inventory status determination.
 *
 * @param int $quantity
 * @return string
 */
    private function determineStatus($quantity)
    {
        if ($quantity <= 0) {
            return 'Sold Out';
        } elseif ($quantity < 10) {
            return 'On Order';
        } else {
            return 'Available';
        }
    }

    // Search product in products page
    public function searchProducts(Request $request)
{
    $query = $request->input('query');
    $pharmacy = Auth::user()->pharmacy;

    if ($query) {
        $products = $pharmacy->products()
            ->where(function ($q) use ($query) {
                $q->where('brand_name', 'LIKE', "%{$query}%")
                  ->orWhere('generic_name', 'LIKE', "%{$query}%")
                  ->orWhere('dosage', 'LIKE', "%{$query}%")
                  ->orWhere('form', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%")
                  ->orWhere('price', 'LIKE', "%{$query}%")
                  ->orWhere('age_group', 'LIKE', "%{$query}%");
            })
            ->get();
    } else {
        $products = $pharmacy->products;
    }

    return view('pharmacy.products', compact('products', 'query'));
}


    //Histories

    public function showInventoryHistory()
    {
        $pharmacyId = Auth::user()->pharmacy->id;
        $inventoryHistories = InventoryHistory::where('pharmacy_id', $pharmacyId)->with('product')->get();

        return view('pharmacy.inventory-history', compact('inventoryHistories'));
    }

    public function showPurchaseHistory()
{
    $pharmacy = Auth::user()->pharmacy;

    $pharmacyId = $pharmacy->id;
    $purchaseHistories = PurchaseHistory::where('pharmacy_id', $pharmacyId)
                                        ->with('product')
                                        ->get();

    // Check if purchaseHistories have the expected data
    // Uncomment the following line to debug
    // dd($purchaseHistories);

    // Group purchases by date and calculate total per day
    $purchasesByDate = $purchaseHistories->groupBy(function ($purchase) {
        return $purchase->purchased_at->format('Y-m-d');
    });

    $totalsPerDay = $purchasesByDate->map(function ($purchases, $date) {
        // Ensure 'total_cost' is correctly summed
        return $purchases->sum('total_cost');
    });

    return view('pharmacy.purchase-history', compact('purchaseHistories', 'totalsPerDay'));
}
    // Sales Management
    public function computeSales() 
{
    $pharmacy = Auth::user()->pharmacy;
    $pharmacyId = $pharmacy->id;

    // Aggregate purchase data from purchase histories for the current pharmacy
    $salesData = DB::table('purchase_histories')
        ->join('products', 'purchase_histories.product_id', '=', 'products.id')
        ->select(
            'products.brand_name as brand_name', 
            'purchase_histories.product_id',
            DB::raw('SUM(purchase_histories.quantity) as total_quantity'),
            DB::raw('SUM(purchase_histories.total_cost) as total_price'),
            'products.price_bought' // Include price bought for profit calculation
        )
        ->where('purchase_histories.pharmacy_id', $pharmacyId)
        ->groupBy('brand_name', 'purchase_histories.product_id', 'products.price_bought') 
        ->get();

    // Update or create sales records for the current pharmacy
    foreach ($salesData as $data) {
        // Calculate the profit: (Price Sold - Price Bought) * Quantity
        $profit = ($data->total_price - ($data->price_bought * $data->total_quantity));

        Sale::updateOrCreate(
            [
                'product_id' => $data->product_id,
                'pharmacy_id' => $pharmacyId
            ],
            [
                'quantity' => $data->total_quantity,
                'total_price' => $data->total_price,
                'profit' => $profit, // Save the profit
                'purchased_at' => now()
            ]
        );
    }

    return redirect()->route('pharmacy.sales')->with('success', 'Sales data computed successfully.');
}

public function showSales()
{
    $pharmacy = Auth::user()->pharmacy;
    $pharmacyId = $pharmacy->id;

    // Define current time variables
    $currentDay = now()->format('F j, Y');
    $currentWeek = now()->format('W');
    $currentMonth = now()->format('F Y');

    // Calculate sales totals
    $dailySalesTotal = DB::table('sales')
        ->whereDate('created_at', now()->format('Y-m-d'))
        ->where('pharmacy_id', $pharmacyId)
        ->sum('total_price');

    $weeklySalesTotal = DB::table('sales')
        ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
        ->where('pharmacy_id', $pharmacyId)
        ->sum('total_price');

    $monthlySalesTotal = DB::table('sales')
        ->whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->where('pharmacy_id', $pharmacyId)
        ->sum('total_price');

    // Get product sales and costs
    $productSales = DB::table('sales')
        ->join('products', 'sales.product_id', '=', 'products.id')
        ->select(
            'products.brand_name as brand_name', 
            DB::raw('SUM(sales.quantity) as total_quantity'),
            DB::raw('SUM(sales.total_price) as total_cost')
        )
        ->where('sales.pharmacy_id', $pharmacyId)
        ->groupBy('brand_name') 
        ->orderBy('total_quantity', 'desc')
        ->get();

    // Calculate daily, weekly, and monthly sales for each product
    $dailyProductSales = DB::table('sales')
        ->join('products', 'sales.product_id', '=', 'products.id')
        ->select(
            'products.brand_name as brand_name', 
            DB::raw('SUM(sales.total_price) as daily_sales')
        )
        ->where('sales.pharmacy_id', $pharmacyId)
        ->whereDate('sales.created_at', now()->format('Y-m-d'))
        ->groupBy('brand_name') 
        ->pluck('daily_sales', 'brand_name');

    $weeklyProductSales = DB::table('sales')
        ->join('products', 'sales.product_id', '=', 'products.id')
        ->select(
            'products.brand_name as brand_name', 
            DB::raw('SUM(sales.total_price) as weekly_sales')
        )
        ->where('sales.pharmacy_id', $pharmacyId)
        ->whereBetween('sales.created_at', [now()->startOfWeek(), now()->endOfWeek()])
        ->groupBy('brand_name') 
        ->pluck('weekly_sales', 'brand_name');

    $monthlyProductSales = DB::table('sales')
        ->join('products', 'sales.product_id', '=', 'products.id')
        ->select(
            'products.brand_name as brand_name', 
            DB::raw('SUM(sales.total_price) as monthly_sales')
        )
        ->where('sales.pharmacy_id', $pharmacyId)
        ->whereMonth('sales.created_at', now()->month)
        ->groupBy('brand_name')
        ->pluck('monthly_sales', 'brand_name'); 

    // Calculate monthly profit for each product
    $monthlyProductProfits = DB::table('sales')
        ->join('products', 'sales.product_id', '=', 'products.id')
        ->select(
            'products.brand_name as brand_name', 
            DB::raw('SUM(sales.total_price - products.price_bought * sales.quantity) as monthly_profit')
        )
        ->where('sales.pharmacy_id', $pharmacyId)
        ->whereMonth('sales.created_at', now()->month)
        ->groupBy('brand_name') 
        ->pluck('monthly_profit', 'brand_name'); 

    $productSalesWithProfits = $productSales->map(function ($sale) use ($monthlyProductProfits) {
        $sale->monthly_profit = $monthlyProductProfits[$sale->brand_name] ?? 0; // Add profit to each product
        return $sale;
    });

    // Prepare data for Monthly Profit Chart
    $profitChartLabels = $monthlyProductProfits->keys();
    $profitChartData = $monthlyProductProfits->values();

    // Prepare data for Top Products Chart
    $topProductsChartLabels = $productSales->pluck('brand_name'); 
    $topProductsChartData = $productSales->pluck('total_quantity');

    return view('pharmacy.sales', compact(
        'productSales',
        'currentDay',
        'currentWeek',
        'currentMonth',
        'dailySalesTotal',
        'weeklySalesTotal',
        'monthlySalesTotal',
        'dailyProductSales',
        'weeklyProductSales',
        'monthlyProductSales',
        'monthlyProductProfits',
        'productSalesWithProfits',
        'profitChartLabels',
        'profitChartData',
        'topProductsChartLabels',
        'topProductsChartData'
    ));
}

    //Expiry Management
    public function productsExpiryStatus()
{
    // Get the current date (start of the day)
    $today = now()->startOfDay();
    
    // Set the threshold for expiring products (15 days before expiry)
    $expiringDate = $today->copy()->addDays(15);

    // Retrieve products that are expiring within the next 15 days
    $expiringProducts = Inventory::where('pharmacy_id', Auth::user()->pharmacy->id)
        ->where('expiration_date', '>', $today) // Not expired yet
        ->where('expiration_date', '<=', $expiringDate) // Expiring in the next 15 days
        ->get();

    // Retrieve products that are already expired or expire today
    $expiredProducts = Inventory::where('pharmacy_id', Auth::user()->pharmacy->id)
        ->where('expiration_date', '<=', $today) // Expired today or earlier
        ->get();

    return view('pharmacy.products-expiry', compact('expiringProducts', 'expiredProducts'));
}

}

