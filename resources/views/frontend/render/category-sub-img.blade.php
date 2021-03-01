@if(count($categories) > 0)
    @foreach($categories as $category)
        @if(!empty($category->image))
            <div data-category-id="{{ $category->id }}">
                <img src="{{ $category->getImg('image', 'medium') }}" alt="">
            </div>
        @endif
        @include('frontend.render.category-sub-img', ['categories'=>$category->sub])
    @endforeach
@endif
