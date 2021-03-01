@extends('admin.layout.master')

@section('section')

    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <!-- [ Main Content ] start -->
                <div class="card">
                    <div class="card-block">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>метод</th>
                                    <th>время создание</th>
                                    <th>время обновление</th>
                                    <th>время синхронизации</th>
                                    <th>статус</th>
                                    <th width="400">контент</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($sync as $method)
                                <tr>
                                    <td>{{ $method->methodLabel }}</td>
                                    <td>{{ $method->created_at->format('d.m.Y H:i:s') }}</td>
                                    <td>{{ $method->updated_at->format('d.m.Y H:i:s') }}</td>
                                    <td>{{ $method->last_sync_at ? $method->last_sync_at->format('d.m.Y H:i:s') : '-' }}</td>
                                    <td>{!! $method->statusUI !!}</td>
                                    <td>{{ $method->value }}</td>
                                    <td><button class="reload-ui" data-method="{{ $method->method }}"></button></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- [ Main Content ] end -->
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script>

    $('body').on('click', '.reload-ui', function (e) {
        e.preventDefault();

        let btn = $(this);

        if (!btn.prop('disabled') === true) {
            let methodName = btn.attr('data-method');

            btn.addClass('reloading').prop('disabled', true);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('admin.regos.method') }}",
                method: "Post",
                data: {methodName: methodName},
                success: function (data) {
                    btn.removeClass('reloading').prop('disabled', false);
                    if (data.success) {
                        btn.parent().parent().empty().append(data.view);
                    }
                },
                error: function (error) {
                    btn.removeClass('reloading').prop('disabled', false);
                    ajaxErrorMessage(error);
                }
            });
        }
    });
</script>
@endsection
