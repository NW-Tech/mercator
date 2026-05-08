<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('m_application_events', function (Blueprint $table): void {
            $table->dropForeign('m_application_events_m_application_id_foreign');
        });

        Schema::rename('m_application_events', 'application_events');

        Schema::table('application_events', function (Blueprint $table): void {
            $table->foreign('m_application_id', 'application_events_m_application_id_foreign')
                ->references('id')->on('applications');
        });
    }

    public function down(): void
    {
        Schema::table('application_events', function (Blueprint $table): void {
            $table->dropForeign('application_events_m_application_id_foreign');
        });

        Schema::rename('application_events', 'm_application_events');

        Schema::table('m_application_events', function (Blueprint $table): void {
            $table->foreign('m_application_id', 'm_application_events_m_application_id_foreign')
                ->references('id')->on('applications');
        });
    }
};
