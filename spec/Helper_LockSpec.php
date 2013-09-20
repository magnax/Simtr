<?php

namespace spec;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class Helper_LockSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Helper_Lock');
    }
}
