<?php

namespace App\Http\Controllers;

use App\Helpers\Utils;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProductController extends Controller {
    public function store(Request $request) {
        $errors = $this->mountValidateRequest($request);

        if (count($errors) > 0) {
            return response()->json(['error' => true, 'errors' => $errors], 422);
        }

        $product = $this->mountProduct($request, new Product());

        $product->save();

        return response()->json($product);
    }

    public function update(Request $request, $id) {
        $errors = $this->mountValidateRequest($request);

        if (count($errors) > 0) {
            return response()->json(['error' => true, 'errors' => $errors], 422);
        }

        $product = Product::where('id', $id)->whereNull('deleted_at')->first();

        if (!$product) {
            return $this->returnNotFound();
        }

        $product = $this->mountProduct($request, $product);

        $product->save();

        return response()->json($product);
    }

    public function get(Request $request, $id) {
        $product = Product::where('id', $id)->whereNull('deleted_at')->first();

        if (!$product) {
            return $this->returnNotFound();
        }

        return response()->json($product);
    }

    public function destroy(Request $request, $id) {
        $product = Product::where('id', $id)->whereNull('deleted_at')->first();

        if (!$product) {
            return $this->returnNotFound();
        }

        $product->deleted_at = Carbon::now();

        $product->save();

        return response(null, 204);
    }

    public function list(Request $request) {
        $columnsToFilter = ['name', 'description', 'sku'];

        $wheres = [
            'deleted_at' => null
        ];

        return response()->json(Utils::createPaginatedResult($request, Product::class, $wheres, $columnsToFilter));
    }

    private function mountValidateRequest(Request $request) {
        return Utils::validateRequest($request, [
            'cost_price' => 'required|numeric',
            'sale_price' => 'required|numeric',
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:255',
            'description' => 'nullable|string'
        ]);
    }

    private function mountProduct(Request $request, Product $product) {
        $product->sku = $request->sku;
        $product->name = $request->name;
        $product->description = $request->description;
        $product->cost_price = $request->cost_price;
        $product->sale_price = $request->sale_price;

        return $product;
    }

    private function returnNotFound() {
        return response()->json(['error' => true, 'errors' => ['Produto não encontrado.']], 404);
    }
}
