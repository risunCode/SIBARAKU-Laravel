<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\Category;

class CategoryObserver
{
    public function created(Category $category): void
    {
        ActivityLog::log(
            'created',
            "Membuat kategori '{$category->name}'",
            $category
        );
    }

    public function updated(Category $category): void
    {
        ActivityLog::log(
            'updated',
            "Memperbarui kategori '{$category->name}'",
            $category
        );
    }

    public function deleted(Category $category): void
    {
        ActivityLog::log(
            'deleted',
            "Menghapus kategori '{$category->name}'"
        );
    }

    public function restored(Category $category): void
    {
        ActivityLog::log(
            'restored',
            "Memulihkan kategori '{$category->name}'",
            $category
        );
    }
}
