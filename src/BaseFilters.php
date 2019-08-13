<?php

namespace Mykeels\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class BaseFilters
{
    /**
     * @var \Illuminate\Http\Request
    */
    protected $request;

    /**
     * @var \Illuminate\Database\Eloquent\Builder
    */
    protected $builder;

    /**
     * @var \Illuminate\Support\Collection
    */
    protected $functions;

    /**
     * @var array
     *
     * Used to store the name and values for filters
     * computed from fields and values in request parameters
     * or added programmatically.
     * The keys of this array corresponds to methods declared in
     * a subclass of this class.
    */
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
     * Applies respective filter methods declared in the subclass
     * that correspond to fields in request query parameters.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param array $extraFilters
     * @return \Illuminate\Database\Eloquent\Builder
    */
    public function apply(Builder $builder, array $extraFilters = null):Builder
    {
        $this->builder = $builder;

        $filters = $extraFilters ? array_merge($this->filters(), $extraFilters) : $this->filters();
        
        foreach ($filters as $name => $value) {
            if (! method_exists($this, $name)) {
                continue;
            }
            if (isset($value)) {
                $this->$name($value);
            } else {
                $this->$name();
            }
        }
        return $this->builder;
    }
  
    /**
     * Gets filters from request query parameters.
     *
     * @return array
    */
    public function filters():array
    {
        return array_merge($this->request->all(), $this->globals);
    }

    /**
     * Registers queries for relations.
     *
     * @param \Closure $function
     * @return $this
    */
    protected function defer(\Closure $function)
    {
        $this->functions->push($function);
        return $this;
    }


    /** Decorates \Illuminate\Database\Eloquent\Model with
     * query results from registered queries
     * for one or more model relations.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return \Illuminate\Database\Eloquent\Model
    */
    public function transform(Model $model)
    {
        return $this->functions->reduce(function ($model, $function) {
            return $function($model);
        }, $model);
    }

    /**
     * Adds a filter programmatically
     *
     * @param string $name
     * @param string $value|null
     * @return $this
    */
    public function add(string $name, ?string $value = null)
    {
        $this->globals[$name] = $value;
        return $this;
    }

    public function request() {
        return $this->request;
    }
}
