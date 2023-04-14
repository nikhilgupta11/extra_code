<?php

namespace App\Http\Controllers;

if (backpack_pro()) {
    class ChartController extends \Backpack\Pro\Http\Controllers\ChartController
    {
    }
}
