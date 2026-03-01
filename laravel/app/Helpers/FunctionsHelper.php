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

            // Only use company_name for Employee model
            $is_employee = is_a($model, \App\Models\Employee::class, true);
            $fields_for_search = $is_employee ? $searchable_fields : array_filter($searchable_fields, fn($f) => $f !== 'company_name');

            foreach ($fields_for_search as $field) {
                $allowed_filters[] = AllowedFilter::custom($field, new $filter_class);
            }

            // If global search is present, ignore all per-column filters for searchable fields
            if (isset($filters['search']) && $filters['search']) {
                $filters = ['search' => $filters['search']];
                $query->where(function($q) use ($fields_for_search, $filters, $is_employee) {
                    foreach ($fields_for_search as $field) {
                        if ($is_employee && $field === 'company_name') {
                            $q->orWhereHas('company', function ($subQ) use ($filters) {
                                $subQ->where('companies.name', 'ILIKE', '%' . $filters['search'] . '%');
                            });
                        } else {
                            $q->orWhere($field, 'ILIKE', '%' . $filters['search'] . '%');
                        }
                    }
                });
                // Do NOT call allowedFilters when using OR logic
            } elseif ($filters) {
                // Per-column search: use OR logic for all non-empty searchable fields
                $query->where(function($q) use ($filters, $fields_for_search, $is_employee) {
                    foreach ($filters as $field => $value) {
                        if (in_array($field, $fields_for_search) && $value !== null && $value !== '') {
                            if ($is_employee && $field === 'company_name') {
                                $q->orWhereHas('company', function ($subQ) use ($value) {
                                    $subQ->where('companies.name', 'ILIKE', '%' . $value . '%');
                                });
                            } else {
                                $q->orWhere($field, 'ILIKE', '%' . $value . '%');
                            }
                        }
                    }
                });
                // Do NOT call allowedFilters when using OR logic
            } else {
                // No search: allow Spatie filters for other cases
                $query->allowedFilters($allowed_filters);
            }
        }

        if ($sorting) {
            if ($sort) {
                // Support both array of arrays (DataTables) and Spatie-style array of strings
                if (is_array($sort) && isset($sort[0]['column'])) {
                    foreach ($sort as $sortItem) {
                        $field = $sortItem['column'];
                        $direction = $sortItem['direction'] ?? $sortItem['dir'] ?? 'asc';
                        $query = (new $model())->sortable($field, $query, $direction);
                    }
                } elseif (is_array($sort)) {
                    foreach ($sort as $sortValue) {
                        $direction = (strpos($sortValue, '-') === 0) ? 'desc' : 'asc';
                        $field_name = ltrim($sortValue, '-');
                        $query = (new $model())->sortable($field_name, $query, $direction);
                    }
                } else {
                    // If sort is a string (single field)
                        $direction = (strpos($sort, '-') === 0) ? 'desc' : 'asc';
                        $field_name = ltrim($sort, '-');
                        $query = (new $model())->sortable($field_name, $query, $direction);
                }
            } else {
                $query->orderBy("{$query->getModel()->getTable()}.id", 'desc');
            }
        }

        return [$query, $items];
    }
}
