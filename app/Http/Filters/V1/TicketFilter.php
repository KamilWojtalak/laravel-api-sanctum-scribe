<?php

namespace App\Http\Filters\V1;

class TicketFilter extends QueryFilter
{
    protected array $sortable = [
        'title',
        'status',
        // casting w method 'sort' do odpowiedniej column
        'createdAt' => 'created_at'
    ];

    public function include($value)
    {
        $this->builder->with($value);
    }

    // localhost/api/v1/tickets?include=user&filter[status]=A,X&filter[title]=*x*
    public function status($value)
    {
        $values = explode(',', $value);

        return $this->builder->whereIn('status', $values);
    }

    // localhost/api/v1/tickets?include=user&filter[status]=A,X&filter[title]=*x*
    public function title($value)
    {
        $likeStr = str_replace('*', '%', $value);

        return $this->builder->where('title', 'like', $likeStr);
    }

    public function createdAt($value)
    {
        $dates = explode(',', $value);

        if (count($dates) > 1)
        {
            return $this->builder->whereBetween('created_at', $dates);
        }

        return $this->builder->whereDate('created_at', $dates);
    }

    public function updateAt($value)
    {
        $dates = explode(',', $value);

        if (count($dates) > 1)
        {
            return $this->builder->whereBetween('updated_at', $dates);
        }

        return $this->builder->whereDate('updated_at', $dates);
    }
}
