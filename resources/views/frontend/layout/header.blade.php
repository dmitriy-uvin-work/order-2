<header>
    <div class="header">
        <div class="fluid-container">
            <div class="left-side mobile-show">
                <button class="menu-btn"></button>
                <button class="search-btn popup-btn" data-class="search-modal"></button>
            </div>
            <nav>
                <a href="{{ route('catalog') }}" class="catalog-drop-btn">Каталог</a>
                <a href="{{ route('brand.list') }}">Бренды</a>
                <a href="{{ route('catalog', ['slug'=>'new']) }}">Новинки</a>
                <a href="{{ route('blog.list') }}">Блог</a>
                <a href="{{ route('stock.list') }}" class="c--main-red">Sale</a>
            </nav>
            <div class="logo">
                <a href="{{ route('home') }}"><img src="/frontend/images/logo.svg" alt=""></a>
            </div>
            <div class="right-side">
                <a href="tel:{{ $settings->phone }}" class="phone">
                    {{ $settings->phone }}
                </a>
                <div class="actions">
                    <a href="#" class="popup-btn search-btn" data-class="search-modal">
                        <img src="/frontend/images/icons/search.svg" alt="">
                    </a>
                    <a href="{{ auth()->user() ? route('profile.index') : route('profile.login') }}">
                        <img src="/frontend/images/icons/user.svg" alt="">
                    </a>
                    <a href="#" class="popup-btn" data-class="wish-modal">
                        <img src="/frontend/images/icons/wishlist.svg" alt="">
                    </a>
                    <a href="#" class="popup-btn" data-class="cart-modal">
                        <img src="/frontend/images/icons/cart.svg" alt="">
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="mobile-menu">
    <div class="mobile-menu__overlay"></div>
    <div class="mobile-menu__wrap">
        <nav>
            <a href="{{ route('catalog') }}" class="has-child catalog-drop-btn">Каталог</a>
            <a href="{{ route('brand.list') }}" class="">Бренды</a>
            <a href="{{ route('catalog', ['slug'=>'new']) }}" class="">Новинки</a>
            <a href="{{ route('blog.list') }}" class="">Блог</a>
            <a href="{{ route('stock.list') }}" class="c--main-red">Sale</a>
        </nav>
    </div>
</div>

@if (isset($categoryTree) && count($categoryTree) > 0)
    <div class="category-dropDown">
        <div class="category-dropDown__overlay"></div>
        <div class="category-dropDown__wrap">
            <div class="medium-container">
                <ul class="main-ul">
                    <li class="mobile-show">
                        <span>Каталог</span>
                    </li>
                    <li><a href="{{ route('catalog') }}">Все товары категории</a></li>
                    @foreach($categoryTree as $category)
                        <li data-id="{{ $category->id }}" class="{{ count($category->sub) > 0 ? 'has-child' : '' }}">
                            <a href="{{ route('catalog', ['slug'=>$category->slug]) }}">{{ $category->name }}</a>
                            @include('frontend.render.category-sub', ['categories'=>$category->sub, 'parent'=>$category, 'level'=>1])
                        </li>
                    @endforeach
                </ul>
                <div class="img-box">
                    @foreach($categoryTree as $category)
                        @if(!empty($category->image))
                            <div data-category-id="{{ $category->id }}">
                                <img src="{{ $category->getImg('image', 'medium') }}" alt="">
                            </div>
                        @endif
                        @include('frontend.render.category-sub-img', ['categories'=>$category->sub])
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif
