@if (isset($MENU_TOP))
    <div id="menu_top">
        <ul>
    @foreach( $MENU_TOP as $key=>$menu )
            <li><a href="{{ route('adtech.core.menu.tab', ['tab' => $menu->group]) }}" class="{{ ($menu->group == session('tab')) ? 'active' : '' }}">{{ $menu->group }}</a></li>
    @endforeach
        </ul>
    </div>
@endif