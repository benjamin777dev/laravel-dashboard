<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Button extends Component
{
    public $label;
    public $icon;
    public $clickEvent ="";
    public $attributes = "";

    public function __construct()
    {
        $this->label = $label;
        $this->icon = $icon;
        $this->clickEvent = $clickEvent;
        $this->attributes = $attributes;
    }

    public function render(): View|Closure|string
    {
        return view('components.button');
    }
}
