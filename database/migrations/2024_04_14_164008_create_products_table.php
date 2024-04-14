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
            $table->string('name');
            $table->text('information');
            // 符号なしの数値、マイナス設定なし
            $table->unsignedInteger('price');
            $table->boolean('is_selling');
            // 表示順は設定しない場合もあるため、nullable
            $table->integer('sort_order')->nullable;
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
            $table->foreignId('image2')
            ->nullable()
            ->constrained('images');
            $table->foreignId('image3')
            ->nullable()
            ->constrained('images');
            $table->foreignId('image4')
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
