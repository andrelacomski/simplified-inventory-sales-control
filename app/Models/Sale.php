<?php

namespace App\Models;

use App\Enums\SaleStatusEnum;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model {
    protected $table = "sales";

    protected $fillable = [
        'total_amount',
        'total_cost',
        'total_profit',
        'status'
    ];

    protected $casts = [
        'status' => SaleStatusEnum::class
    ];

    public function saleItems() {
        return $this->hasMany(SaleItem::class, 'sale_id');
    }
}
