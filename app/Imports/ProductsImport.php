<?php
namespace App\Imports;

use App\Models\Product;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class ProductsImport
{
    public function importProductsFromSpreadsheet($filePath, $pharmacyId)
    {
        $reader = new Xlsx();
        $spreadsheet = $reader->load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        
        // Get the entire data from the sheet as an array
        $rows = $sheet->toArray(null, true, true, true);

        $header = array_shift($rows); // Remove and get the header row

        // Mapping of the spreadsheet columns to database fields
        $fieldMap = [
            'Brand Name' => 'brand_name',
            'Generic Name' => 'generic_name',
            'Dosage' => 'dosage',
            'Form' => 'form',
            'Description' => 'description',
            'Price' => 'price',
            'Price Bought' => 'price_bought',
            'Age Group' => 'age_group',
            'Category ID' => 'category_id'
        ];

        foreach ($rows as $row) {
            // Prepare product data with default values
            $data = [
                'brand_name' => 'No Brand',
                'generic_name' => 'No Generic Name',
                'dosage' => 'Not Specified',
                'form' => 'Not Specified',
                'description' => 'No Description',
                'price' => 0.00,
                'price_bought' => 0.00,
                'age_group' => 'General',
                'category_id' => null,
                'pharmacy_id' => $pharmacyId
            ];

            // Map the row data to the correct fields
            foreach ($row as $columnIndex => $cellValue) {
                $headerValue = $header[$columnIndex] ?? null;

                if (isset($fieldMap[$headerValue])) {
                    $field = $fieldMap[$headerValue];
                    $data[$field] = $this->getDefaultValue($cellValue, $data[$field]);
                }
            }

            // Validate and insert/update the product in the database
            $data['price'] = $this->validateNumeric($data['price'], 0.00);
            $data['price_bought'] = $this->validateNumeric($data['price_bought'], 0.00);
            $data['age_group'] = $this->validateEnumValue($data['age_group']);

            // Use updateOrCreate to insert or update the product
            Product::updateOrCreate(
                ['brand_name' => $data['brand_name'], 'pharmacy_id' => $pharmacyId],
                $data
            );
        }
    }

    // Default value helper
    private function getDefaultValue($value, $default)
    {
        return $value !== null && $value !== '' ? $value : $default;
    }

    // Ensure numeric values
    private function validateNumeric($value, $default)
    {
        return is_numeric($value) ? (float) $value : $default;
    }

    // Ensure valid enum value for age group
    private function validateEnumValue($value)
    {
        $allowedValues = ['Children', 'Teen', 'Adult', 'Senior', 'General'];
        return in_array($value, $allowedValues) ? $value : 'General';
    }
}
