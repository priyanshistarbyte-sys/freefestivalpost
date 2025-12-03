<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\SubCategory;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SubCategoryImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        $category = Category::where('title', $row['category'])->first();
        
        return new SubCategory([
            'category_id' => $category ? $category->id : null,
            'mtitle' => $row['name'],
            'mslug' => SubCategory::slug_string($row['name']),
            'event_date' => $row['event_date'] ?? null,
            'lable' => $row['label'] ?? '',
            'lablebg' => $row['label_bg'] ?? '',
            'status' => $row['status'] === 'Active' ? 1 : 0,
            'plan_auto' => $row['plan_auto'] === 'Plan Only' ? 1 : 0,
            'noti_quote' => $row['notification_quote'] ?? '',
            'mask' => $row['mask'] ?? '',
            'is_parent' => 0,
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'category' => 'required|string',
        ];
    }
}