<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('domaine_ad_forest_ad', function (Blueprint $table) {
            $table->unsignedInteger('forest_ad_id')->index('forest_ad_id_fk_1492084');
            $table->unsignedInteger('domaine_ad_id')->index('domaine_ad_id_fk_1492084');
        });
    }

    public function down()
    {
        Schema::dropIfExists('domaine_ad_forest_ad');
    }
};
