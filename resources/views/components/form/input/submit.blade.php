<div class="form-group row">
    <div class="col-md-auto float-right">
        <input
            type="submit"
            id="{{ $name }}"
            name="{{ $name }}"
            value="{{ $value ?? 'Submit' }}"
            role="button"
            class="btn btn-primary col-md-auto px-5"
        />
        {{ $slot }}
    </div>
</div>
