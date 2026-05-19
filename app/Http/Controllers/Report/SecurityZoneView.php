<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Zone;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityZoneView extends Controller
{
    public function generate(Request $request)
    {
        abort_if(Gate::denies('zone_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->has('filter')) {
            $selectedIds = array_values(array_filter(array_map('intval', (array) $request->input('zones', []))));
            $request->session()->put('security_zone_filter', $selectedIds);
        } else {
            $raw         = $request->session()->get('security_zone_filter', []);
            $selectedIds = is_array($raw) ? $raw : [];
        }

        $allZones = Zone::orderBy('name')->pluck('name', 'id');

        $query = Zone::with('parentZones', 'childZones', 'buildings', 'adminUsers')->orderBy('name');
        if (!empty($selectedIds)) {
            $query->whereIn('id', $selectedIds);
        }
        $zones = $query->get();

        $buildings  = $zones->flatMap(fn($z) => $z->buildings)->unique('id')->sortBy('name');
        $adminUsers = $zones->flatMap(fn($z) => $z->adminUsers)->unique('id')->sortBy('user_id');

        return view('admin/reports/security_zones', compact(
            'allZones',
            'selectedIds',
            'zones',
            'buildings',
            'adminUsers',
        ));
    }
}
