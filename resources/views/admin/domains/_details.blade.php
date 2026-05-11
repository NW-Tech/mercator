@props([
    'domain',
    'withLink' => false,
])
<table class="table table-bordered table-striped table-report" id="{{ $domain->getUID() }}">
    <tbody>
        <tr>
            <th width="10%">
                {{ trans('cruds.domaine.fields.name') }}
            </th>
            <td>
            @if ($withLink)
                <a href="{{ route('admin.domains.show', $domain->id) }}">{{ $domain->name }}</a>
            @else
                {{ $domain->name }}
            @endif
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.domaine.fields.description') }}
            </th>
            <td>
                {!! $domain->description !!}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.domaine.fields.domain_ctrl_cnt') }}
            </th>
            <td>
                {{ $domain->domain_ctrl_cnt }}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.domaine.fields.user_count') }}
            </th>
            <td>
                {{ $domain->user_count }}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.domaine.fields.machine_count') }}
            </th>
            <td>
                {{ $domain->machine_count }}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.domaine.fields.relation_inter_domaine') }}
            </th>
            <td>
                {{ $domain->relation_inter_domaine }}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.forestAd.title') }}
            </th>
            <td>
                @foreach($domain->forestAds as $forestAd)
                    <a href="{{ route('admin.forest-ads.show', $forestAd->id) }}">
                    {{ $forestAd->name }}
                    </a>
                @if (!$loop->last)
                ,
                @endif
                @endforeach
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.logicalServer.title') }}
            </th>
            <td>
                @foreach($domain->logicalServers as $logicalServer)
                    <a href="{{ route('admin.logical-servers.show', $logicalServer->id) }}">
                    {{ $logicalServer->name }}
                    </a>
                    @if ($loop->last!=$logicalServer)
                    ,
                    @endif
                @endforeach
            </td>
        </tr>
    </tbody>
</table>
