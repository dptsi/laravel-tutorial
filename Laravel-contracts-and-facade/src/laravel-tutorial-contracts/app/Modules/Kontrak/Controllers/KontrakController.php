<?php

namespace App\Modules\Kontrak\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Contracts\BladeMenu;
use App\Menus\FirstMenu;
use App\Menus\SecondMenu;
use App\Menus\NotMenu;

class KontrakController extends Controller
{
    public function menus()
    {
        $menus[] = new FirstMenu();
        $menus[] = new NotMenu();
        $menus[] = new SecondMenu();

        $result = "";
        foreach ($menus as $menu) {
            if ($menu instanceof BladeMenu) {
                $result = $result."{$menu->renderMenu()}<br>";
            }
        }
        return $result;
    }
}
