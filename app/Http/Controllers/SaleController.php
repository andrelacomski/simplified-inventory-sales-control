<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class SaleController extends Controller {
    public function store(Request $request) {
        // TODO
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

        foreach ($sale->saleItem as $saleItem) {
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
}
