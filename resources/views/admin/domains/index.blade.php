@extends('layouts.admin')

@section('title')
    {{ trans('cruds.domaine.title_singular') }} {{ trans('global.list') }}
@endsection

@section('content')
    @can('domaine_ad_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a id="btn-new" class="btn btn-success" href="{{ route("admin.domains.create") }}">
                    {{ trans('global.add') }} {{ trans('cruds.domaine.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.domaine.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="dataTable" class="table table-bordered table-striped table-hover datatable">
                    <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.domaine.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.domaine.title') }}
                        </th>
                        <th>
                            {{ trans('cruds.domaine.fields.domain_ctrl_cnt') }}
                        </th>
                        <th>
                            {{ trans('cruds.domaine.fields.user_count') }}
                        </th>
                        <th>
                            {{ trans('cruds.domaine.fields.machine_count') }}
                        </th>
                        <th>
                            {{ trans('cruds.domaine.fields.relation_inter_domaine') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($domains as $domain)
                        <tr data-entry-id="{{ $domain->id }}"
                            @if (
                                ($domain->description===null)||
                                ($domain->domain_ctrl_cnt===null)||
                                ($domain->user_count===null)||
                                ($domain->machine_count===null)||
                                ($domain->relation_inter_domaine===null)
                                )
                                class="table-warning"
                                @endif
                        >
                            <td>

                            </td>
                            <td>
                                <a href="{{ route('admin.domains.show', $domain->id) }}">
                                    {{ $domain->name ?? '' }}
                                </a>
                            </td>
                            <td>
                                @foreach($domain->forestAds as $forestAd)
                                    <a href="{{ route('admin.forest-ads.show', $forestAd->id) }}">
                                        {{ $forestAd->name }}
                                    </a>{{ !$loop->last ? ',' : '' }}
                                @endforeach
                            </td>
                            <td>
                                {{ $domain->domain_ctrl_cnt ?? '' }}
                            </td>
                            <td>
                                {{ $domain->user_count ?? '' }}
                            </td>
                            <td>
                                {{ $domain->machine_count ?? '' }}
                            </td>
                            <td>
                                {{ $domain->relation_inter_domaine ?? '' }}
                            </td>
                            <td nowrap>
                                @can('domaine_ad_show')
                                    <a class="btn btn-xs btn-primary"
                                       href="{{ route('admin.domains.show', $domain->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('domaine_ad_edit')
                                    <a class="btn btn-xs btn-info"
                                       href="{{ route('admin.domains.edit', $domain->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('domaine_ad_delete')
                                    <form action="{{ route('admin.domains.destroy', $domain->id) }}"
                                          method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                          style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger"
                                               value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        @include('partials.datatable', array(
            'id' => '#dataTable',
            'title' => trans("cruds.domaine.title_singular"),
            'URL' => route('admin.domains.massDestroy'),
            'canDelete' => auth()->user()->can('domaine_ad_delete') ? true : false
        ));
    </script>
@endsection
