@props([
    'annuaire',
    'withLink' => false,
])
<table class="table table-bordered table-striped table-report" id="{{ $annuaire->getUID() }}">
    <tbody>
        <tr>
            <th width='10%'>
                {{ trans('cruds.annuaire.fields.name') }}
            </th>
            <td>
            @if ($withLink)
            <a href="{{ route('admin.annuaires.show', $annuaire->id) }}">{{ $annuaire->name }}</a>
            @else
                {{ $annuaire->name }}
            @endif
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.annuaire.fields.description') }}
            </th>
            <td>
                {!! $annuaire->description !!}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.annuaire.fields.solution') }}
            </th>
            <td>
                {{ $annuaire->solution }}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.annuaire.fields.zone_admin') }}
            </th>
            <td>
                @if ($annuaire->zoneAdmin!=null)
                <a href="{{ route('admin.zone-admins.show', $annuaire->zoneAdmin->id) }}">
                {{ $annuaire->zoneAdmin->name ?? '' }}
                @endif
                </a>
            </td>
        </tr>
    </tbody>
</table>
