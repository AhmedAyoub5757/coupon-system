<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCouponTargetingColumns extends Migration
{
    public function up()
    {
        Schema::table('coupons', function (Blueprint $table) {
            if (!Schema::hasColumn('coupons', 'scope')) {
                $table->enum('scope', ['all', 'category', 'products'])->default('all');
            }
            if (!Schema::hasColumn('coupons', 'category_id')) {
                $table->unsignedBigInteger('category_id')->nullable();
            }
            if (!Schema::hasColumn('coupons', 'product_ids')) {
                $table->json('product_ids')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn(['scope', 'category_id', 'product_ids']);
        });
    }
}