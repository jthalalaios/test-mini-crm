<?php

namespace App\Http\Filters\Company;

use App\Helpers\FlexibleFilter;
use Illuminate\Database\Eloquent\Builder;

class FilterCompany extends FlexibleFilter
{
    protected function applyCustomFilter(Builder $query, string $property, $value, string $mode): bool
    {
        $search_value = strtr($value, "_", " ");
        $table = $query->getModel()->getTable();

        if (is_array($value)) {
            $query->where(function ($q) use ($value, $property, $mode, $table) {
                foreach ($value as $v) {
                    $this->applyStringFilter($q, "$table.$property", strtr($v, "_", " "), $mode);
                }
            });
        } else {
            $this->applyStringFilter($query, "$table.$property", $search_value, $mode);
        }

        // Log the SQL for debugging
        \Log::info('Company filter SQL: ' . $query->toSql(), $query->getBindings());

        return true;
    }
}
