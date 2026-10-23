{{--
    <x-alert type="success">登録しました</x-alert>

    @param string $type  success | error
--}}
@props([
    'type' => 'success',
])

<p {{ $attributes->class(['alert', "alert--{$type}"]) }} role="{{ $type === 'error' ? 'alert' : 'status' }}">{{ $slot }}</p>
