<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zones', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('type')->nullable();
            $table->string('attributes')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['name', 'deleted_at'], 'zones_name_unique');
        });

        // Self-referential pivot
        Schema::create('zone_zone', function (Blueprint $table) {
            $table->unsignedInteger('zone_id');
            $table->unsignedInteger('related_zone_id');
            $table->primary(['zone_id', 'related_zone_id']);
            $table->foreign('zone_id')->references('id')->on('zones')->cascadeOnDelete();
            $table->foreign('related_zone_id')->references('id')->on('zones')->cascadeOnDelete();
        });

        // Pivot with buildings (alphabetical: building < zone)
        Schema::create('building_zone', function (Blueprint $table) {
            $table->unsignedInteger('building_id');
            $table->unsignedInteger('zone_id');
            $table->primary(['building_id', 'zone_id']);
            $table->foreign('building_id')->references('id')->on('buildings')->cascadeOnDelete();
            $table->foreign('zone_id')->references('id')->on('zones')->cascadeOnDelete();
        });

        // Pivot with admin_users (alphabetical: admin_user < zone)
        Schema::create('admin_user_zone', function (Blueprint $table) {
            $table->unsignedInteger('admin_user_id');
            $table->unsignedInteger('zone_id');
            $table->primary(['admin_user_id', 'zone_id']);
            $table->foreign('admin_user_id')->references('id')->on('admin_users')->cascadeOnDelete();
            $table->foreign('zone_id')->references('id')->on('zones')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_user_zone');
        Schema::dropIfExists('building_zone');
        Schema::dropIfExists('zone_zone');
        Schema::dropIfExists('zones');
    }
};
