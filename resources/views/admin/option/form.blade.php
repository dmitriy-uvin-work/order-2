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
                            <li class="breadcrumb-item"><a href="{{ route('admin.option.index') }}">Опции товара</a></li>
                            <li class="breadcrumb-item"><a href="javascript:">{{ $data->id ? $data->name : 'Добавить опцию' }}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->

        <form action="" method="post" enctype="multipart/form-data" id="mainForm">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <!------ name ------>
                            <div class="form-group {{ $data->id ? 'mb-0' : '' }}">
                                <label>Название</label>
                                <input type="text"
                                       class="form-control"
                                       name="name"
                                       value="{{ old('name') ?? $data->name }}"
                                       autocomplete="off"
                                       title="">
                            </div>

                            @if(!$data->id)
                                <!------ type ------>
                                <div class="form-group form-check mb-0">
                                    <input type="checkbox" class="form-check-input" id="typeLabel" name="type">
                                    <label class="form-check-label" for="typeLabel">Однотипный</label>
                                </div>
                            @endif
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </div>
        </form>
    </div>
@endsection


@section('scripts')
    <script>
        let optionValue = '{{ route('admin.option-value.post-single') }}';
        $('#typeLabel').on('change', function () {
           if ($(this).prop("checked")) {
                $('#mainForm').prop('action', optionValue);
           } else {
               $('#mainForm').prop('action', '');
           }
        });
    </script>
@endsection
