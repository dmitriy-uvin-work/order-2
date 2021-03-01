@extends('frontend.layout.master')

@section('section')
    <div class="page-gap">
        <div class="small-container">
            <div class="main-title">
                <h2>{{ $post->title }}</h2>
            </div>
            <span class="c--main-grey">{{ $post->getDate() }}</span>
            <div class="banner-img mt-30 mb-30">
                <img src="{{ $post->getImg('image', 'large') }}" alt="">
            </div>
            <div class="rich-box-style rich-box-style--grey">
                {!! $post->body !!}
            </div>
            <div class="mt-80">
                <div class="c--main-grey mb-30">Поделиться в соц сетях</div>

                @php
                    $title = '';
                    $summary = '';
                    $url = '';
                    $image_url = '';

                    if(isset($post)){
                        $title = $post->title;
                        $summary = $post->short_description;
                        $url = urlencode(route('blog.view', ['slug'=>$post->slug]));
                        $image_url = $post->getImg('image', 'thumb');
                    }
                @endphp
                <div class="circle-socials circle-socials--white">
                    <a href="http://twitter.com/share?text={{ $title }}&url={{ $url }}"
                       onclick="window.open(this.href, this.title, 'toolbar=0, status=0, width=548, height=325'); return false"
                       class="circle-socials__item"
                       title="Поделиться ссылкой в Твиттере"
                       target="_parent">
                        <img src="/frontend/images/icons/twitter.svg" alt="">
                    </a>

                    <a href="http://vkontakte.ru/share.php?url={{ $url }}&title={{ $title }}&description={{ $summary }}&image={{ $image_url }}&noparse=true"
                       onclick="window.open(this.href, this.title, 'toolbar=0, status=0, width=548, height=325'); return false"
                       class="circle-socials__item"
                       title="Сохранить в Вконтакте"
                       target="_parent">
                        <img src="/frontend/images/icons/vk.svg" alt="">
                    </a>

                    <a href="http://www.facebook.com/sharer.php?s=100&p[url]={{ $url }}&p[title]={{ $title }}&p[summary]={{ $summary }}&p[images][0]={{ $image_url }}"
                       onclick="window.open(this.href, this.title, 'toolbar=0, status=0, width=548, height=325'); return false"
                       class="circle-socials__item"
                       title="Поделиться ссылкой на Фейсбук"
                       target="_parent">
                        <img src="/frontend/images/icons/facebook.svg" alt="">
                    </a>
                </div>
            </div>
            @if(count($similarPosts) > 0)
                <div class="section-gap">
                    <div class="main-title">
                        <h3>Похожие статьи</h3>
                    </div>
                    <div class="blog-grid">
                        @foreach($similarPosts as $similarPost)
                            <div>
                                @component('frontend.components.blog-card', ['post' => $similarPost])@endcomponent
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
