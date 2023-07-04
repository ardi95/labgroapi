<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

class HomeController extends Controller
{
    public function index() {
        $roleAdmin = app('App\Http\Controllers\GlobalController')->checkRole(1);

        $data['auth'] = User::with(['roles'])->findOrFail(Auth::user()->id);

        if ($roleAdmin) {
            $data['count_user'] = User::count();
        }

        return response()->json($data, 200);
    }
}
