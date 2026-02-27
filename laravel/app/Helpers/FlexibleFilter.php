<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class FlexibleFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        if (is_array($value)) {
            $val = $value['value'] ?? null;
            $mode = $value['matchMode'] ?? 'contains';
        } else {
            $val = $value;
            $mode = 'contains';
        }

        if ($val == null || $val == '') {
            return;
        }

        // Call a method that can be overridden in child classes
        if (!$this->applyCustomFilter($query, $property, $val, $mode)) {
            $this->applyGenericFilter($query, $property, $val, $mode);
        }
    }

    /**
     * Can be overridden by child classes to handle specific fields
     * Return true if the filter was handled, false to fallback to generic
     */
    protected function applyCustomFilter(Builder $query, string $property, $value, string $mode): bool
    {
        return false; // default: no custom logic
    }

    protected function applyGenericFilter(Builder $query, string $property, $value, string $mode)
    {
        // Detect if value is numeric
        if (is_numeric($value)) {
            $this->applyNumericFilter($query, $property, floatval($value), $mode);
        } else {
            $this->applyStringFilter($query, $property, $value, $mode);
        }
    }

    protected function applyStringFilter(Builder $query, string $column, $value, string $mode)
    {
        switch ($mode) {
            case Constants::FILTER_MODES['starts_with']:
                $query->where($column, 'ILIKE', "$value%");
                break;
            case Constants::FILTER_MODES['ends_with']:
                $query->where($column, 'ILIKE', "%$value");
                break;
            case Constants::FILTER_MODES['equals']:
                $query->where($column, '=', $value);
                break;
            case Constants::FILTER_MODES['contains']:
            default:
                $query->where($column, 'ILIKE', "%$value%");
        }
    }

    protected function applyNumericFilter(Builder $query, string $column, float $value, string $mode)
    {
        switch ($mode) {
            case Constants::FILTER_MODES['lt']:
            case Constants::FILTER_MODES['less_than']:
                $query->where($column, '<', $value);
                break;

            case Constants::FILTER_MODES['lte']:
            case Constants::FILTER_MODES['less_than_or_equal']:
                $query->where($column, '<=', $value);
                break;

            case Constants::FILTER_MODES['gt']:
            case Constants::FILTER_MODES['greater_than']:
                $query->where($column, '>', $value);
                break;

            case Constants::FILTER_MODES['gte']:
            case Constants::FILTER_MODES['greater_than_or_equal']:
                $query->where($column, '>=', $value);
                break;

            case Constants::FILTER_MODES['equals']:
            default:
                $query->where($column, '=', $value);
        }
    }
}
