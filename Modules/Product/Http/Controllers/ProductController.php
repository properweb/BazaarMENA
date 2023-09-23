<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Product\Entities\Product;
use Modules\Product\Http\Requests\StoreProductRequest;
use Modules\Product\Http\Requests\UpdateProductRequest;
use Modules\Product\Http\Services\ProductService;
use Illuminate\Http\Request;


class ProductController extends Controller
{
    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Create New Product By logged Brand
     *
     * @param StoreProductRequest $request
     * @return JsonResponse
     */
    public function create(StoreProductRequest $request): JsonResponse
    {
        $user = auth()->user();
        if ($user->cannot('create', Product::class)) {
            return response()->json([
                'res' => false,
                'msg' => 'User is not authorized !',
                'data' => ""
            ]);
        }
        $response = $this->productService->create($request);

        return response()->json($response);
    }

    /**
     * Fetch All Product Respected Logged Brand
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function fetch(Request $request): JsonResponse
    {
        $user = auth()->user();
        if ($user->cannot('viewAny', Product::class)) {
            return response()->json([
                'res' => false,
                'msg' => 'User is not authorized !',
                'data' => ""
            ]);
        }
        $response = $this->productService->fetch($request);

        return response()->json($response);
    }

    /**
     * Product Details for respected product
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function details(Request $request): JsonResponse
    {
        $user = auth()->user();
        $product = Product::where('id', $request->id)->first();
        if ($user->cannot('view', $product)) {
            return response()->json([
                'res' => false,
                'msg' => 'User is not authorized !',
                'data' => ""
            ]);
        }
        $response = $this->productService->details($request);

        return response()->json($response);
    }
}
