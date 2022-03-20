@error($name, $bag)
    <div {!! $attributes->merge(['class' => 'row invalid-feedback text-warning']) !!}>
        {{ $message }}
    </div>
@enderror
