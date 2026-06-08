@extends('layouts.admin')

@section('title')
    {{ trans('cruds.menu.metier.title') }}
@endsection

@section('content')
<div class="graph-card-sticky">
    <div class="card mb-3">
            <div class="card-header">
                {{ trans('cruds.menu.metier.title') }}
            </div>
            <form action="/admin/report/information_system">

                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="col-sm-6">
                        <table class="table table-bordered table-striped"
                               style="max-width: 600px; width:100%">
                            <tr>
                                <td style="width: 50%">
                                    {{ trans('cruds.macroProcessus.title') }} :
                                    <select name="macroprocess" id="macroprocess"
                                            onchange="this.form.process.value='';this.form.submit()"
                                            class="form-control select2">
                                        <option value="">-- All --</option>
                                        @foreach ($all_macroprocess as $macroprocess)
                                            <option value="{{$macroprocess->id}}" {{ Session::get('macroprocess')==$macroprocess->id ? "selected" : "" }}>{{ $macroprocess->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td style="width: 50%">
                                    {{ trans('cruds.process.title') }} :
                                    <select name="process" id="process" onchange="this.form.submit()"
                                            class="form-control select2">
                                        <option value="">-- All --</option>
                                        @if ($all_process!=null)
                                            @foreach ($all_process as $process)
                                                <option value="{{$process->id}}" {{ Session::get('process')==$process->id ? "selected" : "" }}>{{ $process->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div id="graph-container">
                        <div class="graphviz" id="graph"></div>
                        <div class="graph-resize-handle"></div>
                    </div>
                    <div class="row p-1">
                        <div class="col-4">

                            @php($engines=["dot", "fdp",  "osage", "circo" ])
                            @php($engine = request()->get('engine', 'dot'))

                            <label class="inline-flex items-center ps-1 pe-1">
                                <a href="#" id="downloadSvg"><i class="bi bi-download"></i></a>
                            </label>

                            <label class="inline-flex items-center">
                                Rendu :
                            </label>
                            @foreach($engines as $value)
                                <label class="inline-flex items-center ps-1">
                                    <input
                                            type="radio"
                                            name="engine"
                                            value="{{ $value }}"
                                            @checked($engine === $value)
                                            onchange="this.form.submit();"
                                    >
                                    <span>{{ $value }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </form>

    </div>
</div>

<div class="report-scroll-area">

        @canAccess(App\Models\MacroProcessus::class)
            @if ($macroProcessuses->count()>0)
                <br>
                <div class="card">
                    <div class="card-header">
                        {{ trans('cruds.macroProcessus.title') }} :
                    </div>
                    <div class="card-body">
                        <p>{{ trans('cruds.macroProcessus.description') }}</p>
                        @foreach($macroProcessuses as $item)
                            <div class="row">
                                <div class="col">
                                    @include('admin.macroProcessuses._details', [
                                        'macroProcessus' => $item,
                                        'withLink' => true,
                                    ])
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endcan
        @canAccess(App\Models\Process::class)
            @if ($processes->count()>0)
                <br>
                <div class="card">
                    <div class="card-header">
                        {{ trans('cruds.process.title') }}
                    </div>
                    <div class="card-body">
                        <p>{{ trans('cruds.process.description') }}</p>
                        @foreach($processes as $process)
                            <div class="row">
                                <div class="col">
                                    @include('admin.processes._details', [
                                        'process' => $process,
                                        'withLink' => true,
                                    ])
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endcan

        @canAccess(App\Models\Activity::class)
            @if ($activities->count()>0)
                <br>
                <div class="card">
                    <div class="card-header">
                        {{ trans('cruds.activity.title') }}
                    </div>
                    <div class="card-body">
                        <p>{{ trans('cruds.activity.description') }}</p>
                        @foreach($activities as $activity)
                            <div class="row">
                                <div class="col">
                                    @include('admin.activities._details', [
                                        'activity' => $activity,
                                        'withLink' => true,
                                    ])
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endcan

        @canAccess(App\Models\Operation::class)
            @if ($operations->count()>0)
                <br>
                <div class="card">
                    <div class="card-header">
                        {{ trans('cruds.operation.title') }}
                    </div>
                    <div class="card-body">
                        <p>{{ trans('cruds.operation.description') }}</p>
                        @foreach($operations as $operation)
                            <div class="row">
                                <div class="col">
                                    @include('admin.operations._details', [
                                        'operation' => $operation,
                                        'withLink' => true,
                                    ])
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endcan

        @canAccess(App\Models\Task::class)
            @if ($tasks->count()>0)
                <br>
                <div class="card">
                    <div class="card-header">
                        {{ trans('cruds.task.title') }}
                    </div>
                    <div class="card-body">
                        <p>{{ trans('cruds.task.description') }}</p>
                        @foreach($tasks as $task)
                            <div class="row">
                                <div class="col">
                                    @include('admin.tasks._details', [
                                        'task' => $task,
                                        'withLink' => true,
                                    ])
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endcan

        @canAccess(App\Models\Actor::class)
            @if ($actors->count()>0)
                <br>
                <div class="card">
                    <div class="card-header">
                        {{ trans('cruds.actor.title') }}
                    </div>
                    <div class="card-body">
                        <p>{{ trans('cruds.actor.description') }}</p>
                        @foreach($actors as $actor)
                            <div class="row">
                                <div class="col">
                                    @include('admin.actors._details', [
                                        'actor' => $actor,
                                        'withLink' => true,
                                    ])
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endcan

        @canAccess(App\Models\Information::class)
            @if ($informations->count()>0)
                <br>
                <div class="card">
                    <div class="card-header">
                        {{ trans('cruds.information.title') }}
                    </div>
                    <div class="card-body">
                        <p>{{ trans('cruds.information.description') }}</p>
                        @foreach($informations as $information)
                            <div class="row">
                                <div class="col">
                                    @include('admin.information._details', [
                                        'information' => $information,
                                        'withLink' => true,
                                    ])
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endcan
    </div>
@endsection

@section('scripts')
    @vite(['resources/js/graphviz.js'])
    <script>
        let dotSrc = `
digraph  {
@foreach($macroProcessuses as $macroProcess)
    MP{{ $macroProcess->id }} [label="{{ $macroProcess->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/macroprocess.png"  href="#{{ $macroProcess->getUID() }}"]
@endforeach
@foreach($processes as $process)
    P{{ $process->id }} [label="{{ $process->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/process.png"  href="#{{ $process->getUID() }}"]
    @foreach($process->activities as $activity)
        @if($activities->contains('id', $activity->id))
        P{{$process->id}} -> A{{$activity->id}}
        @endif
    @endforeach
    @canAccess(App\Models\Information::class)
        @foreach($process->information as $information)
            @if($informations->contains('id', $information->id))
            P{{ $process->id }} -> I{{ $information->id }}
            @endif
        @endforeach
    @endcan
    @if ($process->macroprocess_id!=null && $macroProcessuses->contains('id', $process->macroprocess_id))
        MP{{ $process->macroprocess_id }} -> P{{$process->id}}
    @endif
    @foreach($process->operations as $operation)
        @if($operations->contains('id', $operation->id))
        P{{ $process->id }} -> O{{ $operation->id }}
        @endif
    @endforeach
@endforeach

@foreach($activities as $activity)
    A{{ $activity->id }} [label="{{ $activity->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/activity.png"  href="#{{ $activity->getUID() }}"]
    @foreach($activity->operations as $operation)
        @if($operations->contains('id', $operation->id))
        A{{ $activity->id }} -> O{{ $operation->id }}
        @endif
    @endforeach
@endforeach
@foreach($operations as $operation)
    O{{ $operation->id }} [label="{{ $operation->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/operation.png"  href="#{{ $operation->getUID() }}"]
    @foreach($operation->tasks as $task)
        @if($tasks->contains('id', $task->id))
        O{{ $operation->id }} -> T{{ $task->id }}
        @endif
    @endforeach
    @foreach($operation->actors as $actor)
        @if($actors->contains('id', $actor->id))
        O{{ $operation->id }} -> ACT{{ $actor->id }}
        @endif
    @endforeach
@endforeach
@foreach($tasks as $task)
    T{{ $task->id }} [label="{{ $task->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/task.png"  href="#{{ $task->getUID() }}"]
@endforeach
@foreach($actors as $actor)
    ACT{{ $actor->id }} [label="{{ $actor->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/actor.png"  href="#{{ $actor->getUID() }}"]
@endforeach
@foreach($informations as $information)
    I{{ $information->id }} [label="{{ $information->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/information.png"  href="#{{ $information->getUID() }}"]
    @foreach($information->children as $child)
        @if($informations->contains('id', $child->id))
        I{{ $information->id }} -> I{{ $child->id }}
        @endif
    @endforeach
@endforeach
}`;

        document.addEventListener('graphvizReady', () => {
            document.getElementById("graph").innerHTML = window.graphviz.layout(
                dotSrc,
                "svg",
                "{{ $engine }}",
                {
                    images: [
                        { path: "/images/macroprocess.png", width: "64px", height: "64px" },
                        { path: "/images/process.png",      width: "64px", height: "64px" },
                        { path: "/images/activity.png",     width: "64px", height: "64px" },
                        { path: "/images/operation.png",    width: "64px", height: "64px" },
                        { path: "/images/task.png",         width: "64px", height: "64px" },
                        { path: "/images/actor.png",        width: "64px", height: "64px" },
                        { path: "/images/information.png",  width: "64px", height: "64px" },
                    ]
                }
            );
        });
    </script>
    @parent
@endsection