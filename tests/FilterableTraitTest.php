<?php

namespace Mykeels\Filters\Tests;

use PHPUnit\Framework\TestCase;
use Illuminate\Database\Eloquent\Builder;

use Mykeels\Filters\Traits\FilterableTrait;
use Mykeels\Filters\Tests\SubjectTrait;


class FilterableTraitTest extends TestCase 
{
    use SubjectTrait;

    public function setUp() {
        $this->subject = $this->subject();
    }

    public function testScopeFilterMethod()
    {
        $mockTrait = $this->getMockForTrait(FilterableTrait::class);
        $mockQuery = $this->createMock(Builder::class);

        $mockQuery->expects($this->exactly(2))
        ->method('where')
        ->withConsecutive(
            [$this->equalTo('foo'), $this->equalTo('='), $this->equalTo('bar'), $this->equalTo('and')],
            [$this->equalTo('bar'), $this->equalTo('='), $this->equalTo('baz'), $this->equalTo('and')]
        );

        $result = $mockTrait->scopeFilter($mockQuery, $this->subject);
    }

    public function testScopeFilterWithExtraFiltersMethod()
    {
        $mockTrait = $this->getMockForTrait(FilterableTrait::class);
        $mockQuery = $this->createMock(Builder::class);

        $mockQuery->expects($this->exactly(3))
        ->method('where')
        ->withConsecutive(
            [$this->equalTo('foo'), $this->equalTo('='), $this->equalTo('bar'), $this->equalTo('and')],
            [$this->equalTo('bar'), $this->equalTo('='), $this->equalTo('baz'), $this->equalTo('and')],
            [$this->equalTo('baz'), $this->equalTo('='), $this->equalTo('bas'), $this->equalTo('and')]
        );

        $result = $mockTrait->scopeFilter($mockQuery, $this->subject, ['baz' => 'bas']);
        
    }
}