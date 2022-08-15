<?php

use Foxws\LaravelMultidomain\Helpers\ConsoleOutput;

function consoleOutput(): ConsoleOutput
{
    return app(ConsoleOutput::class);
}
