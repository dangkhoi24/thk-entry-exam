{{--
    Flash messages as toasts at the bottom right (like artech-ui's Toast)
    set by redirect()->with('successMessage' | 'errorMessage' | 'infoMessage'), placed once in the admin layout.
    Auto dismissed / closed by resources/js/admin/app.js
--}}
@php
    $toasts = array_filter([
        ['variant' => 'positive', 'icon' => 'check-circle', 'message' => session('successMessage')],
        ['variant' => 'negative', 'icon' => 'warning-circle', 'message' => session('errorMessage')],
        ['variant' => 'neutral', 'icon' => 'info-circle', 'message' => session('infoMessage')],
    ], fn ($toast) => $toast['message']);
@endphp

<div class="toast-group" aria-live="polite">
    @foreach ($toasts as $toast)
        <div class="toast toast-{{ $toast['variant'] }}" role="{{ $toast['variant'] === 'negative' ? 'alert' : 'status' }}" data-toast>
            <x-icon :name="$toast['icon']" class="toast__icon" />
            <p class="toast__description">{{ $toast['message'] }}</p>
            <button class="toast__close" type="button" aria-label="Close" data-toast-close>
                <x-icon name="close" />
            </button>
        </div>
    @endforeach
</div>
