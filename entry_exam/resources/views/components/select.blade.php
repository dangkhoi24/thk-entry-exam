{{--
    Select enhanced by Tom Select (resources/js/admin/app.js). Other attributes go to the <select>.
    <x-select name="prefecture_id" :options="$prefectureOptions" :selected="old('prefecture_id')" placeholder="選択してください" />

    @param array|Collection $options  value => label
    @param mixed $selected
    @param string|null $placeholder   empty option shown first (choosing it clears the value)
--}}
@props([
    'options' => [],
    'selected' => null,
    'placeholder' => null,
])

<select {{ $attributes->merge(['id' => $attributes->get('name')])->class('select') }} data-select>
    @if ($placeholder !== null)
        <option value="">{{ $placeholder }}</option>
    @endif
    @foreach ($options as $value => $label)
        <option value="{{ $value }}" @selected((string) $selected === (string) $value)>{{ $label }}</option>
    @endforeach
</select>
