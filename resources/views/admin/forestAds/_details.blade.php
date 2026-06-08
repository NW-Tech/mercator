@props([
    'forestAd',
    'withLink' => false,
])
<table class="table table-bordered table-striped" id="{{ $forestAd->getUID() }}">
    <tbody>
        <tr>
            <th width='10%'>
                {{ trans('cruds.forestAd.fields.name') }}
            </th>
            <td>
            @if ($withLink)
                @canShow($forestAd)
                <a href="{{ route('admin.forest-ads.show', $forestAd->id) }}">{{ $forestAd->name }}</a>
                @elsecanShow
                {{ $forestAd->name }}
                @endcanShow
            @else
                {{ $forestAd->name }}
            @endif
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.forestAd.fields.description') }}
            </th>
            <td>
                {!! $forestAd->description !!}
            </td>
        </tr>
        @canAccess(App\Models\ZoneAdmin::class)
        <tr>
            <th>
                {{ trans('cruds.forestAd.fields.zone_admin') }}
            </th>
            <td>
                @if ($forestAd->zone_admin_id!=null)
                @canShow($forestAd->zoneAdmin)
                <a href="{{ route('admin.zone-admins.show', $forestAd->zoneAdmin->id) }}">
                {{ $forestAd->zoneAdmin->name ?? '' }}
                </a>
                @elsecanShow
                {{ $forestAd->zoneAdmin->name ?? '' }}
                @endcanShow
                @endif
            </td>
        </tr>
        @endcanAccess
        @canAccess(App\Models\Domain::class)
        <tr>
            <th>
                {{ trans('cruds.forestAd.fields.domains') }}
            </th>
            <td>
                @foreach($forestAd->domains as $domain)
                @canShow($domain)
                <a href="{{ route('admin.domains.show', $domain->id) }}">
                {{ $domain->name }}
                </a>
                @elsecanShow
                {{ $domain->name }}
                @endcanShow
                @if ($forestAd->domains->last()!=$domain)
                ,
                @endif
                @endforeach
            </td>
        </tr>
        @endcanAccess
    </tbody>
</table>
