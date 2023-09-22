<?php

namespace Modules\User\Http\Controllers;

use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\User\Http\Requests\RegistrationRequest;
use Modules\User\Http\Requests\LoginUserRequest;
use Modules\User\Http\Requests\ResetPasswordRequest;
use Modules\User\Http\Services\UserService;

class UserController extends Controller
{
    private $userAbsPath = "";

    private $userRelPath = "";

    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        $this->userAbsPath = public_path('uploads/users');
        $this->userRelPath = 'uploads/users/';
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('user::index');
    }

    /**
     * Store a newly created brand in storage
     *
     * @param RegistrationRequest $request
     * @return JsonResponse
     */
    public function signUp(RegistrationRequest $request): JsonResponse
    {
        $response = $this->userService->signUp($request->validated());

        return response()->json($response);
    }

    /**
     * Sign in request by user.
     *
     * @param LoginUserRequest $request
     * @return JsonResponse
     */
    public function login(LoginUserRequest $request): JsonResponse
    {
        $response = $this->userService->login($request->validated());

        return response()->json($response);
    }

    /**
     * Get User Details
     *
     * @return JsonResponse
     */
    public function getUserDetails(): JsonResponse
    {
        $user = auth()->user();
        $response = $this->userService->getUserDetails($user->id);
        return response()->json($response);
    }

}
