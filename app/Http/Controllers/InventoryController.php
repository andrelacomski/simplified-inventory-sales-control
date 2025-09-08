<?php

namespace App\Http\Controllers;

use App\Helpers\Utils;
use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class InventoryController extends Controller {
    public function store(Request $request) {
        $errors = Utils::validateRequest($request, [
            'inventory' => 'required|array|min:1',
            'inventory.*.product_id' => 'required|exists:products,id',
            'inventory.*.quantity' => 'required|numeric',
            'inventory.*.cost_price' => 'nullable|numeric',
            'inventory.*.sale_price' => 'nullable|numeric'
        ]);

        if (count($errors) > 0) {
            return response()->json(['error' => true, 'errors' => $errors], 422);
        }

        foreach ($request->inventory as $item) {
            $inventory = $this->createOrUpdateInventory($item['product_id'], $item['quantity']);

            $inventory->load('product');

            $this->updateProductPrice(
                $inventory->product,
                isset($item['cost_price']) ? $item['cost_price'] : null,
                isset($item['sale_price']) ? $item['sale_price'] : null
            );
        }

        return response(null, 204);
    }

    public function get(Request $request) {
        Paginator::currentPageResolver(function () use ($request) {
            return $request->page;
        });

        $result = Inventory::with('product')->paginate($request->rows);

        $result->getCollection()->transform(function ($inventory) {
            return $this->mountInventoryResponse($inventory);
        });

        return $result;
    }

    private function createOrUpdateInventory($productId, $quantity) {
        $inventory = Inventory::with('product')->where('product_id', $productId)->first();

        if (!$inventory) {
            $inventory = new Inventory();
            $inventory->product_id = $productId;
        }

        $inventory->quantity = $quantity;

        $inventory->save();

        return $inventory;
    }

    private function updateProductPrice(Product $product, $costPrice = null, $salePrice = null) {
        if ($costPrice) {
            $product->cost_price = $costPrice;
        }

        if ($salePrice) {
            $product->sale_price = $salePrice;
        }

        $product->save();
    }

    private function mountInventoryResponse(Inventory $inventory) {
        $unitCost = $inventory->product->cost_price;
        $unitPrice = $inventory->product->sale_price;
        $quantity = $inventory->quantity;

        return [
            'name' => $inventory->product->name,
            'quantity' => $inventory->quantity,
            'unit_cost' => $unitCost,
            'unit_price' => $unitPrice,
            'unit_profit' => $unitPrice - $unitCost,
            'total_amount' => $unitPrice * $quantity,
            'total_cost' => $unitCost * $quantity,
            'total_profit' => ($unitPrice - $unitCost) * $quantity,
            'last_updated' => $inventory->updated_at
        ];
    }
}
