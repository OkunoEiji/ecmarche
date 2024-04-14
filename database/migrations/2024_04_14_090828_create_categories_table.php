<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('primary_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('sort_order');
            $table->timestamps();
        });

        Schema::create('secondary_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('sort_order');
            // 今回はカテゴリーの削除予定ないため、->onUpdate('cascade')、->onDelete('cascade')はなし
            $table->foreignId('primary_category_id')
            ->constrained();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // 外部キー制約がかかっているので、secondaryを先に消す。
        Schema::dropIfExists('secondary_categories');
        Schema::dropIfExists('primary_categories');
    }
};
