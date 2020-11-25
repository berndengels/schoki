<div class="form-group row">
    <label for="{{ $name }}" class="col-md-2 col-form-label">{{ $label ?? ucwords($name) }}</label>
    <div class="col-md-10">
        <textarea
            cols="50"
            rows="6"
            id="{{ $name }}"
            name="{{ $name }}"
            class="form-control @error('title') is-invalid @enderror col-sm-12 col-md-6 px-1"
        >{{ $value ?? '' }}</textarea>
        {{ $slot }}

        @error($name)
        <span class="d-block invalid-feedback" role="alert">
            <strong>{{ $errors->first($name) }}</strong>
        </span>
        @enderror
    </div>
</div>
