<?php

namespace Mykeels\Filters\Tests;

use Mykeels\Filters\BaseFilters;

class FakeBaseFilters extends BaseFilters
{
    public function foo($value) 
    {  
        $this->builder->where('foo', '=', $value);
    }

    public function bar($value)
    {
        $this->builder->where('bar', '=', $value);
    }

    public function baz($value)
    {
        $this->builder->where('baz', '=', $value);
    }

    public function withFoo($value)
    {
        $this->defer(function ($obj) use ($value) {
            $obj->setAttribute('foo', $value);
            return $obj;
        });
    }
}