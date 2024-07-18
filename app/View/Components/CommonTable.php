<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CommonTable extends Component
{
    public $th;
    public $id;
    public $commonArr;
    public $type;
    public function __construct()
    {
       $this->th = $th;
       $this->id = $id;
       $this->commonArr = $commonArr;
       $this->type = $type;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.common-table');
    }
}
