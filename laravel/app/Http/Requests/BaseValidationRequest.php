<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

abstract class BaseValidationRequest extends FormRequest
{
    abstract protected function tableName(): string;

    public function authorize(): bool
    {
        return true;
    }

    public function get_base_rules(): array
    {
        $table_name = $this->tableName();
        $columns = Schema::getColumnListing($table_name);
        $columns_type = $this->get_column_types($table_name);
        $rules = [];

        foreach ($columns as $column) {
            $type = $columns_type[$column];

            $rules[$column] = match ($type) {
                'integer', 'bigint', 'smallint', 'mediumint' => ['integer'],
                'boolean' => ['boolean'],
                'string', 'char', 'text' => ['string', 'max:255'],
                'date', 'datetime', 'timestamp' => ['date'],
                'decimal', 'float', 'double' => ['numeric'],
                'json' => ['json'],
                default => ['nullable'],
            };
        }

        return $rules;
    }

    private function get_column_types(string $table_name): array
    {
        $columns = DB::select("
            SELECT column_name, data_type
            FROM information_schema.columns
            WHERE table_schema = 'public' AND table_name = ?
        ", [$table_name]);

        $columns_type = [];
        foreach ($columns as $column) {
            $columns_type[$column->column_name] = $column->data_type;
        }

        return $columns_type;
    }
}