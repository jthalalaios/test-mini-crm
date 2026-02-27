<?php

namespace App\Helpers;

use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class FunctionsHelper
{
    public static function filters_with_sorting(
        $validated_data,
        string $model,
        string $filter_class,
        $conditions = null,
        $joins = [],
        $conditions_in = null,
        $sorting = true,
        $condition_group = null,
        $conditions_with_operators = null
    ) {
        $filters = $validated_data['filter'] ?? null;
        $items = $validated_data['items'] ?? 10;
        $sort = $validated_data['sort'] ?? null;

        $query = $model::query();
        if (in_array(\Illuminate\Database\Eloquent\SoftDeletes::class, class_uses($model))) $query = $query->withTrashed();

        $query = QueryBuilder::for($query);
        if (!empty($joins)) {
            foreach ($joins as $join) {
                if (isset($join['table'], $join['first'], $join['operator'], $join['second'])) {
                    $query->leftJoin($join['table'], $join['first'], $join['operator'], $join['second']);
                }
            }
        }

        if ($conditions && is_array($conditions)) {
            foreach ($conditions as $column => $value) {
                $query->where($column, $value);
            }
        }

        if ($conditions_with_operators && is_array($conditions_with_operators)) {
            foreach ($conditions_with_operators as $condition) {
                if (is_array($condition) && count($condition) == 3) {
                    [$column, $operator, $value] = $condition;
                    $query->where($column, $operator, $value);
                }
            }
        }

        if ($conditions_in && is_array($conditions_in)) {
            foreach ($conditions_in as $column => $value) {
                $query->whereIn($column, $value);
            }
        }

        if ($condition_group && is_array($condition_group)) {
            foreach ($condition_group as $group) {
                $query->where(function ($q) use ($group) {
                    $group($q);
                });
            }
        }

        if ($filters) {
            $searchable_fields = (new $model())->searchable_fields();
            $allowed_filters = [];

            foreach ($searchable_fields as $field) {
                $allowed_filters[] = AllowedFilter::custom($field, new $filter_class);
            }

            $query->allowedFilters($allowed_filters);
        }

        if ($sorting) {
            if ($sort) {
                foreach ($sort as $field => $direction) {
                    $order = (strpos($field, '-') !== false) ? 'asc' : 'desc';
                    $field_name = ltrim($field, '-');

                    $query = (new $model())->sortable($field_name, $query, $order);
                }
            } else {
                //$query->orderBy('id', 'desc');
                $query->orderBy("{$query->getModel()->getTable()}.id", 'desc');
            }
        }

        return [$query, $items];
    }
}
