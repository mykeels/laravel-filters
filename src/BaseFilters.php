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
  
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->functions = new Collection();
        $this->globals = [];
    }
  
    public function apply(Builder $builder):Builder
    {
        $this->builder = $builder;
        foreach ($this->filters() as $name => $value) {
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
