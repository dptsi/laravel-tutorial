<?php

namespace App\Modules\Post\Presentation\Components;

use Illuminate\View\Component;

class Alert extends Component
{
    /**
     * message from controller
     * @var string
     */
    public $message;

    /**
     * color from view
     * @var string
     */
    public $color;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($message, $color)
    {
        $this->message = $message;
        $this->color = $color;
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('Post::components.alert');
    }
}
