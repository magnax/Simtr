<?php

namespace spec;

define('SYSPATH', '');

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class Helper_ViewSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Helper_View');
    }
}
