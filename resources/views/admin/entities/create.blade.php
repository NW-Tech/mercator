@extends('layouts.admin')
@section('content')

    <form method="POST" action="{{ route("admin.entities.store") }}" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                {{ trans('global.create') }} {{ trans('cruds.entity.title_singular') }}
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required" for="name">{{ trans('cruds.entity.fields.name') }}</label>
                            <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text"
                                   name="name" id="name" value="{{ old('name', '') }}" required maxlength="64"
                                   autofocus/>
                            @if($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.entity.fields.name_helper') }}</span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="entity_type">{{ trans('cruds.entity.fields.entity_type') }}</label>
                            <select class="form-control select2-free {{ $errors->has('entity_type') ? 'is-invalid' : '' }}"
                                    name="entity_type" id="entity_type">
                                <option></option>
                                @foreach($entityTypes as $t)
                                    <option {{ old('entity_type') == $t ? 'selected' : '' }}>{{$t}}</option>
                                @endforeach
                                @if (!$entityTypes->contains(old('entity_type')))
                                    <option {{ old('entity_type') ? 'selected' : ''}}> {{ old('entity_type') }}</option>
                                    '
                                @endif
                            </select>
                            @if($errors->has('entity_type'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('entity_type') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.entity.fields.entity_type_helper') }}</span>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="processes">{{ trans('cruds.entity.fields.parent_entity') }}</label>
                            <select class="form-control select2 {{ $errors->has('processes') ? 'is-invalid' : '' }}"
                                    name="parent_entity_id" id="parent_entity_id">
                                <option></option>
                                @foreach($entities as $id => $name)
                                    <option value="{{ $id }}" {{ old('entity')==$id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('processes'))
                                <span class="text-danger">{{ $errors->first('parent_entity') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.entity.fields.parent_entity_helper') }}</span>
                        </div>
                    </div>

                    <div class="col-md-1">
                        <br>
                        <div class="form-group">
                            <div class="form-check form-switch">
                                <input name="is_external" id='is_external' type="checkbox" value="1"
                                       class="form-check-input" {{ old('is_external') ? 'checked="checked"' : '' }}>
                                <label for="is_external">{{ trans('cruds.entity.fields.is_external') }}</label>
                            </div>
                            @if($errors->has('is_external'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('is_external') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.entity.fields.is_external_helper') }}</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-9">
                        <div class="form-group">
                            <label class="recommended1"
                                   for="description">{{ trans('cruds.entity.fields.description') }}</label>
                            <textarea
                                    class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}"
                                    name="description" id="description">{!! old('description') !!}</textarea>
                            @if($errors->has('description'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('description') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.entity.fields.description_helper') }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="iconSelect">{{ trans('global.icon_select') }}</label>
                            <select id="iconSelect"
                                    name="iconSelect"
                                    class="form-control js-icon-picker"
                                    data-icons='@json($icons)'
                                    data-selected="-1"
                                    data-default-img="{{ asset('images/application.png') }}"
                                    data-url-template="{{ route('admin.documents.show', ':id') }}"
                                    data-upload="#iconFile">
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="file" id="iconFile" name="iconFile" accept="image/png"/>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="recommended1"
                           for="contact_point">{{ trans('cruds.entity.fields.contact_point') }}</label>
                    <textarea class="form-control ckeditor {{ $errors->has('contact_point') ? 'is-invalid' : '' }}"
                              name="contact_point" id="contact_point">{!! old('contact_point') !!}</textarea>
                    @if($errors->has('contact_point'))
                        <div class="invalid-feedback">
                            {{ $errors->first('contact_point') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.entity.fields.contact_point_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="recommended2"
                           for="seurity_level">{{ trans('cruds.entity.fields.security_level') }}</label>
                    <textarea class="form-control ckeditor {{ $errors->has('security_level') ? 'is-invalid' : '' }}"
                              name="security_level" id="security_level">{!! old('security_level') !!}</textarea>
                    @if($errors->has('security_level'))
                        <div class="invalid-feedback">
                            {{ $errors->first('security_level') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.entity.fields.security_level_helper') }}</span>
                </div>


                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="applications">{{ trans('cruds.entity.fields.applications_resp') }}</label>
                            <select class="form-control select2 {{ $errors->has('respApplications') ? 'is-invalid' : '' }}"
                                    name="respApplications[]" id="respApplications" multiple>
                                @foreach($applications as $id => $name)
                                    <option value="{{ $id }}" {{ in_array($id, old('respApplications', [])) ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('respApplications'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('respApplications') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.entity.fields.applications_resp_helper') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="databases">{{ trans('cruds.entity.fields.databases_resp') }}</label>
                            <select class="form-control select2 {{ $errors->has('databases') ? 'is-invalid' : '' }}"
                                    name="databases[]" id="databases" multiple>
                                @foreach($databases as $id => $name)
                                    <option value="{{ $id }}" {{ in_array($id, old('databases', [])) ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('databases'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('databases') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.entity.fields.databases_resp_helper') }}</span>
                        </div>
                    </div>
                </div>


                <div class="form-group">
                    <label class="recommended" for="processes">{{ trans('cruds.entity.fields.processes') }}</label>
                    <div style="padding-bottom: 4px">
                        <span class="btn btn-info btn-xs select-all"
                              style="border-radius: 0">{{ trans('global.select_all') }}</span>
                        <span class="btn btn-info btn-xs deselect-all"
                              style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                    </div>
                    <select class="form-control select2 {{ $errors->has('processes') ? 'is-invalid' : '' }}"
                            name="processes[]" id="activities" multiple>
                        @foreach($processes as $id => $name)
                            <option value="{{ $id }}" {{ in_array($id, old('processes', [])) ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('processes'))
                        <div class="invalid-feedback">
                            {{ $errors->first('processes') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.entity.fields.processes_helper') }}</span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.entities.index') }}">
                {{ trans('global.back_to_list') }}
            </a>
            <button id="btn-save" class="btn btn-danger" type="submit">
                {{ trans('global.save') }}
            </button>
        </div>
    </form>
@endsection

