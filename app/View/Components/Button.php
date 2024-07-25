<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Button extends Component
{
    public $label;
    public $icon;
    public $attributes = "";
    public $id = "";

    public function __construct()
    {
        $this->label = $label;
        $this->icon = $icon;
        $this->attributes = $attributes;
        $this->id = $id;
    }

    public function render(): View|Closure|string
    {
        return view('components.button');
    }
}
