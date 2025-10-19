@extends('layouts.admin')
@section('content')
    <div class="form-group">
        <a class="btn btn-default" href="{{ route('admin.clusters.index') }}">
            {{ trans('global.back_to_list') }}
        </a>

        <a class="btn btn-success" href="{{ route('admin.report.explore') }}?node=CLUSTER_{{$cluster->id}}">
            {{ trans('global.explore') }}
        </a>

        @can('cluster_edit')
            <a class="btn btn-info" href="{{ route('admin.clusters.edit', $cluster->id) }}">
                {{ trans('global.edit') }}
            </a>
        @endcan

        @can('cluster_delete')
            <form action="{{ route('admin.clusters.destroy', $cluster->id) }}" method="POST"
                  onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
            </form>
        @endcan
    </div>

    <div class="card">
        <div class="card-header">
            {{ trans('global.show') }} {{ trans('cruds.cluster.title') }}
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped">
                <tbody>
                <tr>
                    <th width="10%">
                        {{ trans('cruds.cluster.fields.name') }}
                    </th>
                    <td colspan="2">
                        {{ $cluster->name }}
                    </td>
                </tr>
                <tr>
                    <th width="10%">
                        {{ trans('cruds.cluster.fields.type') }}
                    </th>
                    <td colspan="2">
                        {{ $cluster->type }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.cluster.fields.attributes') }}
                    </th>
                    <td colspan="2">
                        @foreach(explode(" ",$cluster->attributes) as $attribute)
                            <span class="badge badge-info">{{ $attribute }}</span>
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.cluster.fields.description') }}
                    </th>
                    <td>
                        {!! $cluster->description !!}
                    </td>
                    <td width="10%">
                        @if ($cluster->icon_id === null)
                            <img src='/images/cluster.png' width='120' height='120'>
                        @else
                            <img src='{{ route('admin.documents.show', $cluster->icon_id) }}' width='120' height='120'>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.cluster.fields.address_ip') }}
                    </th>
                    <td colspan="2">
                        {{ $cluster->address_ip }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.cluster.fields.logical_servers') }} / {{ 'Routers' }}
                    </th>
                    <td colspan="2">
                        @foreach($cluster->logicalServers as $server)
                            <a href="{{ route('admin.logical-servers.show', $server->id) }}">
                                {{ $server->name }}
                            </a>
                            <br>
                        @endforeach
                        @foreach($cluster->routers as $router)
                            <a href="{{ route('admin.routers.show', $router->id) }}">
                                {{ $router->name }}
                            </a>
                            @if(!$loop->last)
                                <br>
                            @endif
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.cluster.fields.physical_servers') }}
                    </th>
                    <td colspan="2">
                        @foreach($cluster->physicalServers as $server)
                            <a href="{{ route('admin.physical-servers.show', $server->id) }}">
                                {{ $server->name }}
                            </a>
                            @if(!$loop->last)
                                <br>
                            @endif
                        @endforeach
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ trans('global.created_at') }} {{ $cluster->created_at ? $cluster->created_at->format(trans('global.timestamp')) : '' }}
            |
            {{ trans('global.updated_at') }} {{ $cluster->updated_at ? $cluster->updated_at->format(trans('global.timestamp')) : '' }}
        </div>
    </div>
    <div class="form-group">
        <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.clusters.index') }}">
            {{ trans('global.back_to_list') }}
        </a>
    </div>

@endsection
