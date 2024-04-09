<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TestClassBase extends Component
{
    public $classBaseMessage;
    public $defaultMessage;

    public function __construct($classBaseMessage,$defaultMessage="初期値です。")
    {
        $this->classBaseMessage = $classBaseMessage;
        $this->defaultMessage = $defaultMessage;
    }

    public function render(): View|Closure|string
    {
        return view('components.tests.test-class-base-component');
    }
}
