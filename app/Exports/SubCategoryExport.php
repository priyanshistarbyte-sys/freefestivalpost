<?php

namespace App\Exports;

use App\Models\SubCategory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SubCategoryExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return SubCategory::with('category')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Category',
            'Name',
            'Event Date',
            'Label',
            'Label BG',
            'Status',
            'Plan Auto',
            'Notification Quote',
            'Mask',
            'Created At'
        ];
    }

    public function map($subCategory): array
    {
        return [
            $subCategory->id,
            $subCategory->category ? $subCategory->category->title : '',
            $subCategory->mtitle,
            $subCategory->event_date,
            $subCategory->lable,
            $subCategory->lablebg,
            $subCategory->status ? 'Active' : 'Inactive',
            $subCategory->plan_auto ? 'Plan Only' : 'Both',
            $subCategory->noti_quote,
            $subCategory->mask,
            $subCategory->created_at->format('Y-m-d H:i:s')
        ];
    }
}