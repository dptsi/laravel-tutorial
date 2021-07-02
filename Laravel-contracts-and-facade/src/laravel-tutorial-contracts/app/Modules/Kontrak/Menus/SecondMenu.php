<?php

namespace App\Modules\Kontrak\Menus;

use App\Contracts\BladeMenu;

class SecondMenu implements BladeMenu
{
    public function renderMenu()
    {
        return 'second menu';
    }
}