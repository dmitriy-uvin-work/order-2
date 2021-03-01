<div class="blog-card">
    <a href="{{ route('blog.view', ['slug'=>$post->slug]) }}" class="blog-card__img" style="background-image: url({{ $post->getImg('image', 'medium') }})"></a>
    <div class="blog-card__caption">
        <div class="b-title">{{ $post->title }}</div>
        <div class="b-date">{{ $post->date }}</div>
        <div class="b-description">{{ $post->short_description }}</div>
    </div>
</div>