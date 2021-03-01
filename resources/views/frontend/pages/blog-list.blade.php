@extends('frontend.layout.master')

@section('section')
    <div class="medium-container page-gap">
        <div class="main-title">
            <h2>Блог</h2>
        </div>
        @if(count($posts) > 0)
            <div class="blog-grid">
                @foreach($posts as $post)
                    <div>
                        @component('frontend.components.blog-card', ['post'=>$post])@endcomponent
                    </div>
                @endforeach
            </div>
            <div class="section-gap">
                {{ $posts->links('frontend.components.pagination') }}
            </div>
        @else
            @component('frontend.components.empty-data', ['text'=>'В этом разделе скоро появятся новости']) @endcomponent
        @endif

        <div class="subscribe">
            <div class="main-title">
                <h2 class="mb-30">Подпишитесь на рассылку</h2>
                <span class="c--main-grey">Чтобы быть в курсе всех акций и новинок, подпишитесь на нашу рассылку</span>
            </div>
            <div class="subscribe__form">
                <input type="email" title="" placeholder="Email" name="email" autocomplete="off">
                <button class="btn btn--black">подписаться</button>
            </div>
        </div>
    </div>
@endsection
