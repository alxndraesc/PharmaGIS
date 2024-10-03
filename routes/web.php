<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MainProductController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\Auth\RegisteredCustomerController;
use App\Http\Controllers\Auth\RegisteredPharmacyController;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Auth\VerificationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

Route::get('/registeras', function () {
    return view('auth.choose');
})->name('registeras');

Route::get('/register/customer', [RegisteredCustomerController::class, 'create'])->name('register.customer');
Route::post('/register/customer', [RegisteredCustomerController::class, 'store']);

Route::get('/pharmacy/register/step1', [RegisteredPharmacyController::class, 'showStep1Form'])->name('pharmacy.register.step1');
Route::post('/pharmacy/register/step1', [RegisteredPharmacyController::class, 'registerStep1'])->name('pharmacy.register.step1.submit');
Route::get('/pharmacy/register/step2', [RegisteredPharmacyController::class, 'showStep2Form'])->name('pharmacy.register.step2');
Route::post('/pharmacy/register/step2', [RegisteredPharmacyController::class, 'registerStep2'])->name('pharmacy.register.step2.submit');
Route::get('/pharmacy/upload-documents', [RegisteredPharmacyController::class, 'showUploadDocumentsForm'])->name('pharmacy.upload_documents');
Route::post('/pharmacy/upload-documents', [RegisteredPharmacyController::class, 'uploadDocuments'])->name('pharmacy.upload_documents.submit');
Route::get('/pharmacy/resubmit-documents', [PharmacyController::class, 'resubmitDocument'])->name('pharmacy.resubmitDocuments');
Route::post('/pharmacy/update-documents', [PharmacyController::class, 'updateDocuments'])->name('pharmacy.updateDocuments');

Route::get('/email/verify', function () {
    return view('auth.verify');
})->name('verification.notice');

Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
     ->name('verification.verify')
     ->middleware(['signed']);

Route::get('/home', [HomeController::class, 'index']);

Route::get('/admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login']);

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');
    Route::get('/admin/approvals', [AdminController::class, 'pharmacyRequests'])->name('admin.pharmacyRequests');
    Route::post('/admin/approvals/approve/{id}', [AdminController::class, 'approvePharmacy'])->name('admin.approvals.approve');
    Route::delete('/admin/approvals/reject/{id}', [AdminController::class, 'rejectPharmacy'])->name('admin.approvals.reject');
    Route::get('/admin/approvals/document/{id}/{document}', [AdminController::class, 'showDocument'])->name('admin.approvals.document');
    Route::get('/admin/user-management', [AdminController::class, 'userManagement'])->name('admin.userManagement');
    Route::resource('main_products', MainProductController::class);
    Route::get('/admin/products/approve/{id}', [MainProductController::class, 'approveProduct'])->name('admin.approve_product');
    Route::post('admin/approve-multiple-products', [MainProductController::class, 'approveMultipleProducts'])->name('admin.approve_multiple_products');
    Route::get('/admin/settings', [AdminController::class, 'settings'])->name('admin.settings');
    Route::get('/admin/locations', [AdminController::class, 'locationManagement'])->name('admin.locations');
});

