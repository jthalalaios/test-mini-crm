<?php

namespace App\Http\Filters\Employ;

use App\Helpers\FlexibleFilter;
use Illuminate\Database\Eloquent\Builder;

class FilterEmploy extends FlexibleFilter
{
    protected function applyCustomFilter(Builder $query, string $property, $value, string $mode): bool
    {
        $search_value = strtr($value, "_", " ");
        
        if (is_array($value)) {
            $query->where(function ($q) use ($value, $property, $mode) {
                foreach ($value as $v) {
                    $this->applyStringFilter($q, "employees.$property", strtr($v, "_", " "), $mode);
                }
            });
        } else {
            $this->applyStringFilter($query, "employees.$property", $search_value, $mode);
        }

        return true;
    }
}
