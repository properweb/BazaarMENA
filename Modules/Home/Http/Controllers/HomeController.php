<?php

namespace Modules\Home\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Home\Http\Services\HomeService;

class HomeController extends Controller
{
    private HomeService $homeService;

    public function __construct(HomeService $homeService)
    {
        $this->homeService = $homeService;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('home::index');
    }

    /**
     * Get All Industries
     *
     * @return JsonResponse
     */
    public function getData(): JsonResponse
    {
        $response = $this->homeService->getData();
        return response()->json($response);
    }

}
