<?php

namespace Modules\Selection\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Selection\Http\Services\SelectionService;

class SelectionController extends Controller
{
    private SelectionService $selectionService;
    public function __construct(SelectionService $selectionService)
    {
        $this->selectionService = $selectionService;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('selection::index');
    }
    /**
     * Get All Industries
     *
     * @return JsonResponse
     */
    public function getIndustries(): JsonResponse
    {
        $response = $this->selectionService->getIndustries();
        return response()->json($response);
    }
    /**
     * Get Industry By id
     * @param int $id
     * @return JsonResponse
     */
    public function getIndustryById(int $id): JsonResponse
    {
        $response = $this->selectionService->getIndustryById($id);
        return response()->json($response);
    }
    /**
     * Get All Categories
     *
     * @return JsonResponse
     */
    public function getCategories(): JsonResponse
    {
        $response = $this->selectionService->getCategories();
        return response()->json($response);
    }
    /**
     * Get Category By id
     * @param int $id
     * @return JsonResponse
     */
    public function getCategoryById(int $id): JsonResponse
    {
        $response = $this->selectionService->getCategoryById($id);
        return response()->json($response);
    }
    /**
     * Get All Countries
     *
     * @return JsonResponse
     */
    public function getCountries(): JsonResponse
    {
        $response = $this->selectionService->getCountries();
        return response()->json($response);
    }

    /**
     * Get Country By id
     * @param int $id
     * @return JsonResponse
     */
    public function getCountryById(int $id): JsonResponse
    {
        $response = $this->selectionService->getCountryById($id);
        return response()->json($response);
    }

    /**
     * Get All State
     * @param int $country_id
     * @return JsonResponse
     */
    public function getState(int $country_id): JsonResponse
    {
        $response = $this->selectionService->getState($country_id);
        return response()->json($response);
    }

    /**
     * Get State By id
     * @param int $id
     * @return JsonResponse
     */
    public function getStateById(int $id): JsonResponse
    {
        $response = $this->selectionService->getStateById($id);
        return response()->json($response);
    }

    /**
     * Get All City
     * @param int $state_id
     * @return JsonResponse
     */
    public function getCity(int $state_id): JsonResponse
    {
        $response = $this->selectionService->getCity($state_id);
        return response()->json($response);
    }

    /**
     * Get City By id
     * @param int $id
     * @return JsonResponse
     */
    public function getCityById(int $id): JsonResponse
    {
        $response = $this->selectionService->getCityById($id);
        return response()->json($response);
    }


}
