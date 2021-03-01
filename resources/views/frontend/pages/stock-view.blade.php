@extends('frontend.layout.master')

@section('section')
    <div class="page-gap stock-view">
        <div class="small-container">
            <div class="stock-card stock-card--single">
                <a href="#" class="stock-card__item stock-card__item--{{$stock->color}}" style="background-image: url({{ $stock->getImg('image', 'large') }})">
                    <span class="s-brand"><img src="/frontend/images/pics/brand1.png" alt=""></span>
                    <div class="s-title">{{ $stock->name }}</div>
                    <span class="s-note">{{ $stock->note }}</span>
                </a>
            </div>

            <div class="rich-box-style rich-box-style--grey">
                {!! $stock->body !!}
            </div>

            <div class="mt-50">
                <a href="{{ route('catalog') }}" class="btn btn--black">перейти к покупкам</a>
            </div>
        </div>
    </div>
@endsection
