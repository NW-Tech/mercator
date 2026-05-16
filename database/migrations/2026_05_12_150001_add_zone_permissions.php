<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Permission::query()->count() === 0) {
            return;
        }

        Permission::query()->insert([
            ['id' => 325, 'title' => 'zone_create'],
            ['id' => 326, 'title' => 'zone_edit'],
            ['id' => 327, 'title' => 'zone_show'],
            ['id' => 328, 'title' => 'zone_delete'],
            ['id' => 329, 'title' => 'zone_access'],
        ]);

        $adminId = DB::table('roles')->where('title', 'Admin')->value('id');
        if ($adminId) {
            Role::query()->findOrFail($adminId)->permissions()->syncWithoutDetaching([325, 326, 327, 328, 329]);
        }
    }

    public function down(): void
    {
        DB::table('permission_role')->whereIn('permission_id', [325, 326, 327, 328, 329])->delete();
        DB::table('permissions')->whereIn('id', [325, 326, 327, 328, 329])->delete();
    }
};
