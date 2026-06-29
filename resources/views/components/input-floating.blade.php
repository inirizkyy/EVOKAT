@props(['disabled' => false, 'name', 'label', 'type' => 'text', 'required' => false, 'value' => '', 'autofocus' => false])

<div class="relative group pb-1">
    <input 
        type="{{ $type }}" 
        name="{{ $name }}" 
        id="{{ $name }}" 
        value="{{ old($name, $value) }}"
        {{ $disabled ? 'disabled' : '' }} 
        {{ $required ? 'required' : '' }}
        {{ $autofocus ? 'autofocus' : '' }}
        placeholder=" "
        {!! $attributes->merge(['class' => 'block w-full rounded-xl border border-border-default-medium bg-transparent shadow-inset text-[15px] text-heading py-3.5 px-4 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand peer transition-all']) !!}
    >
    <label for="{{ $name }}" class="absolute text-[15px] text-body-subtle duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-brand peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-3 pointer-events-none transition-all">
        {{ $label }} @if($required) <span class="text-fg-danger">*</span> @endif
    </label>
    @error($name) <div class="text-xs text-fg-danger mt-1">{{ $message }}</div> @enderror
</div>
