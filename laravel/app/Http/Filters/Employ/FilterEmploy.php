<?php

namespace App\Http\Filters\Employ;

use App\Helpers\FlexibleFilter;
use Illuminate\Database\Eloquent\Builder;

class FilterEmploy extends FlexibleFilter
{
    protected function applyCustomFilter(Builder $query, string $property, $value, string $mode): bool
    {
        $search_value = strtr($value, "_", " ");

        // Handle global search: OR all searchable fields
        if ($property === 'search') {
            $searchable_fields = (new \App\Models\Employee())->searchable_fields();
            $query->where(function ($q) use ($searchable_fields, $search_value, $mode) {
                foreach ($searchable_fields as $field) {
                    if ($field === 'company_name') {
                        $q->orWhereHas('company', function ($subQ) use ($search_value, $mode) {
                            $this->applyStringFilter($subQ, 'companies.name', $search_value, $mode);
                        });
                    } else {
                        $this->applyStringFilter($q, "employees.$field", $search_value, $mode);
                    }
                }
            });
            return true;
        }

        // Handle company_name per-column search
        if ($property == 'company_name') {
            $query->whereHas('company', function (Builder $q) use ($search_value, $mode) {
                $this->applyStringFilter($q, 'companies.name', $search_value, $mode);
            });
            return true;
        } else {
            $this->applyStringFilter($query, "employees.$property", $search_value, $mode);
            return true;
        }
    }
}
