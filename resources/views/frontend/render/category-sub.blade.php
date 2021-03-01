@if(count($categories) > 0)
    @php
        isset($level) ? $level+=1 : $level=1;
    @endphp

    @if($level < 4)
        <ul class="secondary-ul">
            <li>
                <span class="{{ $level == 3 ? 'font-04 font-16' : '' }}">{{ $parent->name }}</span>
            </li>
            <li><a href="{{ route('catalog', ['slug'=>$parent->slug]) }}">Все товары категории</a></li>
            @foreach($categories as $category)
                <li data-id="{{ $category->id }}" class="{{ (count($category->sub) > 0 && $level < 3) ? 'has-child' : '' }}">
                    <a href="{{ route('catalog', ['slug'=>$category->slug]) }}">{{ $category->name }}</a>
                    @include('frontend.render.category-sub', ['categories'=>$category->sub, 'parent'=>$category, 'level'=>$level])
                </li>
            @endforeach
        </ul>
    @endif
@endif
