<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\ErrorResponder\ErrorResponder;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function login(LoginRequest $request, ErrorResponder $errorResponder)
    {
        return 'hello';
    }
}
