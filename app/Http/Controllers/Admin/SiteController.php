<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroySiteRequest;
use App\Http\Requests\StoreSiteRequest;
use App\Http\Requests\UpdateSiteRequest;
use App\Models\Site;
use App\Services\IconUploadService;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SiteController extends Controller
{
    public function __construct(private readonly IconUploadService $iconUploadService) {}

    public function index()
    {
        $user = auth()->user();
        $allowedIds = Gate::allows('site_access') ? null : \App\Models\Cartographer::allowedIdsFor($user, \App\Models\Site::class);
        if ($allowedIds !== null && empty($allowedIds)) {
            abort(Response::HTTP_FORBIDDEN, '403 Forbidden');
        }

        $sites = Site::with('buildings')
            ->when(request('search'), function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    foreach (Site::$searchable as $field) {
                        $q->orWhere($field, 'like', "%{$search}%");
                    }
                });
            })
            ->orderBy('name')
            
            ->when($allowedIds !== null, fn ($q) => $q->whereIn('id', $allowedIds))->paginate(min(max((int) request('per_page', 50), 10), 500));

        return view('admin.sites.index', compact('sites'));
    }

    public function create()
    {
        abort_if(Gate::denies('site_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Select icons
        $icons = Site::select('icon_id')->whereNotNull('icon_id')->orderBy('icon_id')->distinct()->pluck('icon_id');

        return view('admin.sites.create', compact('icons'));
    }

    public function clone(Request $request, Site $site)
    {
        abort_if(Gate::denies('site_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $icons = Site::query()
            ->whereNotNull('icon_id')
            ->orderBy('icon_id')
            ->distinct()
            ->pluck('icon_id');

        $request->merge($site->only($site->getFillable()));
        $request->flash();

        return view('admin.sites.create', compact('icons'));
    }

    public function store(StoreSiteRequest $request)
    {
        $site = Site::create($request->all());

        // Save icon
        $this->iconUploadService->handle($request, $site);

        $site->save();

        return redirect()->route('admin.sites.index');
    }

    public function edit(Site $site)
    {
        abort_if(Gate::denies('edit-object', $site), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $icons = Site::select('icon_id')->whereNotNull('icon_id')->orderBy('icon_id')->distinct()->pluck('icon_id');

        return view('admin.sites.edit', compact('site', 'icons'));
    }

    public function update(UpdateSiteRequest $request, Site $site)
    {
        abort_if(Gate::denies('edit-object', $site), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Save icon
        $this->iconUploadService->handle($request, $site);

        $site->update($request->all());

        return redirect()->route('admin.sites.index');
    }

    public function show(Site $site)
    {
        abort_if(Gate::denies('show-object', $site), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $site->load(
            'buildings',
            'physicalServers',
            'workstations',
            'storageDevices',
            'peripherals',
            'phones',
            'physicalSwitches'
        );

        return view('admin.sites.show', compact('site'));
    }

    public function destroy(Site $site)
    {
        abort_if(Gate::denies('site_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $site->delete();

        return redirect()->route('admin.sites.index');
    }

    public function massDestroy(MassDestroySiteRequest $request)
    {
        Site::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
