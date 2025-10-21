<?php


namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyActivityRequest;
use App\Http\Requests\StoreActivityRequest;
use App\Http\Requests\UpdateActivityRequest;
use App\Http\Resources\Admin\ActivityResource;
use App\Models\Activity;
use Gate;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ActivityController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('activity_access'), ResponseAlias::HTTP_FORBIDDEN, '403 Forbidden');

        $activities = Activity::all();

        return response()->json($activities);
    }

    public function store(StoreActivityRequest $request)
    {
        abort_if(Gate::denies('activity_create'), ResponseAlias::HTTP_FORBIDDEN, '403 Forbidden');

        $activity = Activity::create($request->all());
        $activity->operations()->sync($request->input('operations', []));
        $activity->processes()->sync($request->input('processes', []));

        return response()->json($activity, 201);
    }

    public function show(Activity $activity)
    {
        abort_if(Gate::denies('activity_show'), ResponseAlias::HTTP_FORBIDDEN, '403 Forbidden');

        return new ActivityResource($activity);
    }

    public function update(UpdateActivityRequest $request, Activity $activity)
    {
        abort_if(Gate::denies('activity_edit'), ResponseAlias::HTTP_FORBIDDEN, '403 Forbidden');

        $activity->update($request->all());
        $activity->operations()->sync($request->input('operations', []));
        $activity->processes()->sync($request->input('processes', []));

        return response()->json();
    }

    public function destroy(Activity $activity)
    {
        abort_if(Gate::denies('activity_delete'), ResponseAlias::HTTP_FORBIDDEN, '403 Forbidden');

        $activity->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyActivityRequest $request)
    {
        abort_if(Gate::denies('activity_delete'), ResponseAlias::HTTP_FORBIDDEN, '403 Forbidden');

        Activity::whereIn('id', request('ids'))->delete();

        return response(null, ResponseAlias::HTTP_NO_CONTENT);
    }
}
