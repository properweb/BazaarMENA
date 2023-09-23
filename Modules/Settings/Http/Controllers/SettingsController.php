<?php

namespace Modules\Settings\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Laravel\Passport\HasApiTokens;
use Illuminate\Http\JsonResponse;
use Modules\Settings\Http\Requests\ChangePasswordRequest;
use Modules\Settings\Http\Requests\UpdateAccountInfoRequest;
use Modules\Settings\Http\Requests\UpdateCompanyProfileInfoRequest;
use Modules\Settings\Http\Requests\ShippingUpdateOrCreateInfoRequest;
use Modules\Settings\Http\Requests\PaymentTermsRequest;
use Modules\Settings\Http\Requests\MinminOrderRequest;
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
    /**
     * Update info.
     *
     * @param UpdateCompanyProfileInfoRequest $request
     * return JsonResponse
     */
    public function updateCompanyProfileInfo(UpdateCompanyProfileInfoRequest $request): JsonResponse
    {
        $response = $this->settingsService->updateCompanyProfileInfo($request->validated());
        return response()->json($response);
    }

    /**
     * Update Or Create shipping.
     *
     * @param ShippingUpdateOrCreateInfoRequest $request
     * return JsonResponse
     */
    public function shippingUpdateOrCreate(ShippingUpdateOrCreateInfoRequest $request): JsonResponse
    {
        $response = $this->settingsService->shippingUpdateOrCreate($request->validated());
        return response()->json($response);
    }
    /**
     * Update Or Create shipping.
     *
     * @param PaymentTermsRequest $request
     * return JsonResponse
     */
    public function paymentTermsRequest(PaymentTermsRequest $request): JsonResponse
    {
        $response = $this->settingsService->paymentTermsRequest($request->validated());
        return response()->json($response);
    }

    /**
     * Update Or Create shipping.
     *
     * @param MinminOrderRequest $request
     * return JsonResponse
     */
    public function minOrder(MinminOrderRequest $request): JsonResponse
    {
        $response = $this->settingsService->minOrder($request->validated());
        return response()->json($response);
    }
}
