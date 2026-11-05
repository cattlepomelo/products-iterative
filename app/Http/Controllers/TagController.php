<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TagController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'query' => ['required', 'string', 'min:1', 'max:50'],
        ]);

        $tags = Tag::query()
            ->where('name', 'like', '%'.$validated['query'].'%')
            ->orderBy('name')
            ->limit(10)
            ->get(['id', 'name']);

        return response()->json(['tags' => $tags]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'tags' => ['sometimes', 'array'],
            'tags.*' => ['required', 'string', 'max:50', 'distinct:ignore_case'],
        ]);

        DB::transaction(function () use ($product, $validated): void {
            $tags = collect($validated['tags'] ?? [])
                ->map(fn (string $name): Tag => Tag::firstOrCreate(['name' => trim($name)]));

            $product->tags()->sync($tags->pluck('id')->all());
        });

        return redirect()->route('products.show', $product)
            ->with('status', 'Produkta birkas atjauninātas.');
    }
}
