@extends('frontend.layout.master')

@section('section')

    <div class="profile-page">
        <div class="medium-container">
            <div class="profile-page__sidebar">
                <div class="page-gap">
                    @include('frontend.profile.menu')
                </div>
            </div>
            <div class="profile-page__content">
                <div class="page-gap">
                    <div class="main-title">
                        <h2>Персональные данные</h2>
                    </div>
                    <form action="{{ route('profile.update') }}" method="post">
                        {{ csrf_field() }}
                        <div class="row mb-30">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text"
                                           name="name"
                                           class="form-control"
                                           placeholder="Имя"
                                           title=""
                                           value="{{ old('surname') ?? auth()->user()->name }}"
                                           autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <input type="text"
                                           name="surname"
                                           class="form-control"
                                           placeholder="Фамилия"
                                           title=""
                                           value="{{ old('surname') ?? auth()->user()->surname }}"
                                           autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <div class="type-phone">
                                        <input type="text" class="form-control" placeholder="Телефон" minlength="9" maxlength="9" title="" value="{{ auth()->user()->phone }}" readonly>
                                        <span>+998</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control" placeholder="Email" title="" value="{{ auth()->user()->email }}" readonly>
                                </div>
                                <div class="form-group">
                                    <div class="type-password">
                                        <span class="type-password__eye"></span>
                                        <input type="password" name="password" class="form-control" placeholder="Новый пароль" title="">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="uds-wrap is-ajax-wrap">

                                </div>
                            </div>

                        </div>
                        <div class="d-flex align-items-center">
                            <button type="submit" class="btn btn--black mr-30">Сохранить изменения</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
<script>
    //$('.is-ajax-wrap').append("<div class='loader-ui loader-ui--triangle'></div>");

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: "{{ route('profile.uds.info') }}",
        method: 'get',
        dataType: 'json',
        success: function (data) {
            if (data.view) {
                $('.uds-wrap').empty().append(data.view);
            }
        },
        error: function (error) {
            //alert(error.responseJSON.error);
        }
    });
</script>
@endsection
