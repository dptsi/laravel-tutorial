<?php

namespace App\Modules\Kontrak\Menus;

use App\Contracts\BladeMenu;

class NotMenu implements BladeMenu
{
    public function renderMenu()
    {
        return 'not a menu';
    }
}