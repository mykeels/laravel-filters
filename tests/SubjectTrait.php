<?php

namespace Mykeels\Filters\Tests;

use Illuminate\Http\Request;
use Mykeels\Filters\Tests\FakeBaseFilters;


trait SubjectTrait {
    private $requestParameters = ['foo' => 'bar', 'bar' => 'baz'];

    private function subject()
    {
        $stub = $this->createMock(Request::class);

        $stub->expects($this->any())->method('all')
            ->willReturn($this->requestParameters);
        
        return new FakeBaseFilters($stub);
    }
}
