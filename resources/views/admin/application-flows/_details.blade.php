@props([
    'flux',
    'withLink' => false,
])
<table class="table table-bordered table-striped table-report">
    <tbody>
    <tr>
        <th width="10%">
            {{ trans('cruds.flux.fields.name') }}
        </th>
        <td width="30%">
        @if ($withLink)
        <a href="{{ route('admin.application-flows.show', $flow->id) }}">{{ $flow->name }}</a>
        @else
            {{ $flow->name }}
        @endif
        </td>
        <th width="10%">
            {{ trans('cruds.flux.fields.nature') }}
        </th>
        <td width="20%">
            {{ $flow->nature }}
        </td>
        <th width="10%">
            {{ trans('cruds.flux.fields.attributes') }}
        </th>
        <td width="20%">
            @foreach(explode(" ",$flow->attributes) as $attribute)
                <span class="badge badge-info">{{ $attribute }}</span>
            @endforeach
        </td>
    </tr>
    <tr>
        <th>
            {{ trans('cruds.flux.fields.description') }}
        </th>
        <td colspan="5">
            {!! $flow->description !!}
        </td>
    </tr>

    <tr>
        <th>
            {{ trans('cruds.flux.fields.source') }}
        </th>
        <td colspan="1">
            @if ($flow->applicationSource!=null)
                <a href="{{ route('admin.applications.show',$flow->applicationSource->id) }}">
                    {{ $flow->applicationSource->name }}
                </a> [Application]
            @endif
            @if($flow->serviceSource!=null)
                <a href="{{ route('admin.application-services.show', $flow->serviceSource->id) }}">
                    {{ $flow->serviceSource->name }}
                </a> [Service]
            @endif
            @if ($flow->moduleSource!=null)
                <a href="{{ route('admin.application-modules.show', $flow->moduleSource->id) }}">
                    {{ $flow->moduleSource->name }}
                </a> [Module]
            @endif
            @if ($flow->databaseSource!=null)
                <a href="{{ route('admin.databases.show',$flow->databaseSource->id) }}">
                    {{ $flow->databaseSource->name }}
                </a> [Database]
            @endif
        </td>

        <th>
            {{ trans('cruds.flux.fields.destination') }}
        </th>
        <td colspan="3">
            @if ($flow->applicationDest!=null)
                <a href="{{ route('admin.applications.show',$flow->applicationDest->id) }}">
                    {{ $flow->applicationDest->name }}
                </a> [Application]
            @endif
            @if ($flow->serviceDest!=null)
                <a href="{{ route('admin.application-services.show', $flow->serviceDest->id) }}">
                    {{ $flow->serviceDest->name }}
                </a> [Service]
            @endif
            @if ($flow->moduleDest!=null)
                <a href="{{ route('admin.application-modules.show', $flow->moduleDest->id) }}">
                    {{ $flow->moduleDest->name }}
                </a> [Module]
            @endif
            @if ($flow->databaseDest!=null)
                <a href="{{ route('admin.databases.show',$flow->databaseDest->id) }}">
                    {{ $flow->databaseDest->name }}
                </a> [Database]
            @endif
        </td>
    </tr>
    <tr>
        <th>
            {{ trans('cruds.flux.fields.information') }}
        </th>
        <td colspan="5">
            @foreach($flow->informations as $info)
                <a href="{{ route('admin.information.show',$info->id) }}">{{$info->name}}</a>
                @if (!$loop->last) , @endif
            @endforeach
        </td>
    </tr>
    <tr>
        <th>
            {{ trans('cruds.flux.fields.crypted') }}
        </th>
        <td>
            @if ($flow->crypted==0)
                Non
            @elseif ($flow->crypted==1)
                Oui
            @endif
        </td>
        <th>
            {{ trans('cruds.flux.fields.bidirectional') }}
        </th>
        <td colspan="3">
            @if ($flow->bidirectional==0)
                Non
            @elseif ($flow->bidirectional==1)
                Oui
            @endif
        </td>
    </tr>
    </tbody>
</table>
