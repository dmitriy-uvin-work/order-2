@extends('frontend.layout.master')

@section('section')

    <div class="profile-page help-page">
        <div class="medium-container">
            <div class="profile-page__sidebar">
                <div class="page-gap">
                    <ul>
                        @if(count($pages) > 0)
                            @foreach($pages as $page)
                                <li>
                                    <a href="javascript:" class="smooth-scroll-anchor" data-id="{{ $page->slug }}">{{ $page->title }}</a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
            <div class="profile-page__content">
                <div class="page-gap">
                    @if(count($pages) > 0)
                        @foreach($pages as $page)
                           <div class="mb-30 help-page__section" id="{{ $page->slug }}">
                               <div class="main-title">
                                   <h2>{{ $page->title }}</h2>
                               </div>
                               <div class="rich-box-style">
                                   {!! $page->body !!}
                               </div>
                           </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="/frontend/libs/stickySidebar/jquery.sticky-sidebar.min.js"></script>
    <script src="/frontend/libs/stickySidebar/ResizeSensor.js"></script>
@endsection

@section('js')
    <script>
        $('.smooth-scroll-anchor').on('click', function (e) {
            e.preventDefault();
            let target = $('#'+$(this).attr('data-id'));
            $('html, body').animate({ scrollTop: target.offset().top - 30});
        });

        let sidebar = $('.profile-page__sidebar')

        sidebar.stickySidebar({
            containerSelector: '.profile-page__sidebar ul',
            resizeSensor: true
        });
    </script>
@endsection
