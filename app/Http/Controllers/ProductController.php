<?php

namespace App\Http\Controllers;

use App\Repositories\ProductRepository;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Exception;

class ProductController extends Controller
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function index()
    {
        try {
            $products = $this->productRepository->getAllWithFilters();
            return view('products.index', compact('products'));
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to load products: ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(StoreProductRequest $request)
    {
        try {
            $this->productRepository->create($request->validated());

            return redirect()->route('products.index')
                ->with('success', 'Product created successfully.');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create product: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $product = $this->productRepository->findOrFail($id);
            return view('products.show', compact('product'));
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to load product: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $product = $this->productRepository->findOrFail($id);
            return view('products.edit', compact('product'));
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to load product: ' . $e->getMessage());
        }
    }

    public function update(UpdateProductRequest $request, $id)
    {
        try {
            $this->productRepository->update($id, $request->validated());

            return redirect()->route('products.index')
                ->with('success', 'Product updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update product: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->productRepository->delete($id);

            return redirect()->route('products.index')
                ->with('success', 'Product deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete product: ' . $e->getMessage());
        }
    }
}
