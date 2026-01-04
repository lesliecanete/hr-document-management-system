<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Navbar extends Component
{
    public $backgroundColor;
    public $textColor;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($backgroundColor = 'dark', $textColor = 'light')
    {
        $this->backgroundColor = $backgroundColor;
        $this->textColor = $textColor;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.navbar');
    }
}