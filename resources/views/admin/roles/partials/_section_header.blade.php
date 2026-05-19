@php
    $hdrActionId = $permission['actions'][0][0];
    $hdrChecked  = in_array($hdrActionId, old('permissions', [])) || ($role && $role->permissions->contains($hdrActionId));
    $hdrSize     = $disabled ? '' : ' form-switch-lg';
@endphp
<div class="card-header">
    <div class="form-switch{{ $hdrSize }}">
        <input class="form-check-input" type="checkbox" name="permissions[]"
            data-check="{{ $permission['name'] }}"
            id="perm_{{ $hdrActionId }}"
            value="{{ $hdrActionId }}"
            @disabled($disabled)
            @checked($hdrChecked)>
        <label class="form-check-label"><b>{{ $label }}</b></label>
    </div>
</div>
