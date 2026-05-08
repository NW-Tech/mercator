<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('domaine_ads', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->longText('description')->nullable();
            $table->integer('domain_ctrl_cnt')->nullable();
            $table->integer('user_count')->nullable();
            $table->integer('machine_count')->nullable();
            $table->string('relation_inter_domaine')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['name', 'deleted_at'], 'domaine_ads_name_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('domaine_ads');
    }
};
