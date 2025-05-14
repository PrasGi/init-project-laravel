<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasSearchScope
{
    public function scopeSearch(Builder $query, ?string $keyword = null): Builder
    {
        if (!$keyword || !property_exists($this, 'searchable')) {
            return $query;
        }

        return $query->where(function ($q) use ($keyword) {
            foreach ($this->searchable as $field) {
                if (str_contains($field, '.')) {
                    // Relasi: relation.field
                    [$relation, $relationField] = explode('.', $field, 2);
                    $q->orWhereHas($relation, function ($subQuery) use ($relationField, $keyword) {
                        $subQuery->where($relationField, 'like', '%' . $keyword . '%');
                    });
                } else {
                    // Kolom langsung
                    $q->orWhere($field, 'like', '%' . $keyword . '%');
                }
            }
        });
    }
}
