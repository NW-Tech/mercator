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
    $response = $this->postJson('/api/backups', [
        'name'              => 'Daily backup',
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
// Relations n-m : logicalServers et storageDevices
// ============================================================

it('links a backup to a logical server and a storage device via API', function () {
    $server = LogicalServer::factory()->create(['name' => 'WebServer']);
    $device = StorageDevice::factory()->create(['name' => 'NAS-01']);

    $response = $this->postJson('/api/backups', [
        'name'               => 'WebServer NAS backup',
        'backup_frequency'   => 1,
        'backup_cycle'       => 1,
        'backup_retention'   => 30,
        'logical_server_ids' => [$server->id],
        'storage_device_ids' => [$device->id],
    ])->assertCreated();

    $backup = Backup::findOrFail($response->json('id'));

    expect($backup->logicalServers->pluck('id')->contains($server->id))->toBeTrue();
    expect($backup->storageDevices->pluck('id')->contains($device->id))->toBeTrue();
});

// ============================================================
// L'update via l'interface admin ne doit pas laisser
// d'anciens enregistrements residuels
// ============================================================

it('replaces backups without leaving residue on admin update', function () {
    $this->actingAs($this->user);

    $logicalServer = LogicalServer::factory()->create(['name' => 'TestServer']);
    $device        = StorageDevice::factory()->create(['name' => 'StorageA']);

    // Backup initial lié via pivot
    $old = Backup::factory()->create(['backup_frequency' => 2]);
    $old->logicalServers()->attach($logicalServer->id);
    $old->storageDevices()->attach($device->id);

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

    // L'ancien enregistrement doit être définitivement supprimé
    $this->assertDatabaseMissing('backups', ['id' => $old->id]);

    // Un seul backup actif doit être lié à ce serveur
    expect($logicalServer->fresh()->backups()->count())->toBe(1);
    expect($logicalServer->fresh()->backups()->first()->backup_frequency)->toBe(1);
});
