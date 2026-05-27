@extends('layouts.admin')

@section('title')
    {{ trans('cruds.zone.title_singular') }} {{ trans('global.list') }}
@endsection

@section('content')
    @can('zone_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a id="btn-new" class="btn btn-success" href="{{ route('admin.zones.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.zone.title_singular') }}
                </a>
            </div>
        </div>
    @endcan

    <div class="card">
        <div class="card-header">
            {{ trans('cruds.zone.title') }}
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="dataTable" class="table table-bordered table-striped table-hover datatable">
                    <thead>
                    <tr>
                        <th width="10"></th>
                        <th>{{ trans('cruds.zone.fields.name') }}</th>
                        <th>{{ trans('cruds.zone.fields.type') }}</th>
                        <th>{{ trans('cruds.zone.fields.attributes') }}</th>
                        <th>{{ trans('cruds.zone.fields.description') }}</th>
                        <th>{{ trans('cruds.zone.fields.buildings') }}</th>
                        <th>{{ trans('cruds.zone.fields.parent_zones') }}</th>
                        <th>{{ trans('cruds.zone.fields.child_zones') }}</th>
                        <th>&nbsp;</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($zones as $zone)
                        <tr data-entry-id="{{ $zone->id }}"
                            @if($zone->description === null) class="table-warning" @endif>
                            <td></td>
                            <td>
                                <a href="{{ route('admin.zones.show', $zone->id) }}">
                                    {{ $zone->name }}
                                </a>
                            </td>
                            <td>{{ $zone->type ?? '' }}</td>
                            <td>
                            @foreach(explode(" ",$zone->attributes) as $attribute)
                                <span class="badge badge-info">{{ $attribute }}</span>
                            @endforeach
                            </td>
                            <td>{!! $zone->description ?? '' !!}</td>
                            <td>
                            @foreach($zone->buildings as $building)
                                <a href="{{ route('admin.buildings.show', $building->id) }}">{{ $building->name }}</a>{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                            </td>
                            <td>
                            @foreach($zone->parentZones as $parentZone)
                                <a href="{{ route('admin.zones.show', $parentZone->id) }}">{{ $parentZone->name }}</a>{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                            </td>
                            <td>
                            @foreach($zone->childZones as $childZone)
                                <a href="{{ route('admin.zones.show', $childZone->id) }}">{{ $childZone->name }}</a>{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                            </td>
                            <td nowrap>
                                @can('zone_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.zones.show', $zone->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan
                                @can('zone_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.zones.edit', $zone->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan
                                @can('zone_delete')
                                    <form action="{{ route('admin.zones.destroy', $zone->id) }}" method="POST"
                                          onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                          style="display: inline-block;">
                                        @method('DELETE')
                                        @csrf
                                        <input type="submit" class="btn btn-xs btn-danger"
                                               value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script>
        @include('partials.datatable', [
            'id'        => '#dataTable',
            'title'     => trans('cruds.zone.title_singular'),
            'URL'       => route('admin.zones.massDestroy'),
            'canDelete' => auth()->user()->can('zone_delete') ? true : false,
        ])
    </script>
@endsection
