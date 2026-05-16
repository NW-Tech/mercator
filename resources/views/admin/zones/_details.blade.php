@props([
    'zone',
    'withLink' => false
])
<table class="table table-bordered table-striped table-report" id="{{ $zone->getUID() }}">
    <tbody>
    <tr>
        <th width="10%">{{ trans('cruds.zone.fields.name') }}</th>
        <td width="20%">
        @if($withLink)
        <a href="{{ route('admin.zones.show', $zone->id) }}">{{ $zone->name }}</a>
        @else
        {{ $zone->name }}
        @endif
        </td>
        <th width="10%">{{ trans('cruds.zone.fields.type') }}</th>
        <td width="20%">{{ $zone->type }}</td>
        <th width="10%">{{ trans('cruds.zone.fields.attributes') }}</th>
        <td width="30%">{{ $zone->attributes }}</td>
    </tr>
    <tr>
        <th>{{ trans('cruds.zone.fields.description') }}</th>
        <td colspan="5">{!! $zone->description !!}</td>
    </tr>
    <tr>
        <th>{{ trans('cruds.zone.fields.parent_zones') }}</th>
        <td colspan="2">
            @foreach($zone->parentZones as $parentZone)
                <a href="{{ route('admin.zones.show', $parentZone->id) }}">{{ $parentZone->name }}</a>{{ !$loop->last ? ', ' : '' }}
            @endforeach
        </td>
        <th>{{ trans('cruds.zone.fields.child_zones') }}</th>
        <td colspan="2">
            @foreach($zone->childZones as $childZone)
                <a href="{{ route('admin.zones.show', $childZone->id) }}">{{ $childZone->name }}</a>{{ !$loop->last ? ', ' : '' }}
            @endforeach
        </td>
    </tr>
    <tr>
        <th>{{ trans('cruds.zone.fields.buildings') }}</th>
        <td colspan="5">
            @foreach($zone->buildings as $building)
                <a href="{{ route('admin.buildings.show', $building->id) }}">{{ $building->name }}</a>{{ !$loop->last ? ', ' : '' }}
            @endforeach
        </td>
    </tr>
    <tr>
        <th>{{ trans('cruds.zone.fields.admin_users') }}</th>
        <td colspan="5">
            @foreach($zone->adminUsers as $adminUser)
                <a href="{{ route('admin.admin-users.show', $adminUser->id) }}">{{ $adminUser->user_id }}</a>{{ !$loop->last ? ', ' : '' }}
            @endforeach
        </td>
    </tr>
    </tbody>
</table>
