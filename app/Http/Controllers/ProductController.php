<?php

namespace App\Http\Controllers;

use App\Interfaces\ProductRepository;
use App\Models\Product;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        return Inertia::render('Product/Product', [
            'products' => $this->productRepository->products(),
        ]);
    }

}
