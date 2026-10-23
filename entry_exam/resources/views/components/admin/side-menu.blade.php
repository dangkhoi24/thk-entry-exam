{{--
    Admin sidebar (same structure as artech-client's default layout)
    desktop: fixed column on the left
    mobile:  top bar, the menu drops down from the ☰ button (toggled in resources/js/admin/app.js)
--}}
@php
    $menuItems = [
        ['icon' => 'search', 'label' => __('admin.menu.hotel_search'), 'route' => 'adminHotelSearchPage', 'active' => ['adminHotelSearch*', 'adminHotelEdit*']],
        ['icon' => 'plus', 'label' => __('admin.menu.hotel_create'), 'route' => 'adminHotelCreatePage', 'active' => ['adminHotelCreate*']],
        ['icon' => 'calendar', 'label' => __('admin.menu.booking_search'), 'route' => 'adminBookingSearchPage', 'active' => ['adminBooking*']],
    ];
@endphp

<aside class="sidebar" data-sidebar>
    <div class="sidebar__header">
        <a class="sidebar__brand headline-2" href="{{ route('adminTop') }}">{{ __('admin.menu.title') }}</a>
        <button class="sidebar__toggle" type="button" aria-label="Menu" aria-expanded="false" aria-controls="sidebar-body" data-sidebar-toggle>
            <x-icon name="menu" class="sidebar__toggle-open" />
            <x-icon name="close" class="sidebar__toggle-close" />
        </button>
    </div>

    <div class="sidebar__body" id="sidebar-body">
        <nav class="sidebar__nav">
            @foreach ($menuItems as $item)
                <a @class(['menu-item body-2', 'is-selected' => request()->routeIs(...$item['active'])]) href="{{ route($item['route']) }}">
                    <x-icon :name="$item['icon']" class="menu-item__icon" />
                    <span class="menu-item__label">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>

        {{-- display language --}}
        <div class="sidebar__footer">
            @foreach (\App\Http\Middleware\SetLocale::LOCALES as $locale)
                <x-button
                    size="small"
                    :variant="app()->getLocale() === $locale ? 'primary' : 'secondary'"
                    :href="route('adminLocale', ['locale' => $locale])"
                >{{ __("admin.locale.{$locale}") }}</x-button>
            @endforeach
        </div>
    </div>
</aside>
