<?php

namespace Mykeels\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class BaseFilters
{
    protected $request;
    protected $builder;
    protected $functions;
    protected $globals;
  
    /** 
     * @param \Illuminate\Http\Request $request
    */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->functions = new Collection();
        $this->globals = [];
    }
  
    /** 
     * Applies respective filter methods present in the subclass 
     * corresponding to request query parameters.
     * 
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string $extraFilters
     * @return \Illuminate\Database\Eloquent\Builder
    */
    public function apply(Builder $builder, $extraFilters = null):Builder
    {
        $this->builder = $builder;

        $filters = $extraFilters ? array_merge($this->filters(), $extraFilters) : $this->filters();
        
        foreach ($filters as $name => $value) {
            if (! method_exists($this, $name)) {
                continue;
            }
            if (strlen($value)) {
                $this->$name($value);
            } else {
                $this->$name();
            }
        }
        return $this->builder;
    }
  
    /** 
     * Get filters from request query parameters.
     * @return array
    */
    public function filters():array
    {
        return array_merge($this->request->all(), $this->globals);
    }

    protected function defer($function)
    {
        $this->functions->push($function);
        return $this;
    }

    public function transform($model)
    {
        return $this->functions->reduce(function ($model, $function) {
            return $function($model);
        }, $model);
    }

    public function add($name, $value = null)
    {
        $this->globals[$name] = $value;
        return $this;
    }
}
