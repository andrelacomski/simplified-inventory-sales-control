<?php

namespace App\Http\Controllers;

use App\Enums\SaleStatusEnum;
use App\Helpers\Utils;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller {
    public function store(Request $request) {
        $errors = Utils::validateRequest($request, [
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:inventory,product_id',
            'products.*.quantity' => 'required|numeric'
        ]);

        if (count($errors) > 0) {
            return response()->json(['error' => true, 'errors' => $errors], 422);
        }

        $sale = $this->initSale();

        $response = $this->processProducts($request, $sale->id);

        $sale = $this->updatePriceSale($sale, $response->total_amount, $response->total_cost);

        if ($response->error) {
            $sale->status = SaleStatusEnum::FAIL;
            $sale->save();

            return response()->json(['error' => true, 'errors' => $response->errors], 422);
        }

        $sale->status = SaleStatusEnum::PAID;

        $sale->save();
        $sale->load('saleItems');

        $this->processQuantityItems($sale->saleItems);

        return response()->json($sale);
    }

    public function get(Request $request, $id) {
        $sale = Sale::with('saleItems.product')->where('id', $id)->first();

        if (!$sale) {
            return response()->json(['error' => true, 'errors' => ['Venda não encontrada.']], 404);
        }

        return response()->json($this->mountSaleResponse($sale));
    }

    private function mountSaleResponse(Sale $sale) {
        $products = [];

        foreach ($sale->saleItems as $saleItem) {
            $products[] = [
                'id' => $saleItem->product_id,
                'name' => $saleItem->product->name,
                'quantity' => $saleItem->quantity,
                'unit_price' => $saleItem->unit_price,
                'unit_cost' => $saleItem->unit_cost,
                'unit_profit' => $saleItem->unit_price - $saleItem->unit_cost
            ];
        }

        return [
            'total_amount' => $sale->total_amount,
            'total_cost' => $sale->total_cost,
            'total_profit' => $sale->total_profit,
            'status' => $sale->status,
            'sale_date' => $sale->created_at,
            'products' => $products
        ];
    }

    private function initSale() {
        $sale = new Sale();

        $sale->total_amount = 0;
        $sale->total_cost = 0;
        $sale->total_profit = 0;
        $sale->status = SaleStatusEnum::PENDING;

        $sale->save();

        return $sale;
    }

    private function updatePriceSale(Sale $sale, $total_amount, $total_cost) {
        $sale->total_amount = $total_amount;
        $sale->total_cost = $total_cost;
        $sale->total_profit = $total_amount - $total_cost;

        $sale->save();

        return $sale;
    }

    private function processProducts(Request $request, $sale_id) {
        $error = false;
        $errors = [];
        $total_amount = 0;
        $total_cost = 0;
        $inventory = [];

        foreach ($request->products as $product) {
            $inventory = Inventory::with('product')->where('product_id', $product['product_id'])->first();

            if ($inventory->quantity < $product['quantity']) {
                $error = true;
                $errors[] = 'Quantidade inválida para o produto: ' . $inventory->product->name;
            }

            $total_amount += $inventory->product->sale_price;
            $total_cost += $inventory->product->cost_price;

            $this->saveProductInSale($inventory->product, $sale_id, $product['quantity']);
        }

        return (object) [
            'error' => $error,
            'errors' => $errors,
            'total_amount' => $total_amount,
            'total_cost' => $total_cost,
            'inventory' => $inventory
        ];
    }

    private function saveProductInSale(Product $product, $sale_id, $quantity) {
        $saleItem = new SaleItem();

        $saleItem->sale_id = $sale_id;
        $saleItem->product_id = $product->id;
        $saleItem->quantity = $quantity;
        $saleItem->unit_price = $product->sale_price;
        $saleItem->unit_cost = $product->cost_price;

        $saleItem->save();
    }

    private function processQuantityItems($saleItems = []) {
        foreach ($saleItems as $saleItem) {
            DB::transaction(function () use ($saleItem) {
                $inventory = Inventory::where('product_id', $saleItem->product_id)->lockForUpdate()->first();

                $inventory->update([
                    'quantity' => $inventory->quantity - $saleItem->quantity,
                ]);
            });
        }
    }
}
