<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyApplicationFlowRequest;
use App\Http\Requests\StoreApplicationFlowRequest;
use App\Http\Requests\UpdateApplicationFlowRequest;
use App\Models\Application;
use App\Models\ApplicationModule;
use App\Models\ApplicationService;
use App\Models\Database;
use App\Models\ApplicationFlow;
use App\Models\Information;
use Gate;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class ApplicationFlowController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('flux_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $flows = ApplicationFlow::all()->sortBy('name');

        return view('admin.application-flows.index', compact('flows'));
    }

    public function create()
    {
        abort_if(Gate::denies('flux_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applications = Application::all()->sortBy('name')->pluck('name', 'id');
        $services = ApplicationService::all()->sortBy('name')->pluck('name', 'id');
        $modules = ApplicationModule::all()->sortBy('name')->pluck('name', 'id');
        $databases = Database::all()->sortBy('name')->pluck('name', 'id');
        $informations = Information::query()->orderBy('name')->pluck('name', 'id');

        // List
        $nature_list = ApplicationFlow::select('nature')->where('nature', '<>', null)->distinct()->orderBy('nature')->pluck('nature');
        $attributes_list = $this->getAttributes();

        $items = Collection::make();
        foreach ($applications as $key => $value) {
            $items->put(Application::$prefix . $key, $value . ' [Application]');
        }
        foreach ($services as $key => $value) {
            $items->put(ApplicationService::$prefix . $key, $value . ' [Service]');
        }
        foreach ($modules as $key => $value) {
            $items->put(ApplicationModule::$prefix . $key, $value . ' [Module]');
        }
        foreach ($databases as $key => $value) {
            $items->put(Database::$prefix . $key, $value . ' [Database]');
        }

        return view(
            'admin.application-flows.create',
            compact('items', 'nature_list', 'informations', 'attributes_list')
        );
    }

    public function store(StoreApplicationFlowRequest $request)
    {
        $flow = new ApplicationFlow;
        $flow->name = $request->name;
        $flow->nature = $request->nature;
        $flow->description = $request->description;
        $flow->attributes = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        // Source item
        if (str_starts_with($request->src_id, Application::$prefix)) {
            $flow->application_source_id = intval(substr($request->src_id, strlen(Application::$prefix)));
        } else {
            $flow->application_source_id = null;
        }

        if (str_starts_with($request->src_id, ApplicationService::$prefix)) {
            $flow->service_source_id = intval(substr($request->src_id, strlen(ApplicationService::$prefix)));
        } else {
            $flow->service_source_id = null;
        }

        if (str_starts_with($request->src_id, ApplicationModule::$prefix)) {
            $flow->module_source_id = intval(substr($request->src_id, strlen(ApplicationModule::$prefix)));
        } else {
            $flow->module_source_id = null;
        }

        if (str_starts_with($request->src_id, Database::$prefix)) {
            $flow->database_source_id = intval(substr($request->src_id, strlen(Database::$prefix)));
        } else {
            $flow->database_source_id = null;
        }

        // Dest item
        if (str_starts_with($request->dest_id, Application::$prefix)) {
            $flow->application_dest_id = intval(substr($request->dest_id, strlen(Application::$prefix)));
        } else {
            $flow->application_dest_id = null;
        }

        if (str_starts_with($request->dest_id, ApplicationService::$prefix)) {
            $flow->service_dest_id = intval(substr($request->dest_id, strlen(ApplicationService::$prefix)));
        } else {
            $flow->service_dest_id = null;
        }

        if (str_starts_with($request->dest_id, ApplicationModule::$prefix)) {
            $flow->module_dest_id = intval(substr($request->dest_id, strlen(ApplicationModule::$prefix)));
        } else {
            $flow->module_dest_id = null;
        }

        if (str_starts_with($request->dest_id, Database::$prefix)) {
            $flow->database_dest_id = intval(substr($request->dest_id, strlen(Database::$prefix)));
        } else {
            $flow->database_dest_id = null;
        }

        $flow->crypted = $request->has('crypted');
        $flow->bidirectional = $request->has('bidirectional');
        $flow->save();

        $flow->informations()->sync($request->get('informations'));

        return redirect()->route('admin.application-flows.index');
    }

    public function edit(ApplicationFlow $flow)
    {
        abort_if(Gate::denies('flux_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applications = Application::query()->orderBy('name')->pluck('name', 'id');
        $services = ApplicationService::query()->orderBy('name')->pluck('name', 'id');
        $modules = ApplicationModule::query()->orderBy('name')->pluck('name', 'id');
        $databases = Database::query()->orderBy('name')->pluck('name', 'id');
        $informations = Information::query()->orderBy('name')->pluck('name', 'id');

        // List
        $nature_list = ApplicationFlow::select('nature')->where('nature', '<>', null)->distinct()->orderBy('nature')->pluck('nature');
        $attributes_list = $this->getAttributes();

        $items = Collection::make();
        foreach ($applications as $key => $value) {
            $items->put( Application::$prefix . $key, $value . ' [Application]');
        }
        foreach ($services as $key => $value) {
            $items->put(ApplicationService::$prefix . $key, $value . ' [Service]');
        }
        foreach ($modules as $key => $value) {
            $items->put(ApplicationModule::$prefix . $key, $value . ' [Module]');
        }
        foreach ($databases as $key => $value) {
            $items->put(Database::$prefix . $key, $value . ' [Database]');
        }

        return view(
            'admin.application-flows.edit',
            compact('items', 'nature_list', 'informations', 'attributes_list', 'flow')
        );
    }

    public function update(UpdateApplicationFlowRequest $request, ApplicationFlow $flow)
    {
        $flow->name = $request->get('name');
        $flow->nature = $request->nature;
        $flow->description = $request->get('description');
        $flow->attributes = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        // Source item
        if (str_starts_with($request->src_id, Application::$prefix)) {
            $flow->application_source_id = intval(substr($request->src_id, strlen(Application::$prefix)));
        } else {
            $flow->application_source_id = null;
        }

        if (str_starts_with($request->src_id, ApplicationService::$prefix)) {
            $flow->service_source_id = intval(substr($request->src_id, strlen(ApplicationService::$prefix)));
        } else {
            $flow->service_source_id = null;
        }

        if (str_starts_with($request->src_id, ApplicationModule::$prefix)) {
            $flow->module_source_id = intval(substr($request->src_id, strlen(ApplicationModule::$prefix)));
        } else {
            $flow->module_source_id = null;
        }

        if (str_starts_with($request->src_id, Database::$prefix)) {
            $flow->database_source_id = intval(substr($request->src_id, strlen(Database::$prefix)));
        } else {
            $flow->database_source_id = null;
        }

        // Dest item
        if (str_starts_with($request->dest_id, Application::$prefix)) {
            $flow->application_dest_id = intval(substr($request->dest_id, strlen(Application::$prefix)));
        } else {
            $flow->application_dest_id = null;
        }

        if (str_starts_with($request->dest_id, ApplicationService::$prefix)) {
            $flow->service_dest_id = intval(substr($request->dest_id, strlen(ApplicationService::$prefix)));
        } else {
            $flow->service_dest_id = null;
        }

        if (str_starts_with($request->dest_id, ApplicationModule::$prefix)) {
            $flow->module_dest_id = intval(substr($request->dest_id, strlen(ApplicationModule::$prefix)));
        } else {
            $flow->module_dest_id = null;
        }

        if (str_starts_with($request->dest_id, Database::$prefix)) {
            $flow->database_dest_id = intval(substr($request->dest_id, strlen(Database::$prefix)));
        } else {
            $flow->database_dest_id = null;
        }
        
        $flow->crypted = $request->has('crypted');
        $flow->bidirectional = $request->has('bidirectional');
        $flow->update();

        $flow->informations()->sync($request->get('informations'));

        return redirect()->route('admin.application-flows.index');
    }

    public function show(ApplicationFlow $flow)
    {
        abort_if(Gate::denies('flux_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $flow->load('applicationSource', 'serviceSource', 'moduleSource', 'databaseSource', 'applicationDest', 'serviceDest', 'moduleDest', 'databaseDest'
        );

        return view('admin.application-flows.show', compact('flow'));
    }

    public function destroy(ApplicationFlow $flow)
    {
        abort_if(Gate::denies('flux_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $flow->delete();

        return redirect()->route('admin.application-flows.index');
    }

    public function massDestroy(MassDestroyApplicationFlowRequest $request)
    {
        ApplicationFlow::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    private function getAttributes()
    {
        $attributes_list = ApplicationFlow::query()->select('attributes')
            ->where('attributes', '<>', null)
            ->distinct()
            ->pluck('attributes');
        $res = [];
        foreach ($attributes_list as $i) {
            foreach (explode(' ', $i) as $j) {
                if (strlen(trim($j)) > 0) {
                    $res[] = trim($j);
                }
            }
        }
        sort($res);

        return array_unique($res);
    }
}
