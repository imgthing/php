<?php

namespace Imgthing\Source;

use Imgthing\Extension;

interface SourceInterface extends \Stringable
{
    public ?int $density {
        get;
        set;
    }

    public ?Extension $extension {
        get;
        set;
    }
}
