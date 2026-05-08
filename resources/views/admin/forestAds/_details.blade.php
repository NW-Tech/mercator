<table class="table table-bordered table-striped">
    <tbody>
        <tr>
            <th width='10%'>
                {{ trans('cruds.forestAd.fields.name') }}
            </th>
            <td>
                {{ $forestAd->name }}
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
        <tr>
            <th>
                {{ trans('cruds.forestAd.fields.zone_admin') }}
            </th>
            <td>
                @if ($forestAd->zone_admin_id!=null)
                <a href="{{ route('admin.zone-admins.show', $forestAd->zoneAdmin->id) }}">
                {{ $forestAd->zoneAdmin->name ?? '' }}
                </a>
                @endif
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.forestAd.fields.domains') }}
            </th>
            <td>
                @foreach($forestAd->domains as $domain)
                <a href="{{ route('admin.domains.show', $domain->id) }}">
                {{ $domain->name }}
                </a>
                @if ($forestAd->domains->last()!=$domain)
                ,
                @endif
                @endforeach
            </td>
        </tr>
    </tbody>
</table>
