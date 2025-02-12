<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create(\config('common.tables.branch'), static function (Blueprint $table): void {
            $table->uuid('id')->primary();

            $table->string('type', 45);
            $table->string('name');

            $table->boolean('is_active');
            $table->boolean('is_head_office');

            $table->uuid('parent_id')->nullable();

            if (! Schema::hasColumn(\config('common.tables.branch'), 'zona')) {
                $table->string('zona')->nullable();
            }

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(\config('common.tables.branch'));
    }
};
