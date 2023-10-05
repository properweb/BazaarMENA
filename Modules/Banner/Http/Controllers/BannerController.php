<?php

namespace Modules\Banner\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Banner\Http\Services\BannerService;
use Modules\Banner\Http\Requests\BannerValidationRequest;

class BannerController extends Controller
{
    private $absPath = "";

    private $relPath = "";

    private BannerService $bannerService;

    public function __construct(BannerService $bannerService)
    {
        $this->bannerService = $bannerService;
        $this->absPath = public_path('uploads/banners');
        $this->relPath = 'uploads/banners/';
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('banner::index');
    }

    /**
     * Add Banner
     *
     * @param BannerValidationRequest $request
     * @return JsonResponse
     */
    public function addBanner(BannerValidationRequest $request): JsonResponse
    {
        $response = $this->bannerService->addBanner($request->validated());

        return response()->json($response);
    }

}
