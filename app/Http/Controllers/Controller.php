<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController; // <-- Asegúrate de que esté esta línea
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

class Controller extends BaseController // <-- Debe extender de BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}