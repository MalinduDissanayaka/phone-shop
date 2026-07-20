<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Phone;
use App\Models\Role;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'branches' => Branch::count(),
            'users' => User::count(),
            'roles' => Role::count(),
            'products' => Phone::count(),
        ];

        return view('dashboard', compact('stats'));
    }
}
