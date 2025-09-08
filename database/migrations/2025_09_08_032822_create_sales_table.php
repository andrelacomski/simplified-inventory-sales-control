<?php

use App\Enums\SaleStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->double('total_amount');
            $table->double('total_cost');
            $table->double('total_profit');
            $table->enum('status', SaleStatusEnum::cases());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('sales');
    }
};
