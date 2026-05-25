<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyDomainRequest;
use App\Http\Requests\StoreDomainRequest;
use App\Http\Requests\UpdateDomainRequest;
use App\Models\Domain;
use App\Models\ForestAd;
use App\Models\LogicalServer;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class DomainController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('domaine_ad_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $domains = Domain::all()->sortBy('name');

        return view(
            'admin.domains.index',
            compact('domains')
        );
    }

    public function create()
    {
        abort_if(Gate::denies('domaine_ad_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $forestAds = ForestAd::all()->sortBy('name')->pluck('name', 'id');
        $logicalServers = LogicalServer::all()->sortBy('name')->pluck('name', 'id');

        return view(
            'admin.domains.create',
            compact('forestAds', 'logicalServers')
        );
    }

    public function store(StoreDomainRequest $request)
    {
        $domainAd = Domain::query()->create($request->all());
        $domainAd->forestAds()->sync($request->input('forestAds', []));

        LogicalServer::whereIn('id', $request->input('logicalServers', []))
            ->update(['domain_id' => $domainAd->id]);

        return redirect()->route('admin.domains.index');
    }

    public function edit(Domain $domain)
    {
        abort_if(Gate::denies('domaine_ad_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $forestAds = ForestAd::all()->sortBy('name')->pluck('name', 'id');
        $logicalServers = LogicalServer::all()->sortBy('name')->pluck('name', 'id');
        $domain->load('forestAds');

        return view(
            'admin.domains.edit',
            compact('domain', 'forestAds', 'logicalServers')
        );
    }

    public function update(UpdateDomainRequest $request, Domain $domain)
    {
        $domain->update($request->all());
        $domain->forestAds()->sync($request->input('forestAds', []));

        LogicalServer::where('domain_id', $domain->id)
            ->update(['domain_id' => null]);

        LogicalServer::whereIn('id', $request->input('logicalServers', []))
            ->update(['domain_id' => $domain->id]);

        return redirect()->route('admin.domains.index');
    }

    public function show(Domain $domain)
    {
        abort_if(Gate::denies('domaine_ad_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $domain->load('forestAds');

        return view('admin.domains.show', compact('domain'));
    }

    public function destroy(Domain $domain)
    {
        abort_if(Gate::denies('domaine_ad_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $domain->delete();

        return redirect()->route('admin.domains.index');
    }

    public function massDestroy(MassDestroyDomainRequest $request)
    {
        Domain::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
