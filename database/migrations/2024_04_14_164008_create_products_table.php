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
        Schema::create('products', function (Blueprint $table) {
            // 親キーを削除したときに合わせて削除するか
            $table->id();
            // ownerを消したときに、shopの消えるので、productも消える
            $table->foreignId('shop_id')
            ->constrained()
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->foreignId('secondary_category_id')
            ->constrained();
            // image1では、どのモデルかを判断できない。->constrained()で指定する。
            $table->foreignId('image1')
            ->nullable()
            ->constrained('images');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
