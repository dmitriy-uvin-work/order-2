@extends('frontend.layout.master')

@section('section')
    <div class="page-gap">
        <div class="small-container">
            <div class="main-title">
                <h2>{{ $data->title }}</h2>
            </div>
            <div class="rich-box-style rich-box-style--grey">
                {!! $data->body !!}
            </div>
        </div>
    </div>
@endsection
