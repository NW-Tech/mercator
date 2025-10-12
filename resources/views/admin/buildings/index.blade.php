@extends('layouts.admin')
@section('content')
    @can('building_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a id="btn-new" class="btn btn-success" href="{{ route("admin.buildings.create") }}">
                    {{ trans('global.add') }} {{ trans('cruds.building.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.building.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="dataTable" class="table table-bordered table-striped table-hover datatable">
                    <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.building.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.building.fields.description') }}
                        </th>
                        <th>
                            {{ trans('cruds.building.fields.type') }}
                        </th>
                        <th>
                            {{ trans('cruds.building.fields.attributes') }}
                        </th>
                        <th>
                            {{ trans('cruds.building.fields.site') }}
                        </th>
                        <th>
                            {{ trans('cruds.building.fields.parent') }}
                        </th>
                        <th>
                            {{ trans('cruds.building.fields.children') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($buildings as $key => $building)
                        <tr data-entry-id="{{ $building->id }}">
                            <td>

                            </td>
                            <td>
                                <a href="{{ route('admin.buildings.show', $building->id) }}">{{ $building->name ?? '' }}</a>
                            </td>
                            <td>
                                {!! $building->description ?? '' !!}
                            </td>
                            <td>
                                {{ $building->type }}
                            </td>
                            <td>
                                    <?php
                                    foreach (explode(" ", $building->attributes) as $attribute) {
                                        echo "<span class='badge badge-info'>";
                                        echo $attribute;
                                        echo "</span> ";
                                    }
                                    ?>
                            </td>
                            <td>
                                @if ($building->site!=null)
                                    <a href="{{ route('admin.sites.show', $building->site_id) }}">
                                        {{ $building->site->name ?? '' }}
                                    </a>
                                @endif
                            </td>
                            <td>
                                @if ($building->building!=null)
                                    <a href="{{ route('admin.buildings.show', $building->building) }}">
                                        {{ $building->building->name ?? '' }}
                                    </a>
                                @endif
                            </td>
                            <td>
                                @foreach($building->buildings as $b)
                                    <a href="{{ route('admin.buildings.show', $b->id) }}">
                                        {{ $b->name ?? '' }}
                                    </a>
                                    @if ($building->buildings->last()!=$b)
                                        ,
                                    @endif
                                @endforeach
                            </td>
                            <td nowrap>
                                @can('building_show')
                                    <a class="btn btn-xs btn-primary"
                                       href="{{ route('admin.buildings.show', $building->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('building_edit')
                                    <a class="btn btn-xs btn-info"
                                       href="{{ route('admin.buildings.edit', $building->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('building_delete')
                                    <form action="{{ route('admin.buildings.destroy', $building->id) }}" method="POST"
                                          onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                          style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
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
        @include('partials.datatable', array(
            'id' => '#dataTable',
            'title' => trans("cruds.building.title_singular"),
            'URL' => route('admin.buildings.massDestroy'),
            'canDelete' => auth()->user()->can('building_delete') ? true : false
        ));
    </script>
@endsection
