<?php

namespace App\Filters\Schedules;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class ProviderFilter implements Filter
{
    public function __invoke(Builder $query, mixed $value, string $property)
    {
        
    }
}