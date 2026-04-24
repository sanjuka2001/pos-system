<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->decimal('subtotal', 12, 2)->default(0)->after('user_id');
            $table->decimal('tax', 12, 2)->default(0)->after('subtotal');
            $table->decimal('total', 12, 2)->default(0)->after('tax');
            $table->decimal('amount_received', 12, 2)->default(0)->after('total');
            $table->decimal('change_amount', 12, 2)->default(0)->after('amount_received');
            $table->string('payment_method')->default('cash')->after('change_amount');
            $table->string('status')->default('completed')->after('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn([
                'user_id', 'subtotal', 'tax', 'total',
                'amount_received', 'change_amount', 'payment_method', 'status',
            ]);
        });
    }
};
