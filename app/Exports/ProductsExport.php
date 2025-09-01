<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        return Product::with(['latestDetail.category', 'latestDetail.gender'])
    ->where('user_id', auth()->id())
    ->where('is_delete', 0)
    ->get()
    ->map(function ($product) {
        $detail = $product->latestDetail;

        return [
            'Product ID'       => $product->unique_number ?? $product->unique_id,
            'Category'         => $detail->category?->name ?? '-',
            'Gender'           => $detail->gender?->name ?? '-',
            'Age'              => $detail->age . ' ' . $detail?->age_type,
            'Weight'           => $detail->weight,
            'Purchased Amount' => $detail->purchased_amount,
            'Sold Amount'      => $detail->sold_amount ?? '-',
            'Status'           => $product->status_text,
        ];
    });
    }

    public function headings(): array
    {
        return [
            'Product ID',
            'Category',
            'Gender',
            'Age',
            'Weight',
            'Purchased Amount',
            'Sold Amount',
            'Status',
        ];
    }

}
