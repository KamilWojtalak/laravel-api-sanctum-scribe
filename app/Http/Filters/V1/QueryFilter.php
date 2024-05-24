<?php

namespace App\Http\Filters\V1;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class QueryFilter
{
    protected Builder $builder;

    protected array $sortable = [];

    public function __construct(protected Request $request)
    {
    }

    protected function filter($arr)
    {
        foreach ($arr as $key => $value) {
            if (method_exists($this, $key)) {
                $this->$key($value);
            }
        }
    }

    public function apply(Builder $builder)
    {
        $this->builder = $builder;

        // Ale ciekawy syntax, do zapamiętania
        foreach ($this->request->all() as $key => $value) {

            // Sprawdzasz czy twój QueryFilter ma tą method
            if (method_exists($this, $key)) {
                $this->$key($value);
            }
        }

        return $builder;
    }

    // localhost/api/v1/tickets?filter[id]=1,2&sort=title,status
    // localhost/api/v1/tickets?filter[id]=1,2&sort=-title,status
    protected function sort($value)
    {
        $values = explode(',', $value);

        foreach ($values as $column) {
            $direction = 'asc';

            if (strpos($column, '-') === 0) {
                $direction = 'desc';
                $column = substr($column, 1);
            }

            if (!in_array($column, $this->sortable) && !array_key_exists($column, $this->sortable))
            {
                continue;
            }

            $column = $this->sortable[$column] ?? $column;

            $this->builder->orderBy($column, $direction);
        }
    }
}
