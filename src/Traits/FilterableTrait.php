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
     * @param array $extraFilters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, BaseFilters $filters, array $extraFilters = null)
    {
        return $filters->apply($query, $extraFilters);
    }
}
