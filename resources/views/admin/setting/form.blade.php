@extends('admin.layout.master')

@section('section')
    <div class="pcoded-inner-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row">
                    <div class="col-md-12">
                        <div class="page-header-title">
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="javascript:">Настройки сайта</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->

        <form action="" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <!------ name ------>
                            <div class="form-group">
                                <label>Телефон</label>
                                <input type="text"
                                       class="form-control"
                                       name="phone"
                                       value="{{ old('phone') ?? $data->phone }}"
                                       autocomplete="off"
                                       title="">
                            </div>

                            <!------ email ------>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="text"
                                       class="form-control"
                                       name="email"
                                       value="{{ old('email') ?? $data->email }}"
                                       autocomplete="off"
                                       title="">
                            </div>

                            <!------ address ------>
                            <div class="form-group">
                                <label>Адрес</label>
                                <input type="text"
                                       class="form-control"
                                       name="address"
                                       value="{{ old('address') ?? $data->address }}"
                                       autocomplete="off"
                                       title="">
                            </div>

                            <!------ working_hours ------>
                            <div class="form-group">
                                <label>Режим работы</label>
                                <input type="text"
                                       class="form-control"
                                       name="working_hours"
                                       value="{{ old('working_hours') ?? $data->working_hours }}"
                                       autocomplete="off"
                                       title="">
                            </div>

                            <!------ instagram ------>
                            <div class="form-group">
                                <label>Instagram</label>
                                <input type="text"
                                       class="form-control"
                                       name="instagram"
                                       value="{{ old('instagram') ?? $data->instagram }}"
                                       autocomplete="off"
                                       title="">
                            </div>

                            <!------ twitter ------>
                            <div class="form-group">
                                <label>Twitter</label>
                                <input type="text"
                                       class="form-control"
                                       name="twitter"
                                       value="{{ old('twitter') ?? $data->twitter }}"
                                       autocomplete="off"
                                       title="">
                            </div>

                            <!------ vk ------>
                            <div class="form-group">
                                <label>VK</label>
                                <input type="text"
                                       class="form-control"
                                       name="vk"
                                       value="{{ old('vk') ?? $data->vk }}"
                                       autocomplete="off"
                                       title="">
                            </div>

                            <!------ facebook ------>
                            <div class="form-group">
                                <label>Facebook</label>
                                <input type="text"
                                       class="form-control"
                                       name="facebook"
                                       value="{{ old('facebook') ?? $data->facebook }}"
                                       autocomplete="off"
                                       title="">
                            </div>

                            <!------ telegram ------>
                            <div class="form-group">
                                <label>Telegram</label>
                                <input type="text"
                                       class="form-control"
                                       name="telegram"
                                       value="{{ old('telegram') ?? $data->telegram }}"
                                       autocomplete="off"
                                       title="">
                            </div>

                            <!------ about ------>
                            <div class="form-group mb-0">
                                <label>О нас</label>
                                <textarea class="form-control"
                                          name="about"
                                          title="">{{ old('about') ?? $data->about }}</textarea>
                            </div>

                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <!------ meta_title ------>
                            <div class="form-group">
                                <label>Мета заголовок</label>
                                <input type="text"
                                       class="form-control"
                                       name="meta_title"
                                       value="{{ old('meta_title') ?? $data->meta_title }}"
                                       title=""
                                       autocomplete="off">
                            </div>

                            <!------ meta_description ------>
                            <div class="form-group">
                                <label>Мета описание</label>
                                <textarea class="form-control"
                                          name="meta_description"
                                          title="">{{ old('meta_description') ?? $data->meta_description }}</textarea>
                            </div>

                            <!------ meta_keywords ------>
                            <div class="form-group">
                                <label>Мета ключевые слова</label>
                                <textarea class="form-control"
                                          name="meta_keywords"
                                          title="">{{ old('meta_keywords') ?? $data->meta_keywords }}</textarea>
                            </div>

                            <!------ meta_tags ------>
                            <div class="form-group">
                                <label>Мета Теги</label>
                                <textarea class="form-control"
                                          name="meta_tags"
                                          style="min-height: 410px"
                                          title="">{{ old('meta_tags') ?? $data->meta_tags }}</textarea>
                            </div>

                            <!------ policy_link ------>
                            <div class="form-group">
                                <label>Условия политики ( ссылка )</label>
                                <input type="text"
                                       class="form-control"
                                       name="policy_link"
                                       value="{{ old('policy_link') ?? $data->policy_link }}"
                                       autocomplete="off"
                                       title="">
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
