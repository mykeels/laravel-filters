<?php

namespace Mykeels\Filters\Tests;

use PHPUnit\Framework\TestCase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Mykeels\Filters\Tests\SubjectTrait;

class BaseFiltersTest extends TestCase 
{
    use SubjectTrait;

    public function setUp()
    {
        $this->subject = $this->subject();
    }


    public function testFilters()
    {
        $filters = $this->subject->filters();
        $this->assertSame($filters, $this->requestParameters);
    }

    public function testRelationQuery()
    {
        $this->subject->add('withFoo', 'baz')->filters();
        $this->subject->apply($this->createMock(Builder::class));
        $mock = $this->createMock(Model::class);
        $mock->expects($this->exactly(1))
            ->method('setAttribute')
            ->with('foo', 'baz');
        $this->subject->transform($mock);
    }

    public function testAddWhenValueOfRequestQueryParameterIsString()
    {
        $filters = $this->subject->add('foo', 'blas')->filters();
        $this->assertSame($filters, ['foo' => 'blas', 'bar'=>'baz']);
    }

    public function testAddWhenValueOfRequestQueryParameterIsNull() 
    {
        $filters = $this->subject->add('foo')->filters();
        $this->assertSame($filters, ['foo' => null, 'bar'=>'baz']);  
    }
}