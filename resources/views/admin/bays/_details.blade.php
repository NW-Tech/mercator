@props([
    'bay',
    'withLink' => false
])
<table class="table table-bordered table-striped table-report" id="{{ $bay->getUID() }}">
    <tbody>
    <tr>
        <th width="10%">
            {{ trans('cruds.bay.fields.name') }}
        </th>
        <td>
        @if($withLink)
        @canShow($bay)
        <a href="{{ route('admin.bays.show', $bay->id) }}">{{ $bay->name }}</a>
        @elsecanShow
        {{ $bay->name }}
        @endcanShow
        @else
        {{ $bay->name }}
        @endif
        </td>
    </tr>
    <tr>
        <th>
            {{ trans('cruds.bay.fields.description') }}
        </th>
        <td>
            {!! $bay->description !!}
        </td>
    </tr>
    @canAccess(App\Models\Building::class)
    <tr>
        <th>
            {{ trans('cruds.bay.fields.room') }}
        </th>
        <td>
            @if ($bay->room!=null)
                @canShow($bay->room)
                    <a href="{{ route('admin.buildings.show', $bay->room->id) }}">
                        {{ $bay->room->name ?? '' }}
                    </a>
                @elsecanShow
                    {{ $bay->room->name ?? '' }}
                @endcanShow
            @endif
        </td>
    </tr>
    @endcanAccess
    @canAccessAny(App\Models\PhysicalServer::class, App\Models\StorageDevice::class, App\Models\Peripheral::class, App\Models\PhysicalSwitch::class, App\Models\PhysicalRouter::class, App\Models\PhysicalSecurityDevice::class)
    <tr>
        <th>
            {{ trans('cruds.menu.physical_infrastructure.title_short') }}
        </th>
        <td>
            @foreach($bay->physicalServers as $physicalServer)
                @canShow($physicalServer)
                    <a href="{{ route('admin.physical-servers.show', $physicalServer->id) }}">{{ $physicalServer->name }}</a>
                @elsecanShow
                    {{ $physicalServer->name }}
                @endcanShow
                @if(!$loop->last)
                    ,
                @else
                    <br>
                @endif
            @endforeach

            @foreach($bay->storageDevices as $storageDevice)
                @canShow($storageDevice)
                    <a href="{{ route('admin.storage-devices.show', $storageDevice->id) }}">{{ $storageDevice->name }}</a>
                @elsecanShow
                    {{ $storageDevice->name }}
                @endcanShow
                @if(!$loop->last)
                    ,
                @else
                    <br>
                @endif
            @endforeach

            @foreach($bay->peripherals as $peripheral)
                @canShow($peripheral)
                    <a href="{{ route('admin.peripherals.show', $peripheral->id) }}">{{ $peripheral->name }}</a>
                @elsecanShow
                    {{ $peripheral->name }}
                @endcanShow
                @if(!$loop->last)
                    ,
                @else
                    <br>
                @endif
            @endforeach

            @foreach($bay->physicalSwitches as $physicalSwitch)
                @canShow($physicalSwitch)
                    <a href="{{ route('admin.physical-switches.show', $physicalSwitch->id) }}">{{ $physicalSwitch->name }}</a>
                @elsecanShow
                    {{ $physicalSwitch->name }}
                @endcanShow
                @if(!$loop->last)
                    ,
                @else
                    <br>
                @endif
            @endforeach

            @foreach($bay->physicalRouters as $physicalRouter)
                @canShow($physicalRouter)
                    <a href="{{ route('admin.physical-routers.show', $physicalRouter->id) }}">{{ $physicalRouter->name }}</a>
                @elsecanShow
                    {{ $physicalRouter->name }}
                @endcanShow
                @if(!$loop->last)
                    ,
                @else
                    <br>
                @endif
            @endforeach

            @foreach($bay->physicalSecurityDevices as $physicalSecurityDevice)
                @canShow($physicalSecurityDevice)
                    <a href="{{ route('admin.physical-security-devices.show', $physicalSecurityDevice->id) }}">{{ $physicalSecurityDevice->name }}</a>
                @elsecanShow
                    {{ $physicalSecurityDevice->name }}
                @endcanShow
                @if(!$loop->last)
                    ,
                @else
                    <br>
                @endif
            @endforeach
        </td>
    </tr>
    @endcanAccessAny
    </tbody>
</table>
