<?php

namespace App\Http\Filters\V1;

class UserFilter extends QueryFilter
{
    protected array $sortable = [
        'email',
        'name',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at',
    ];

    public function include($value)
    {
        $this->builder->with($value);
    }

    // localhost/api/v1/users?filter[id]=11,12
    public function id($value)
    {
        $values = explode(',', $value);

        return $this->builder->whereIn('id', $values);
    }

    // localhost/api/v1/users?filter[email]=*x*
    public function email($value)
    {
        $likeStr = str_replace('*', '%', $value);

        return $this->builder->where('email', 'like', $likeStr);
    }

    // localhost/api/v1/users?[name]=*x*
    public function name($value)
    {
        $likeStr = str_replace('*', '%', $value);

        return $this->builder->where('name', 'like', $likeStr);
    }

    public function createdAt($value)
    {
        $dates = explode(',', $value);

        if (count($dates) > 1) {
            return $this->builder->whereBetween('created_at', $dates);
        }

        return $this->builder->whereDate('created_at', $dates);
    }

    public function updateAt($value)
    {
        $dates = explode(',', $value);

        if (count($dates) > 1) {
            return $this->builder->whereBetween('updated_at', $dates);
        }

        return $this->builder->whereDate('updated_at', $dates);
    }
}
