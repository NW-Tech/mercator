@props([
    'zoneAdmin',
    'withLink' => false
])
<table class="table table-bordered table-striped table-report" id="{{ $zoneAdmin->getUID() }}">
    <tbody>
        <tr>
            <th width="10%">
                {{ trans('cruds.zoneAdmin.fields.name') }}
            </th>
            <td>
            @if ($withLink)
                @canShow($zoneAdmin)
                <a href="{{ route('admin.zone-admins.show', $zoneAdmin->id) }}">{{ $zoneAdmin->name }}</a>
                @elsecanShow
                {{ $zoneAdmin->name }}
                @endcanShow
            @else
                {{ $zoneAdmin->name }}
            @endif
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.zoneAdmin.fields.description') }}
            </th>
            <td>
                {!! $zoneAdmin->description !!}
            </td>
        </tr>
        @canAccess(App\Models\Annuaire::class)
        <tr>
            <th>
                {{ trans('cruds.zoneAdmin.fields.annuaires') }}
            </th>
            <td>
                @foreach($zoneAdmin->annuaires as $annuaire)
                @canShow($annuaire)
                <a href="{{ route('admin.annuaires.show', $annuaire->id) }}">
                    {{ $annuaire->name }}
                </a>
                @elsecanShow
                    {{ $annuaire->name }}
                @endcanShow
                @if ($zoneAdmin->annuaires->last()!=$annuaire)
                    ,
                @endif
                @endforeach
            </td>
        </tr>
        @endcanAccess
        @canAccess(App\Models\ForestAd::class)
        <tr>
            <th>
                {{ trans('cruds.zoneAdmin.fields.forests') }}
            </th>
            <td>
                @foreach($zoneAdmin->forestAds as $forestAd)
                @canShow($forestAd)
                <a href="{{ route('admin.forest-ads.show', $forestAd->id) }}">
                    {{ $forestAd->name ?? '' }}
                </a>
                @elsecanShow
                    {{ $forestAd->name ?? '' }}
                @endcanShow
                @if ($zoneAdmin->forestAds->last()!=$forestAd)
                    ,
                @endif
                @endforeach
            </td>
        </tr>
        @endcanAccess
    </tbody>
</table>
