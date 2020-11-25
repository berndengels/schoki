<div class="form-group row">
    <label class="col-md-2 col-form-label">{{ ucfirst($name) }}</label>
    <div class="col-md-auto form-check-inline float-left ml-3">
        @foreach($options as $index => $option)
            <input
                type="radio"
                id="{{ $option }}"
                name="{{ $name }}"
                value="{{ $option }}"
                class="form-control form-check-input @error('title') is-invalid @enderror"
                @if($option === $value) checked @endif
            />
            <label for="{{ $name.$index }}" class="col-form-label form-check-label mr-4">{{ $label ?? ucwords($option) }}</label>
        @endforeach
        {{ $slot }}

        @error($name)
        <span class="d-block invalid-feedback" role="alert">
            <strong>{{ $errors->first($name) }}</strong>
        </span>
        @enderror
    </div>
</div>
