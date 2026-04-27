<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->string('receipt_no')->nullable()->after('user_id');
            $table->decimal('subtotal', 12, 2)->default(0)->after('receipt_no');
            $table->decimal('discount', 12, 2)->default(0)->after('subtotal');
            $table->decimal('tax', 12, 2)->default(0)->after('discount');
            $table->decimal('grand_total', 12, 2)->default(0)->after('tax');
            $table->string('payment_method')->default('cash')->after('grand_total');
            $table->text('note')->nullable()->after('payment_method');
            $table->string('status')->default('completed')->after('note');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'receipt_no', 'subtotal', 'discount', 'tax', 'grand_total', 'payment_method', 'note', 'status']);
        });
    }
};
