@php
    $actionId    = $action[0];
    $actionLabel = $action[1];
    $isChecked   = in_array($actionId, old('permissions', [])) || ($role && $role->permissions->contains($actionId));
    $sizeClass   = $disabled ? '' : ' form-switch-lg';
@endphp
<div class="form-switch{{ $sizeClass }}">
    <input class="form-check-input" type="checkbox" name="permissions[]"
        data-check="{{ $permission['name'] }}"
        id="perm_{{ $actionId }}"
        value="{{ $actionId }}"
        @disabled($disabled)
        @checked($isChecked)>
    <label class="form-check-label" for="for_{{ $actionId }}">{{ $actionLabel=='access' ? 'list' : $actionLabel  }}</label>
</div>
