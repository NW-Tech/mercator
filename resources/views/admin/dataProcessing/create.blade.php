@extends('layouts.admin')
@section('content')
    <form method="POST" action="{{ route("admin.data-processings.store") }}" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                {{ trans('global.create') }} {{ trans('cruds.dataProcessing.title_singular') }}
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <div class="form-group">
                            <label class="required" for="name">{{ trans('cruds.dataProcessing.fields.name') }}</label>
                            <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text"
                                   name="name" id="name" maxlength=64 value="{{ old('name', '') }}" required autofocus/>
                            @if($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.dataProcessing.fields.name_helper') }}</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="referent">{{ trans('cruds.dataProcessing.fields.legal_basis') }}</label>
                            <select class="form-control select2-free {{ $errors->has('legal_basis') ? 'is-invalid' : '' }}"
                                    multiple name="legal_bases[]" id="legal_bases">
                                <option></option>
                                @foreach($legal_basis_list as $lb)
                                    <option {{ str_contains(old('legal_bases') ,$lb) ? 'selected' : '' }}>{{$lb}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('legal_basis'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('legal_basis') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.dataProcessing.fields.legal_basis_helper') }}</span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="recommended"
                           for="description">{{ trans('cruds.dataProcessing.fields.description') }}</label>
                    <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}"
                              name="description" id="description">{!! old('description') !!}</textarea>
                    @if($errors->has('description'))
                        <div class="invalid-feedback">
                            {{ $errors->first('description') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.dataProcessing.fields.description_helper') }}</span>
                </div>

                <div class="form-group">
                    <label class="recommended"
                           for="responsible">{{ trans('cruds.dataProcessing.fields.responsible') }}</label>
                    <textarea class="form-control ckeditor {{ $errors->has('responsible') ? 'is-invalid' : '' }}"
                              name="responsible" id="responsible">{!! old('responsible') !!}</textarea>
                    @if($errors->has('responsible'))
                        <div class="invalid-feedback">
                            {{ $errors->first('responsible') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.dataProcessing.fields.responsible_helper') }}</span>
                </div>

                <div class="form-group">
                    <label class="recommended" for="purpose">{{ trans('cruds.dataProcessing.fields.purpose') }}</label>
                    <textarea class="form-control ckeditor {{ $errors->has('purpose') ? 'is-invalid' : '' }}"
                              name="purpose" id="purpose">{!! old('purpose') !!}</textarea>
                    @if($errors->has('purpose'))
                        <div class="invalid-feedback">
                            {{ $errors->first('purpose') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.dataProcessing.fields.purpose_helper') }}</span>
                </div>

                <div class="form-group">
                    <label class="recommended"
                           for="lawfulness">{{ trans('cruds.dataProcessing.fields.lawfulness') }}</label>

                    <table width="100%">
                        <td>
                            <input class="form-check-input" type="checkbox" name="lawfulness_consent" value="1"
                                    {{ old('lawfulness_consent') ? "checked" : "" }}>
                            {{ trans('cruds.dataProcessing.fields.lawfulness_consent') }}
                        </td>
                        <td>
                            <input class="form-check-input" type="checkbox" name="lawfulness_contract" value="1"
                                    {{ old('lawfulness_contract') ? "checked" : "" }}>
                            {{ trans('cruds.dataProcessing.fields.lawfulness_contract') }}
                        </td>
                        <td>
                            <input class="form-check-input" type="checkbox" name="lawfulness_legal_obligation" value="1"
                                    {{ old('lawfulness_legal_obligation') ? "checked" : "" }}>
                            {{ trans('cruds.dataProcessing.fields.lawfulness_legal_obligation') }}
                        </td>
                        <td>
                            <input class="form-check-input" type="checkbox" name="lawfulness_vital_interest" value="1"
                                    {{ old('lawfulness_vital_interest') ? "checked" : "" }}>
                            {{ trans('cruds.dataProcessing.fields.lawfulness_vital_interest') }}
                        </td>
                        <td>
                            <input class="form-check-input" type="checkbox" name="lawfulness_public_interest" value="1"
                                    {{ old('lawfulness_public_interest') ? "checked" : "" }}>
                            {{ trans('cruds.dataProcessing.fields.lawfulness_public_interest') }}
                        </td>
                        <td>
                            <input class="form-check-input" type="checkbox" name="lawfulness_legitimate_interest"
                                   value="1"
                                    {{ old('lawfulness_legitimate_interest') ? "checked" : "" }}>
                            {{ trans('cruds.dataProcessing.fields.lawfulness_legitimate_interest') }}
                        </td>
                    </table>

                    <textarea class="form-control ckeditor {{ $errors->has('lawfulness') ? 'is-invalid' : '' }}"
                              name="lawfulness"
                              id="lawfulness">{!! old('lawfulness') !!}</textarea>
                    @if($errors->has('lawfulness'))
                        <div class="invalid-feedback">
                            {{ $errors->first('lawfulness') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.dataProcessing.fields.lawfulness_helper') }}</span>
                </div>


                <div class="form-group">
                    <label class="recommended"
                           for="categories">{{ trans('cruds.dataProcessing.fields.categories') }}</label>
                    <textarea class="form-control ckeditor {{ $errors->has('categories') ? 'is-invalid' : '' }}"
                              name="categories" id="categories">{!! old('categories') !!}</textarea>
                    @if($errors->has('categories'))
                        <div class="invalid-feedback">
                            {{ $errors->first('categories') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.dataProcessing.fields.categories_helper') }}</span>
                </div>

                <div class="form-group">
                    <label class="recommended"
                           for="recipients">{{ trans('cruds.dataProcessing.fields.recipients') }}</label>
                    <textarea class="form-control ckeditor {{ $errors->has('recipients') ? 'is-invalid' : '' }}"
                              name="recipients" id="recipients">{!! old('recipients') !!}</textarea>
                    @if($errors->has('recipients'))
                        <div class="invalid-feedback">
                            {{ $errors->first('recipients') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.dataProcessing.fields.recipients_helper') }}</span>
                </div>

                <div class="form-group">
                    <label class="recommended"
                           for="transfert">{{ trans('cruds.dataProcessing.fields.transfert') }}</label>
                    <textarea class="form-control ckeditor {{ $errors->has('transfert') ? 'is-invalid' : '' }}"
                              name="transfert" id="transfert">{!! old('transfert') !!}</textarea>
                    @if($errors->has('transfert'))
                        <div class="invalid-feedback">
                            {{ $errors->first('transfert') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.dataProcessing.fields.transfert_helper') }}</span>
                </div>

                <div class="form-group">
                    <label class="recommended"
                           for="retention">{{ trans('cruds.dataProcessing.fields.retention') }}</label>
                    <textarea class="form-control ckeditor {{ $errors->has('retention') ? 'is-invalid' : '' }}"
                              name="retention" id="retention">{!! old('retention') !!}</textarea>
                    @if($errors->has('retention'))
                        <div class="invalid-feedback">
                            {{ $errors->first('retention') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.dataProcessing.fields.retention_helper') }}</span>
                </div>


                <div class="form-group">
                    <label for="operations">{{ trans('cruds.dataProcessing.fields.processes') }}</label>
                    <div style="padding-bottom: 4px">
                        <span class="btn btn-info btn-xs select-all"
                              style="border-radius: 0">{{ trans('global.select_all') }}</span>
                        <span class="btn btn-info btn-xs deselect-all"
                              style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                    </div>
                    <select class="form-control select2 {{ $errors->has('processes') ? 'is-invalid' : '' }}"
                            name="processes[]" id="processes" multiple>
                        @foreach($processes as $id => $processes)
                            <option value="{{ $id }}" {{ in_array($id, old('processes', [])) ? 'selected' : '' }}>{{ $processes }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('processes'))
                        <div class="invalid-feedback">
                            {{ $errors->first('processes') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.dataProcessing.fields.processes_helper') }}</span>
                </div>


                <div class="form-group">
                    <label for="operations">{{ trans('cruds.dataProcessing.fields.applications') }}</label>
                    <div style="padding-bottom: 4px">
                        <span class="btn btn-info btn-xs select-all"
                              style="border-radius: 0">{{ trans('global.select_all') }}</span>
                        <span class="btn btn-info btn-xs deselect-all"
                              style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                    </div>
                    <select class="form-control select2 {{ $errors->has('applications') ? 'is-invalid' : '' }}"
                            name="applications[]" id="applications" multiple>
                        @foreach($applications as $id => $application)
                            <option value="{{ $id }}" {{ in_array($id, old('informations', [])) ? 'selected' : '' }}>{{ $application }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('applications'))
                        <div class="invalid-feedback">
                            {{ $errors->first('applications') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.dataProcessing.fields.applications_helper') }}</span>
                </div>

                <div class="form-group">
                    <label for="operations">{{ trans('cruds.dataProcessing.fields.information') }}</label>
                    <div style="padding-bottom: 4px">
                        <span class="btn btn-info btn-xs select-all"
                              style="border-radius: 0">{{ trans('global.select_all') }}</span>
                        <span class="btn btn-info btn-xs deselect-all"
                              style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                    </div>
                    <select class="form-control select2 {{ $errors->has('informations') ? 'is-invalid' : '' }}"
                            name="informations[]" id="informations" multiple>
                        @foreach($informations as $id => $information)
                            <option value="{{ $id }}" {{ in_array($id, old('informations', [])) ? 'selected' : '' }}>{{ $information }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('informations'))
                        <div class="invalid-feedback">
                            {{ $errors->first('informations') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.dataProcessing.fields.information_helper') }}</span>
                </div>

                <div class="form-group">
                    <label class="recommended"
                           for="controls">{{ trans('cruds.dataProcessing.fields.documents') }}</label>
                    <div class="dropzone dropzone-previews" id="dropzoneFileUpload"></div>
                    @if($errors->has('documents'))
                        <div class="invalid-feedback">
                            {{ $errors->first('documents') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.dataProcessing.fields.documents_helper') }}</span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.data-processings.index') }}">
                {{ trans('global.back_to_list') }}
            </a>
            <button id="btn-save" class="btn btn-danger" type="submit">
                {{ trans('global.save') }}
            </button>
        </div>
    </form>
@endsection

@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            var image_uploader = new window.Dropzone("#dropzoneFileUpload", {
                url: '/admin/documents/store',
                headers: {'x-csrf-token': '{{csrf_token()}}'},
                paramName: 'file',
                addRemoveLinks: true,
                timeout: 50000,
                removedfile: function (file) {
                    console.log("remove file " + file.name + " " + file.id);
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}'
                        },
                        type: 'GET',
                        url: '{{ url( "/admin/documents/delete" ) }}' + "/" + file.id,
                        success: function (data) {
                            console.log("File has been successfully removed");
                        },
                        error: function (e) {
                            console.log("File not removed");
                            console.log(e);
                        }
                    });
                    // console.log('{{ url( "/documents/delete" ) }}'+"/"+file.id+']');
                    var fileRef;
                    return (fileRef = file.previewElement) != null ?
                        fileRef.parentNode.removeChild(file.previewElement) : void 0;
                },
                success: function (file, response) {
                    file.id = response.id;
                    console.log("success response");
                    console.log(response);
                },
                error: function (file, response) {
                    console.log("error response");
                    console.log(response);
                    return false;
                },
                init: function () {
                    //Add existing files into dropzone
                    var existingFiles = [
                            @foreach(session()->get("documents") as $document)
                        {
                            name: "{{ $document->filename }}", size: {{ $document->size }}, id: {{ $document->id }}
                        },
                        @endforeach
                    ];
                    for (i = 0; i < existingFiles.length; i++) {
                        this.emit("addedfile", existingFiles[i]);
                        this.emit("complete", existingFiles[i]);
                    }
                }
            });
        });
    </script>
@endsection
