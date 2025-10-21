@extends('layouts.admin')
@section('content')
    <div class="form-group">
        <a class="btn btn-default" href="{{ route('admin.data-processings.index') }}">
            {{ trans('global.back_to_list') }}
        </a>

        <!--
    <a class="btn btn-success" href="{{ route('admin.report.explore') }}?node=ACTIVITY_{{$dataProcessing->id}}">
        {{ trans('global.explore') }}
        </a>
-->

        @can('data_processing_edit')
            <a class="btn btn-info" href="{{ route('admin.data-processings.edit', $dataProcessing->id) }}">
                {{ trans('global.edit') }}
            </a>
        @endcan

        @can('data_processing_delete')
            <form action="{{ route('admin.data-processings.destroy', $dataProcessing->id) }}" method="POST"
                  onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
            </form>
        @endcan
    </div>
    <div class="card">
        <div class="card-header">
            {{ trans('global.show') }} {{ trans('cruds.dataProcessing.title') }}
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped">
                <tbody>
                <tr>
                    <th width="10%">
                        {{ trans('cruds.dataProcessing.fields.name') }}
                    </th>
                    <td>
                        {{ $dataProcessing->name }}
                    </td>
                    <th width="10%">
                        {{ trans('cruds.dataProcessing.fields.legal_basis') }}
                    </th>
                    <td>
                        {{ $dataProcessing->legal_basis }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.dataProcessing.fields.description') }}
                    </th>
                    <td colspan='3'>
                        {!! $dataProcessing->description !!}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.dataProcessing.fields.responsible') }}
                    </th>
                    <td colspan='3'>
                        {!! $dataProcessing->responsible !!}
                    </td>
                </tr>

                <tr>
                    <th>
                        {{ trans('cruds.dataProcessing.fields.purpose') }}
                    </th>
                    <td colspan='3'>
                        {!! $dataProcessing->purpose !!}
                    </td>
                </tr>

                <tr>
                    <th>
                        {{ trans('cruds.dataProcessing.fields.lawfulness') }}
                    </th>
                    <td colspan="3">
                        <table width="100%">
                            <td>
                                <input class="form-check-input" type="checkbox"
                                       {{ $dataProcessing->lawfulness_consent ? "checked" : "" }} disabled>
                                {{ trans('cruds.dataProcessing.fields.lawfulness_consent') }}
                            </td>
                            <td>
                                <input class="form-check-input" type="checkbox"
                                       {{ $dataProcessing->lawfulness_contract ? "checked" : "" }} disabled>
                                {{ trans('cruds.dataProcessing.fields.lawfulness_contract') }}
                            </td>
                            <td>
                                <input class="form-check-input" type="checkbox"
                                       {{ $dataProcessing->lawfulness_legal_obligation ? "checked" : "" }} disabled>
                                {{ trans('cruds.dataProcessing.fields.lawfulness_legal_obligation') }}
                            </td>
                            <td>
                                <input class="form-check-input" type="checkbox"
                                       {{ $dataProcessing->lawfulness_vital_interest ? "checked" : "" }} disabled>
                                {{ trans('cruds.dataProcessing.fields.lawfulness_vital_interest') }}
                            </td>
                            <td>
                                <input class="form-check-input" type="checkbox"
                                       {{ $dataProcessing->lawfulness_public_interest ? "checked" : "" }} disabled>
                                {{ trans('cruds.dataProcessing.fields.lawfulness_public_interest') }}
                            </td>
                            <td>
                                <input class="form-check-input" type="checkbox"
                                       {{ $dataProcessing->lawfulness_legitimate_interest ? "checked" : "" }} disabled>
                                {{ trans('cruds.dataProcessing.fields.lawfulness_legitimate_interest') }}
                            </td>
                        </table>
                    </td>
                </tr>
                <tr>
                    <th>
                        &nbsp;
                    </th>
                    <td colspan='3'>
                        {!! $dataProcessing->lawfulness !!}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.dataProcessing.fields.categories') }}
                    </th>
                    <td colspan='3'>
                        {!! $dataProcessing->categories !!}
                    </td>
                </tr>

                <tr>
                    <th>
                        {{ trans('cruds.dataProcessing.fields.recipients') }}
                    </th>
                    <td colspan='3'>
                        {!! $dataProcessing->recipients !!}
                    </td>
                </tr>


                <tr>
                    <th>
                        {{ trans('cruds.dataProcessing.fields.transfert') }}
                    </th>
                    <td colspan='3'>
                        {!! $dataProcessing->transfert !!}
                    </td>
                </tr>

                <tr>
                    <th>
                        {{ trans('cruds.dataProcessing.fields.retention') }}
                    </th>
                    <td colspan='3'>
                        {!! $dataProcessing->retention !!}
                    </td>
                </tr>

                <tr>
                    <th>
                        {{ trans('cruds.dataProcessing.fields.processes') }}
                    </th>
                    <td colspan='3'>
                        @foreach($dataProcessing->processes as $process)
                            <a href="{{ route('admin.processes.show', $process->id) }}">{{ $process->name }}</a>
                            @if (!$loop->last)
                                ,
                            @endif
                        @endforeach
                    </td>
                </tr>

                <tr>
                    <th>
                        {{ trans('cruds.dataProcessing.fields.applications') }}
                    </th>
                    <td colspan='3'>
                        @foreach($dataProcessing->applications as $application)
                            <a href="{{ route('admin.applications.show', $application->id) }}">{{ $application->name }}</a>
                            @if (!$loop->last)
                                ,
                            @endif
                        @endforeach
                    </td>
                </tr>

                <tr>
                    <th>
                        {{ trans('cruds.dataProcessing.fields.information') }}
                    </th>
                    <td colspan='3'>
                        @foreach($dataProcessing->informations as $information)
                            <a href="{{ route('admin.information.show', $information->id) }}">{{ $information->name }}</a>
                            @if (!$loop->last)
                                ,
                            @endif
                        @endforeach
                    </td>
                </tr>

                <tr>
                    <th>
                        {{ trans('cruds.dataProcessing.fields.documents') }}
                    </th>
                    <td colspan='3'>
                        @foreach($dataProcessing->documents as $document)
                            <a href="{{ route('admin.documents.show', $document->id) }}">{{ $document->filename }}</a>
                            @if (!$loop->last)
                                ,
                            @endif
                        @endforeach
                    </td>
                </tr>

                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ trans('global.created_at') }} {{ $dataProcessing->created_at ? $dataProcessing->created_at->format(trans('global.timestamp')) : '' }}
            |
            {{ trans('global.updated_at') }} {{ $dataProcessing->updated_at ? $dataProcessing->updated_at->format(trans('global.timestamp')) : '' }}
        </div>
    </div>
    <div class="form-group">
        <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.data-processings.index') }}">
            {{ trans('global.back_to_list') }}
        </a>
    </div>
@endsection
