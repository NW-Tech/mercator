<?php

use App\Models\Backup;
use App\Models\LogicalServer;
use App\Models\StorageDevice;
use App\Models\User;
use Database\Seeders\PermissionRoleTableSeeder;
use Database\Seeders\PermissionsTableSeeder;
use Database\Seeders\RolesTableSeeder;
use Database\Seeders\RoleUserTableSeeder;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Laravel\Passport\Passport;

uses(RefreshDatabase::class);

beforeEach(function () {
    Cache::forget('permissions_roles_map');

    $this->seed([
        PermissionsTableSeeder::class,
        RolesTableSeeder::class,
        PermissionRoleTableSeeder::class,
        UsersTableSeeder::class,
        RoleUserTableSeeder::class,
    ]);
    $this->user = User::query()->where('login', 'admin@admin.com')->first();
    Passport::actingAs($this->user);
});

// ============================================================
// created_at doit être rempli à la création (API)
// ============================================================

it('fills created_at when a backup is created via API', function () {
    $logicalServer = LogicalServer::factory()->create();
    $storageDevice = StorageDevice::factory()->create();

    $response = $this->postJson('/api/backups', [
        'logical_server_id' => $logicalServer->id,
        'storage_device_id' => $storageDevice->id,
        'backup_frequency'  => 1,
        'backup_cycle'      => 1,
        'backup_retention'  => 30,
    ])->assertCreated();

    $id     = $response->json('id');
    $backup = Backup::find($id);

    expect($backup)->not->toBeNull();
    expect($backup->created_at)->not->toBeNull();
});

it('fills created_at when a backup is created directly', function () {
    $backup = Backup::factory()->create();

    expect($backup->created_at)->not->toBeNull();
});

// ============================================================
// L'update via l'interface admin ne doit pas laisser
// d'anciens enregistrements soft-deletés
// ============================================================

it('replaces backups without leaving soft-deleted residue on admin update', function () {
    $this->actingAs($this->user);

    $logicalServer = LogicalServer::factory()->create(['name' => 'TestServer']);
    $device        = StorageDevice::factory()->create();

    // Backup initial
    $old = Backup::factory()->create([
        'logical_server_id' => $logicalServer->id,
        'storage_device_id' => $device->id,
        'backup_frequency'  => 2,
    ]);

    // Mise à jour du serveur logique (remplace le backup)
    $this->put(route('admin.logical-servers.update', $logicalServer), array_merge(
        $logicalServer->only(['name']),
        [
            'storage_device_id' => [$device->id],
            'backup_frequency'  => [1],
            'backup_cycle'      => [1],
            'backup_retention'  => [30],
        ]
    ))->assertRedirect();

    // L'ancien enregistrement doit être définitivement supprimé (pas soft-deleted)
    $this->assertDatabaseMissing('backups', ['id' => $old->id]);

    // Un seul backup actif doit exister pour ce serveur
    expect(Backup::where('logical_server_id', $logicalServer->id)->count())->toBe(1);
    expect(Backup::where('logical_server_id', $logicalServer->id)->first()->backup_frequency)->toBe(1);
});
