<?php

namespace App\Http\Controllers\AppManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Role;

class RoleController extends Controller
{
    public function temp() {
        $role = Role::get();

        return response()->json($role, 200);
    }
}
