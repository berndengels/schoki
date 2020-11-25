<div class="form-group row">
    <label for="{{ $name }}" class="col-md-2 col-form-label">{{ $label ?? ucwords($name) }}</label>
    <div class="col-md-10">
        <select
            id="{{ $name }}"
            name="{{ $name }}"
            value="{{ $value ?? ''}}"
            class="form-control @error('title') is-invalid @enderror col-sm-12 col-md-6"
        >
            <option value="">Bitte wählen</option>
            <!-- baue options per blade function -->
            @foreach($options as $index => $option)
                @if($value == $index)
                    <option value="{{ $index }}" selected="selected">{{ $option }}</option>
                @else
                    <option value="{{ $index }}">{{ $option }}</option>
                @endif
            @endforeach
        </select>
        {{ $slot }}

        @error($name)
        <span class="d-block invalid-feedback" role="alert">
            <strong>{{ $errors->first($name) }}</strong>
        </span>
        @enderror
    </div>
</div>
