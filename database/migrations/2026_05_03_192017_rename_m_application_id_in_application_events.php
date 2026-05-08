<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('application_events', function (Blueprint $table): void {
            $table->dropForeign('application_events_m_application_id_foreign');
            $table->renameColumn('m_application_id', 'application_id');
            $table->foreign('application_id', 'application_events_application_id_foreign')
                ->references('id')->on('applications');
        });
    }

    public function down(): void
    {
        Schema::table('application_events', function (Blueprint $table): void {
            $table->dropForeign('application_events_application_id_foreign');
            $table->renameColumn('application_id', 'm_application_id');
            $table->foreign('m_application_id', 'application_events_m_application_id_foreign')
                ->references('id')->on('applications');
        });
    }
};