@error($name, $bag)
    <div {!! $attributes->merge(['class' => 'invalid-feedback text-warning']) !!}>
        {{ $message }}
    </div>
@enderror
