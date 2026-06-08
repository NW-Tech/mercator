@extends('layouts.admin')

@section('title')
    {{ trans('global.test') }}
@endsection

@section('content')
<div class="card">
    <div class="card-body">

    Fuseau horaire : {{ config('app.timezone') }}<br>
    Langue : {{ config('app.locale') }}<br>

    panel.date_format = {{ config('panel.date_format') }} <br>
    panel.time_format = {{ config('panel.time_format') }}

    <hr>

    <?php
    // --- Environnement applicatif ---
    $memoryUsed  = round(memory_get_usage(true) / 1048576, 1);
    $memoryPeak  = round(memory_get_peak_usage(true) / 1048576, 1);
    $memoryLimit = ini_get('memory_limit');

    $diskFreeBytes  = disk_free_space(base_path());
    $diskTotalBytes = disk_total_space(base_path());
    $diskFreeGb     = $diskFreeBytes  !== false ? round($diskFreeBytes  / 1073741824, 1) : null;
    $diskTotalGb    = $diskTotalBytes !== false ? round($diskTotalBytes / 1073741824, 1) : null;

    $appVersion = trim(file_exists(base_path('version.txt')) ? file_get_contents(base_path('version.txt')) : 'N/A');
    ?>

    <strong>Application</strong><br>
    Version : {{ $appVersion }}<br>
    Environnement : {{ config('app.env') }}<br>
    Mode debug : {{ config('app.debug') ? 'activé' : 'désactivé' }}<br>

    <hr>

    <strong>Mémoire PHP</strong><br>
    Utilisée : {{ $memoryUsed }} Mo<br>
    Pic d'utilisation : {{ $memoryPeak }} Mo<br>
    Limite : {{ $memoryLimit }}<br>

    <hr>

    @if($diskFreeGb !== null)
    <strong>Espace disque</strong><br>
    Libre : {{ $diskFreeGb }} Go / {{ $diskTotalGb }} Go
    ({{ $diskTotalGb > 0 ? round(($diskFreeGb / $diskTotalGb) * 100) : '?' }} % disponible)<br>
    <hr>
    @endif

    <strong>Drivers configurés</strong><br>
    Cache : {{ config('cache.default') }}<br>
    Session : {{ config('session.driver') }}<br>
    Queue : {{ config('queue.default') }}<br>
    Base de données : {{ config('database.default') }}<br>
    Mail : {{ config('mail.default') }}<br>

    </div>
</div>
@endsection

@section('scripts')
@parent
@endsection
