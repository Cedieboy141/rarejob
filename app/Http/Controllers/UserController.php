<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\UserServices;

class UserController extends Controller
{

    private $userService;

    public function __construct(UserServices $userService) {
        $this->userService = $userService;
    }
   
    public function create(Request $request)
    {
        $userServiceResponse = $this->userService->create($request);

        return response()->json(
            $userServiceResponse,
            $userServiceResponse['code']
        );
    }

    public function login(Request $request) {
        $userServiceResponse = $this->userService->login($request);

        return response()->json(
            $userServiceResponse,
            $userServiceResponse['code']
        );
    }

}
