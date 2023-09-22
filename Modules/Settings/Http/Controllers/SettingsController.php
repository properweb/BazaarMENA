<?php

namespace Modules\Settings\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Laravel\Passport\HasApiTokens;
use Illuminate\Http\JsonResponse;
use Modules\Settings\Http\Requests\ChangePasswordRequest;
use Modules\Settings\Http\Requests\UpdateAccountInfoRequest;
use Modules\Settings\Http\Services\SettingsService;

class SettingsController extends Controller
{
    private $userAbsPath = "";

    private $userRelPath = "";

    private SettingsService $settingsService;

    public function __construct(SettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
        $this->userAbsPath = public_path('uploads/users');
        $this->userRelPath = 'uploads/users/';
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('settings::index');
    }

    /**
     * Update Password for specific brand.
     *
     * @param ChangePasswordRequest $request
     * return JsonResponse
     */
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $response = $this->settingsService->changePassword($request->validated());
        return response()->json($response);
    }

    /**
     * Update info.
     *
     * @param UpdateAccountInfoRequest $request
     * return JsonResponse
     */
    public function updateInfo(UpdateAccountInfoRequest $request): JsonResponse
    {
        $response = $this->settingsService->updateInfo($request->validated());
        return response()->json($response);
    }
}
