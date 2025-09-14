<?php

namespace ImageProxy\Source;

use ImageProxy\Extension;

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
