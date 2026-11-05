<?php

namespace App\Http\Controllers;

use App\Models\Product;
use DomainException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        return view('products.index', [
            'products' => Product::query()->latest()->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('products.create', [
            'product' => new Product,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $product = Product::create($this->validatedData($request));

        return redirect()->route('products.show', $product)
            ->with('status', 'Produkts veiksmīgi izveidots.');
    }

    public function show(Product $product): View
    {
        $product->load('tags');

        return view('products.show', compact('product'));
    }

    public function updateQuantity(Request $request, Product $product): JsonResponse
    {
        $validated = $request->validate([
            'direction' => ['required', 'in:increase,decrease'],
            'amount' => ['required', 'integer', 'min:1', 'max:1000000'],
        ]);

        try {
            $updatedProduct = DB::transaction(function () use ($product, $validated): Product {
                $lockedProduct = Product::query()->lockForUpdate()->findOrFail($product->id);

                if ($validated['direction'] === 'increase') {
                    $lockedProduct->increaseQuantity($validated['amount']);
                } else {
                    $lockedProduct->decreaseQuantity($validated['amount']);
                }

                $lockedProduct->save();

                return $lockedProduct;
            });
        } catch (DomainException $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }

        return response()->json([
            'quantity' => $updatedProduct->quantity,
            'message' => 'Produkta daudzums atjaunināts.',
        ]);
    }

    public function edit(Product $product): View
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $product->update($this->validatedData($request));

        return redirect()->route('products.show', $product)
            ->with('status', 'Produkts veiksmīgi atjaunināts.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('status', 'Produkts veiksmīgi dzēsts.');
    }

    /**
     * @return array{name: string, quantity: int|string, description: ?string, expiration_date: ?string, status: string}
     */
    private function validatedData(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:0', 'max:4294967295'],
            'description' => ['nullable', 'string'],
            'expiration_date' => ['nullable', 'date'],
            'status' => ['required', 'string', 'max:255'],
        ]);
    }
}
