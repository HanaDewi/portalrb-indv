@php
    $type = $type ?? 'side'; // 'mobile' or 'side'
    $menuUrl = $menu['url'] ?? null;

    $isActive = $menuUrl ? (request()->is($menuUrl) || request()->is($menuUrl . '/*')) : false;
    $menuActiveClass = $type === 'mobile' ? 'menu--active' : 'side-menu--active';
    $menuOpenClass = $type === 'mobile' ? 'menu--open' : 'side-menu--open';
    $subOpenClass = $type === 'mobile' ? 'menu__sub-open' : 'side-menu__sub-open';

    $active = $isActive ? $menuActiveClass : '';
    $open = $isActive ? $menuOpenClass : '';
    $url = isset($menu['items']) ? 'javascript:;' : ($menuUrl ? url($menuUrl) : 'javascript:;');
    $subicon = isset($menu['items'])
        ? ($isActive
            ? '<div class="' . ($type === 'mobile' ? 'menu' : 'side-menu') . '__sub-icon transform rotate-180"><i data-lucide="chevron-down"></i></div>'
            : '<div class="' . ($type === 'mobile' ? 'menu' : 'side-menu') . '__sub-icon"><i data-lucide="chevron-down"></i></div>')
        : '';
    $subopen = $isActive ? $subOpenClass : '';
@endphp

@if (in_array('devider', $menu['levels']))
    @if ($type === 'mobile')
        <li class="menu__devider my-6"></li>
    @else
        <div class="side-nav__devider my-6"></div>
    @endif
@elseif (in_array(auth()->user()->level, $menu['levels']))
    <li>
        <a href="{{ $url }}" class="{{ $type === 'mobile' ? 'menu' : 'side-menu' }} {{ $active }} {{ $open }}">
            <div class="{{ $type === 'mobile' ? 'menu' : 'side-menu' }}__icon">
                <i data-lucide="{{ $menu['icon'] }}"></i>
            </div>
            <div class="{{ $type === 'mobile' ? 'menu' : 'side-menu' }}__title">
                {{ $menu['title'] }} {!! $subicon !!}
            </div>
        </a>

        @isset($menu['items'])
            <ul class="{{ $subopen }}">
                @foreach ($menu['items'] as $item)
                    @if (in_array(auth()->user()->level, $item['levels']))
                        @php
                            $itemUrlPath = $item['url'] ?? null;
                            $itemIsActive = $itemUrlPath ? (request()->is($itemUrlPath) || request()->is($itemUrlPath . '/*')) : false;
                            $itemActive = $itemIsActive ? $menuActiveClass : '';
                            $itemOpen = $itemIsActive ? $subOpenClass : '';
                            $itemUrl = isset($item['items']) && count($item['items']) > 0 ? 'javascript:;' : ($itemUrlPath ? url($itemUrlPath) : 'javascript:;');
                            $itemSubicon = isset($item['items'])
                                ? '<div class="' . ($type === 'mobile' ? 'menu' : 'side-menu') . '__sub-icon"><i data-lucide="chevron-down"></i></div>'
                                : '';
                        @endphp
                        <li>
                            <a href="{{ $itemUrl }}" class="{{ $type === 'mobile' ? 'menu' : 'side-menu' }} {{ $itemActive }}">
                                <div class="{{ $type === 'mobile' ? 'menu' : 'side-menu' }}__icon">
                                    <i data-lucide="{{ $item['icon'] }}"></i>
                                </div>
                                <div class="{{ $type === 'mobile' ? 'menu' : 'side-menu' }}__title">
                                    {{ $item['title'] }} {!! $itemSubicon !!}
                                </div>
                            </a>

                            @isset($item['items'])
                                <ul class="{{ $itemOpen }}">
                                    @foreach ($item['items'] as $subitem)
                                        @php
                                            $subitemUrlPath = $subitem['url'] ?? null;
                                            $subitemIsActive = $subitemUrlPath ? (request()->is($subitemUrlPath) || request()->is($subitemUrlPath . '/*')) : false;
                                            $subitemActive = $subitemIsActive ? $menuActiveClass : '';
                                        @endphp
                                        @if (in_array(auth()->user()->level, $subitem['levels']))
                                            <li>
                                                <a href="{{ $subitemUrlPath ? url($subitemUrlPath) : 'javascript:;' }}" class="{{ $type === 'mobile' ? 'menu' : 'side-menu' }} {{ $subitemActive }}">
                                                    <div class="{{ $type === 'mobile' ? 'menu' : 'side-menu' }}__icon">
                                                        <i data-lucide="{{ $subitem['icon'] }}"></i>
                                                    </div>
                                                    <div class="{{ $type === 'mobile' ? 'menu' : 'side-menu' }}__title">
                                                        {{ $subitem['title'] }}
                                                    </div>
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            @endisset
                        </li>
                    @endif
                @endforeach
            </ul>
        @endisset
    </li>
@endif
