@extends('frontend.layout.master')

@section('section')
    <div class="page-gap">
        <div class="medium-container">
            <div class="main-title">
                <h2>Акции</h2>
            </div>

            @if (count($stocks) > 0)
                @foreach($stocks as $stock)
                    <div>
                        @component('frontend.components.stock-card', ['stock'=>$stock]) @endcomponent
                        <div class="row stock-card__text">
                            <div class="col-md-8">
                                @if(!empty($stock->short_description))
                                <div class="mt-30">
                                    <div class="font-16">Условия акции</div>
                                    <p class="c--main-grey mb-0">{{ $stock->short_description }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="section-gap">
                    {{ $stocks->links('frontend.components.pagination') }}
                </div>

            @else
                @component('frontend.components.empty-data', ['text'=>'В этом разделе скоро появятся акции']) @endcomponent
            @endif

        </div>
    </div>
@endsection
