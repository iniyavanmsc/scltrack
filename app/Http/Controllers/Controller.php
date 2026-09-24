<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class Controller
{
    protected function currentAdminAndDriverId(Request $request): ?int
    {
        $userId = $request->session()->get('admin_and_driver_id');

        return $userId ? (int) $userId : null;
    }
}
