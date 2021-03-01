<ul>
    <li class="{{ request()->is('profile') ? 'active' : '' }}">
        <a href="{{ route('profile.index') }}"><span><img src="/frontend/images/icons/user.svg" alt=""></span>Персональные данные</a>
    </li>
    <li class="{{ request()->is('profile/favorite') ? 'active' : '' }}">
        <a href="{{ route('profile.favorite') }}"><span><img src="/frontend/images/icons/wishlist.svg" alt=""></span>Избранное</a>
    </li>
    <li>
        <a href="{{ route('profile.purchasing') }}"><span><img src="/frontend/images/icons/cart.svg" alt=""></span>Корзина</a>
    </li>
    <li class="{{ request()->is('profile/history') ? 'active' : '' }}">
        <a href="{{ route('profile.history.list') }}"><span><img src="/frontend/images/icons/menu.svg" alt=""></span>История заказов</a>
    </li>
    <li>
        <a href="javascript:" onclick="getElementById('logout-form').submit()" class="logout">Выход</a>
    </li>
</ul>

<form id="logout-form" action="{{ route('profile.logout') }}" method="post" hidden>{{ csrf_field() }}</form>
