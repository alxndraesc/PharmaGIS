<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Imports\ProductsImport;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\XlsxReader;

class ProductsController extends Controller
{
    public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,csv',
    ]);

    $file = $request->file('file');
    $pharmacyId = Auth::user()->pharmacy->id; // Retrieve the pharmacy ID from the authenticated user

    try {
        $import = new ProductsImport();
        $import->importProductsFromSpreadsheet($file->path(), $pharmacyId);
        return redirect()->back()->with('success', 'Products imported successfully!');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Failed to import products. Error: ' . $e->getMessage());
    }
}

}

