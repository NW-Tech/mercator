@extends('layouts.admin')

@section('title')
    {{ trans('cruds.cartographer.my_objects.title') }}
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <i class="bi bi-pin-map-fill me-3"></i>{{ trans('cruds.cartographer.my_objects.title') }}
    </div>
    <div class="card-body">
        @if($cartographers->isEmpty())
            <p class="text-muted">{{ trans('cruds.cartographer.my_objects.empty') }}</p>
        @else
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th>{{ trans('cruds.cartographer.fields.type') }}</th>
                        <th>{{ trans('cruds.cartographer.fields.object') }}</th>
                        <th>{{ trans('cruds.cartographer.fields.last_updated') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cartographers as $cartographer)
                    @php
                        $showRoute   = $routes[$cartographer->cartographiable_type] ?? null;
                        $object      = $cartographer->cartographiable;
                        $label       = $object?->name ?? $object?->label ?? $object?->title ?? ($object ? '#'.$object->getKey() : null);
                        $updatedAt   = $object?->updated_at;
                    @endphp
                    <tr>
                        {{-- Type --}}
                        <td>{{ $models[$cartographer->cartographiable_type] ?? class_basename($cartographer->cartographiable_type) }}</td>

                        {{-- Objet --}}
                        <td>
                            @if($object)
                                @if($showRoute)
                                    <a href="{{ route($showRoute, $object->getKey()) }}">{{ $label }}</a>
                                @else
                                    {{ $label }}
                                @endif
                            @else
                                <em class="text-muted">({{ trans('cruds.cartographer.my_objects.deleted') }})</em>
                            @endif
                        </td>

                        {{-- Dernière modification + lien historique --}}
                        <td>
                            @if($object && $updatedAt)
                                <a href="{{ route('admin.audit-logs.history', ['type' => $cartographer->cartographiable_type, 'id' => $object->getKey()]) }}">
                                    {{ $updatedAt->format(trans('global.timestamp')) }}
                                </a>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection
