<?php

namespace App\Modules\Kontrak\Menus;

use App\Contracts\BladeMenu;

class FirstMenu implements BladeMenu
{
    public function renderMenu()
    {
        return 'first menu';
    }
}