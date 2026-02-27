<?php

namespace App\Helpers;

class Constants
{
    public const FILE_PATHS = [
        'base_image_path' => '/uploads/images',
    ];   

    public const FILTER_MODES = [
        'starts_with' => 'startsWith',
        'ends_with' => 'endsWith',
        'contains' => 'contains',
        'equals' => 'equals',
        'lt' => 'lt',
        'lte' => 'lte',
        'gt' => 'gt',
        'gte' => 'gte',
        'less_than' => 'lessThan',
        'less_than_or_equal' => 'lessThanOrEqual',
        'greater_than' => 'greaterThan',
        'greater_than_or_equal' => 'greaterThanOrEqual',
    ];
}
