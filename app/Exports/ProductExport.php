<?php

namespace App\Exports;

use App\Models\ProductDetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ProductExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $productId;

    public function __construct($productId)
    {
        $this->productId = $productId;
    }

    public function collection()
    {
        return ProductDetail::with(['category', 'gender', 'product'])
            ->where('product_id', $this->productId)
            ->where('is_delete', 0)
            ->get()
            ->map(function ($detail) {
                return [
                    'Product ID'       => $detail->product->unique_number ?? $detail->product->unique_id,
                    'Category'         => $detail->category?->name ?? '-',
                    'Gender'           => $detail->gender?->name ?? '-',
                    'Age'              => $detail->age . ' ' . $detail->age_type,
                    'Weight'           => $detail->weight,
                    'Purchased Amount' => $detail->purchased_amount ?? '-',
                    'Sold Amount'      => $detail->sold_amount ?? '-',
                    'Status'           => $detail->product->status_text,
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