Route::middleware(['auth', 'role:pharmacy'])->group(function () {
    Route::get('/pharmacy/upload-documents', [PharmacyController::class, 'handleUploadDocuments'])->name('pharmacy.upload_documents_handler');
    Route::get('/pharmacy/select-role', [PharmacyController::class, 'showSelectRoleForm'])->name('pharmacy.selectRole');
    Route::get('/pharmacy/account/delete-confirm', [PharmacyController::class, 'showDeleteConfirm'])->name('pharmacy.account.delete.confirm');
    Route::post('/pharmacy/set-role', [PharmacyController::class, 'setRole'])->name('pharmacy.setRole');
    Route::post('/pharmacy/confirm-password', [PharmacyController::class, 'confirmPassword'])->name('pharmacy.confirm-password');
    Route::get('/pharmacy/dashboard', [PharmacyController::class, 'dashboard'])->name('pharmacy.dashboard');
    Route::get('/not-approved', [PharmacyController::class, 'notApproved'])->name('pharmacy.not-approved');
    Route::post('/pharmacy/purchase', [PharmacyController::class, 'purchase'])->name('pharmacy.purchase');
    Route::get('/receipt/{id}', [PharmacyController::class, 'showReceipt'])->name('receipt.show');
    Route::get('/receipt/download/{id}', [PharmacyController::class, 'downloadPDF'])->name('receipt.download');
    Route::get('/pharmacy/account', [PharmacyController::class, 'account'])->name('pharmacy.account');
    Route::post('/pharmacy/account', [PharmacyController::class, 'updateAccount'])->name('pharmacy.updateAccount');
    Route::post('/pharmacy/account/delete', [PharmacyController::class, 'deleteAccount'])->name('pharmacy.account.delete');
    Route::post('/pharmacy/change-password', [PharmacyController::class, 'changePassword'])->name('pharmacy.changePassword');
    Route::get('/pharmacy/products', [PharmacyController::class, 'products'])->name('pharmacy.products');
    Route::get('/pharmacy/products/create', [PharmacyController::class, 'createProduct'])->name('pharmacy.createProduct');
    Route::get('/pharmacy/products/{product}/edit', [PharmacyController::class, 'editProduct'])->name('pharmacy.editProduct');
    Route::put('/pharmacy/products/{product}', [PharmacyController::class, 'updateProduct'])->name('pharmacy.updateProduct');
    Route::delete('/pharmacy/products/{product}', [PharmacyController::class, 'deleteProduct'])->name('pharmacy.deleteProduct');
    Route::post('/pharmacy/products', [PharmacyController::class, 'storeProduct'])->name('pharmacy.storeProduct');
    Route::get('/pharmacy/inventory', [PharmacyController::class, 'inventory'])->name('pharmacy.inventory');
    Route::post('/pharmacy/inventory', [PharmacyController::class, 'updateInventory'])->name('pharmacy.updateInventory');
    Route::get('/pharmacy/inventory-history', [PharmacyController::class, 'showInventoryHistory'])->name('pharmacy.inventoryHistory');
    Route::get('/pharmacy/purchase-history', [PharmacyController::class, 'showPurchaseHistory'])->name('pharmacy.purchaseHistory');
    Route::post('/pharmacy/purchase', [PharmacyController::class, 'purchase'])->name('pharmacy.purchase');
    Route::get('/pharmacy/products/search', [PharmacyController::class, 'searchProducts'])->name('pharmacy.searchProducts');
    Route::get('/pharmacy/sales/compute', [PharmacyController::class, 'computeSales'])->name('pharmacy.sales.compute');
    Route::get('/pharmacy/sales', [PharmacyController::class, 'showSales'])->name('pharmacy.sales');
    Route::get('/pharmacy/weekly-sales', [PharmacyController::class, 'showWeeklySales'])->name('pharmacy.weekly-sales');
    Route::get('/pharmacy/monthly-sales', [PharmacyController::class, 'showMonthlySales'])->name('pharmacy.monthly-sales');
    Route::post('pharmacy/products/import', [ProductsController::class, 'import'])->name('products.import');
    Route::get('/pharmacy/products-expiry', [PharmacyController::class, 'productsExpiryStatus'])->name('pharmacy.products-expiry');
    Route::delete('/pharmacy/expired/discard/{id}', [PharmacyController::class, 'discard'])->name('pharmacy.discard');
});  

Route::middleware(['auth', 'role:pharmacy', 'sub_role:employee'])->group(function () {
    Route::get('pharmacy/emp/products', [EmployeeController::class, 'employeeProducts'])->name('pharmacy.employee.products');
    Route::get('pharmacy/emp/products/search', [EmployeeController::class, 'employeeSearch'])->name('pharmacy.emp.searchProducts');
    Route::get('/pharmacy/employee/products-expiry', [EmployeeController::class, 'employeeExpiryStatus'])->name('pharmacy.emp.products-expiry');
});
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/customer/home', [CustomerController::class, 'home'])->name('customer.home');
    Route::post('/customer/search', [CustomerController::class, 'search'])->name('customer.search');
    Route::get('/customer/edit', [CustomerController::class, 'edit'])->name('customer.edit');
    Route::post('/customer/update', [CustomerController::class, 'update'])->name('customer.update');
    Route::post('/customer/change-password', [CustomerController::class, 'changePassword'])->name('customer.changePassword');
    Route::post('/customer/account/delete', [CustomerController::class, 'deleteAccount'])->name('customer.account.delete');
});
Route::resource('categories', CategoryController::class);
Route::post('/categories/{category}/add-product', [CategoryController::class, 'addProduct'])->name('categories.addProduct');
Route::delete('/categories/{category}/remove-product/{product}', [CategoryController::class, 'removeProduct'])->name('categories.removeProduct');
