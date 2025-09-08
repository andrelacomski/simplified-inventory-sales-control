<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model {
    protected $table = "inventory";

    protected $fillable = [
        'product_id',
        'quantity',
    ];

    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
