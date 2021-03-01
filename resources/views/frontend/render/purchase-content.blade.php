@php
    $phone = isset($old['phone']) ? $old['phone'] : auth()->user()->phone;
    $confirm_use = isset($old['confirm_use']) ? $old['confirm_use'] : null;
    $payment_type = isset($old['payment_type']) ? $old['payment_type'] : 1;
    $delivery_type = isset($old['delivery_type']) ? $old['delivery_type'] : 1;
    $delivery_region = isset($old['delivery_region']) ? $old['delivery_region'] : null;
    $delivery_district = isset($old['delivery_district']) ? $old['delivery_district'] : null;
    $delivery_address = isset($old['delivery_address']) ? $old['delivery_address'] : null;

    // default values
    $uds_points = 0;
    $uds_max_points = 0;
    $uds_cashBack = 0;
    $uds_total = 0;

    if (isset($udsInfo)) {
        if ($udsInfo->purchase != null) {
            $uds_points = floor($udsInfo->user->participant->points);
            $uds_max_points = floor($udsInfo->purchase->maxPoints);
            $uds_cashBack = $udsInfo->purchase->cashBack;
            $uds_total = $udsInfo->purchase->total;
        }
    }
@endphp

<form action="{{ route('profile.purchasing.post') }}" method="post" id="PurchaseForm">
    {{ csrf_field() }}

    <div class="group">
        <div class="group-label">
            Личная информация
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <input type="text"
                           class="form-control"
                           placeholder="Имя и фамилия"
                           value="{{ auth()->user()->fullName }}"
                           readonly
                           title="">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <div class="type-phone">
                        <input type="text"
                               class="form-control"
                               name="phone"
                               placeholder="Номер телефона"
                               minlength="9"
                               maxlength="9"
                               value="{{ $phone ?? auth()->user()->phone }}"
                               required
                               autocomplete="off"
                               title="">
                        <span>+998</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <input type="email"
                           class="form-control"
                           placeholder="Эл. почта"
                           value="{{ auth()->user()->email }}"
                           readonly
                           title="">
                </div>
            </div>
        </div>
    </div>

    <div class="group">
        <div class="group-label">Промокод</div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <input type="text"
                           name="uds_code"
                           class="form-control"
                           placeholder="Введите UDS код"
                           value="{{ isset($old['uds_code']) ? $old['uds_code'] : '' }}"
                           autocomplete="off"
                           title="">
                </div>
            </div>
            <div class="col-md-8">
                <button type="button" id="getUDSDiscount" class="btn btn--black btn--small">Получить скидку</button>
            </div>
            @if(isset($udsInfoError) )
                <div class="col-md-12">
                    <span>{{ $udsInfoError }}</span>
                </div>
            @endif
        </div>
        @if(isset($udsInfo))
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="mb-20 font-06 font-13 d-block">Ваши баллы:</label>
                        <input type="text"
                               class="form-control"
                               placeholder="Введите UDS код"
                               value="{{ floor($uds_points) }}"
                               autocomplete="off"
                               readonly
                               title="">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="mb-20 font-06 font-13 d-block">Разрешенные баллы:</label>
                        <input type="text"
                               name="uds_max_points"
                               class="form-control"
                               placeholder="Введите UDS код"
                               max="{{ floor($uds_max_points) }}"
                               value="{{ floor($uds_max_points) }}"
                               autocomplete="off"
                               title="">
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="group">
        <div class="group-label">Адрес</div>
        <div class="d-flex flex-wrap mb-30">
            <label class="checkbox-ui mr-40">
                <input type="radio" name="delivery_type" value="1" {{ $delivery_type == 1 ? 'checked' : '' }}>
                <span class="checkbox-ui__figure br-100"></span><span>Доставка</span>
            </label>
            <label class="checkbox-ui">
                <input type="radio" name="delivery_type" value="2" {{ $delivery_type == 2 ? 'checked' : '' }}>
                <span class="checkbox-ui__figure br-100"></span><span>Самовывоз</span>
            </label>
        </div>
        <div class="font-style--min c--main-grey">Доставка по всей России и СНГ — Звоните по номеру +998 (99) 000 00 00
        </div>
        <div class="row mt-40" data-status="delivery_type">
            <div class="col-md-6">
                <div class="form-group">
                    <select class="select2" name="delivery_region" title="" data-placeholder="Выберите регион">
                        @if(count($regions) > 0)
                            <option value=""></option>
                            @foreach($regions as $region)
                                <option
                                    value="{{ $region->id }}" {{ $delivery_region == $region->id ? 'selected' : '' }}>{{ $region->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <select class="select2" name="delivery_district" {{ isset($districts) ? '' : 'disabled' }} title=""
                            data-placeholder="Выберите район">
                        <option value=""></option>
                        @if(isset($districts) && count($districts) > 0)
                            @foreach($districts as $district)
                                <option
                                    value="{{ $district->id }}" {{ $delivery_district == $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <input type="text"
                           name="delivery_address"
                           class="form-control"
                           placeholder="Улица, дом, квартира"
                           value="{{ $delivery_address }}"
                           title="">
                </div>
            </div>
        </div>
    </div>

    <div class="group">
        <div class="group-label">Выберите способ оплаты</div>
        <div class="form-group">
            <select class="select2" name="payment_type" title="">
                <option value="1" {{ $payment_type == 1 ? 'selected' : '' }}>Payme</option>
                <option value="2" {{ $payment_type == 2 ? 'selected' : '' }}>Click</option>
                <option value="2" {{ $payment_type == 3 ? 'selected' : '' }}>Apelsin</option>
                <option value="0" {{ $payment_type == 0 ? 'selected' : '' }}>Наличными</option>
            </select>
        </div>
    </div>

    <div class="group">

        <table class="table mb-30">
            <tbody>
            <tr>
                <td class="font-14">Товары:</td>
                <td class="text-right font-16">@priceFormat($cartValues['net_price']) UZS</td>
            </tr>
            @if($uds_points != 0)
            <tr>
                <td class="font-14">Скидка по UDS:</td>
                <td class="text-right font-16">@priceFormat($uds_points) UZS</td>
            </tr>
            @endif
            @if($uds_cashBack != 0)
            <tr>
                <td class="font-14">Накопительная скидка  на счет UDS:</td>
                <td class="text-right font-16">@priceFormat($uds_cashBack) UZS</td>
            </tr>
            @endif
            <tr>
                <td class="font-14">Стоимость доставки:</td>
                <td class="text-right font-16">@priceFormat($cartValues['delivery_price']) UZS</td>
            </tr>
            <tr>
                <td class="font-14">Всего:</td>
                <td class="text-right font-18">@priceFormat($cartValues['total_price']) UZS</td>
            </tr>
            </tbody>
        </table>

        <label class="checkbox-ui checkbox-ui--top">
            <input type="checkbox" name="confirm_use" {{ isset($old['confirm_use']) ? 'checked' : '' }}>
            <span class="checkbox-ui__figure"></span><span class="font-style--min c--main-grey">Я даю согласие на обработку своих персональных данных и соглашаюсь с Условиями использования и Политикой конфиденциальности</span>
        </label>
    </div>

    <input type="hidden" name="net_price" value="{{ $cartValues['net_price'] }}">
    <input type="hidden" name="uds_points" value="{{ $uds_total }}">
    <input type="hidden" name="total_price" value="{{ $cartValues['total_price'] }}">
    <input type="hidden" name="delivery_price" value="{{ $cartValues['delivery_price'] }}">

    <button type="submit" class="btn btn--black">оплатить заказ</button>

</form>
