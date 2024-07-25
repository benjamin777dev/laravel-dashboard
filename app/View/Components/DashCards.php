<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DashCards extends Component
{
    public $stageData;
    public function __construct()
    {
        $this->stageData = $stageData;
    }

    public function render(): View|Closure|string
    {
        return view('components.dash-cards');
    }
}
