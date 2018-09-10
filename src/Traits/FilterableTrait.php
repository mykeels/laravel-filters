<?php

namespace Mykeels\Filters\Traits;

use Mykeels\Filters\BaseFilters;
use Illuminate\Database\Eloquent\Builder;

trait FilterableTrait
{
    /**
     * Applies filters to the scoped query
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Mykeels\Filters\BaseFilters $filters
     * @param string $extraFilters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, BaseFilters $filters, $extraFilters = null)
    {
        return $filters->apply($query, $extraFilters);
    }
}
