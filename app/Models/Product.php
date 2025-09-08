<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model {
    protected $table = "products";

    protected $fillable = [
        'sku',
        'name',
        'description',
        'cost_price',
        'sale_price',
        'deleted_at'
    ];

    public function inventory() {
        return $this->hasOne(Inventory::class, 'product_id');
    }

    public function saleItems() {
        return $this->hasMany(SaleItem::class, 'product_id');
    }
}
