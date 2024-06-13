<div class="CEB w-11/12 max-w-7xl mx-auto pb-10">
    <div class="CEB__wrapContent df {{ $form }}">
        @if ($menu)
            <div class="CEB CEB-1 CEB-2">
                <div class="CEB__row">
                    <div class="CEB__text2">{{ __('calculator.type_fence') }}</div>
                    <fieldset class="CEB__wrapParams types" id="group1">

                        <label class="labelCustomRadio_type checked">
                            <input checked class="labelCustomRadio__input CMR__change_js CMR__input_typeZabor_js"
                                type="radio" name="calc" value="type_1" data-numberType="1">
                            <img src="{{ Storage::url('1.jpg') }}" alt="{{ __('calculator.type_1') }}">
                        </label>
                        <label class="labelCustomRadio_type">
                            <input class="labelCustomRadio__input CMR__change_js CMR__input_typeZabor_js" type="radio"
                                name="calc" value="type_2" data-numberType="2">
                            <img src="{{ Storage::url('2.jpg') }}" alt="{{ __('calculator.type_2') }}">
                        </label>
                        <label class="labelCustomRadio_type">
                            <input class="labelCustomRadio__input CMR__change_js CMR__input_typeZabor_js" type="radio"
                                name="calc" value="type_3" data-numberType="3">
                            <img src="{{ Storage::url('3.jpg') }}" alt="{{ __('calculator.type_3') }}">
                        </label>

                    </fieldset>
                </div>
                <div class="flex">
                    <div class="CEB__row">
                        <div class="CEB__text2">
                            <div>{{ __('calculator.fence_length') }}</div>
                            <div class="CEBQuestionW__input-rande-text">
                                <div class="quantity">
                                    <input type="number" value=0 id="CEB__textLength" min=0 class="change_length"
                                        step=1>
                                    <div class="quantity-nav">
                                        <div class="quantity-button quantity-up">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-caret-up" viewBox="0 0 16 16">
                                                <path
                                                    d="M3.204 11h9.592L8 5.519 3.204 11zm-.753-.659 4.796-5.48a1 1 0 0 1 1.506 0l4.796 5.48c.566.647.106 1.659-.753 1.659H3.204a1 1 0 0 1-.753-1.659z" />
                                            </svg>
                                        </div>
                                        <div class="quantity-button quantity-down">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-caret-down" viewBox="0 0 16 16">
                                                <path
                                                    d="M3.204 5h9.592L8 10.481 3.204 5zm-.753.659 4.796 5.48a1 1 0 0 0 1.506 0l4.796-5.48c.566-.647.106-1.659-.753-1.659H3.204a1 1 0 0 0-.753 1.659z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="metric">{{ __('calculator.m') }}</div>
                            </div>
                        </div>

                        <div class="CEB__wrapSlider">
                            <div class="CEBQuestionW__wrap-answer-input-rande">
                                <div id="CEBQuestionW-slide1" class="CEBQuestionW__slider"></div>
                                <input type="hidden" id="CEB__inputLength" name="Длина забора, м: " value="0">
                            </div> <!-- .qCEBQuestionW__wrap-answer-input-rande -->
                            <div class="CEB__wrapData">
                                <span class="CEB__Data">0</span>
                                <span class="CEB__Data">50</span>
                                <span class="CEB__Data">100</span>
                                <span class="CEB__Data">150</span>
                                <span class="CEB__Data">200</span>
                                <span class="CEB__Data">250</span>
                                <span class="CEB__Data">300</span>
                            </div>
                        </div>

                    </div>
                    <div class="CEB__row">
                        <div class="CEB__text2">
                            <div>{{ __('calculator.number_of_pillars') }}</div>
                            <div class="CEBQuestionW__input-rande-text">
                                <div class="quantity">
                                    <input type="number" value=0 id="CEB__textPost_quantity" min=0
                                        class="change_postQuantity" step=1>
                                    <div class="quantity-nav">
                                        <div class="quantity-button quantity-up">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-caret-up" viewBox="0 0 16 16">
                                                <path
                                                    d="M3.204 11h9.592L8 5.519 3.204 11zm-.753-.659 4.796-5.48a1 1 0 0 1 1.506 0l4.796 5.48c.566.647.106 1.659-.753 1.659H3.204a1 1 0 0 1-.753-1.659z" />
                                            </svg>
                                        </div>
                                        <div class="quantity-button quantity-down">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-caret-down" viewBox="0 0 16 16">
                                                <path
                                                    d="M3.204 5h9.592L8 10.481 3.204 5zm-.753.659 4.796 5.48a1 1 0 0 0 1.506 0l4.796-5.48c.566-.647.106-1.659-.753-1.659H3.204a1 1 0 0 0-.753 1.659z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="metric">{{ __('calculator.pc') }}</div>
                            </div>
                        </div>

                        <div class="CEB__wrapSlider">
                            <div class="CEBQuestionW__wrap-answer-input-rande">
                                <div id="CEBQuestionW-slide2" class="CEBQuestionW__slider"></div>
                                <input type="hidden" id="CEB__inputPost_quantity" name="Количество столбов, шт: "
                                    value="0">
                            </div> <!-- .qCEBQuestionW__wrap-answer-input-rande -->
                            <div class="CEB__wrapData">
                                <span class="CEB__Data">0</span>
                                <span class="CEB__Data">20</span>
                                <span class="CEB__Data">40</span>
                                <span class="CEB__Data">60</span>
                                <span class="CEB__Data">80</span>
                                <span class="CEB__Data">100</span>
                                <span class="CEB__Data">120</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex">
                    <div class="CEB__row">
                        <div class="CEB__text2">
                            <div>{{ __('calculator.wall_height') }}</div>
                            <div class="CEBQuestionW__input-rande-text">

                                <div class="quantity">
                                    <input type="number" value=0 id="CEB__text_wallHeight" min=0
                                        class="change_wallHeight" step=1>
                                    <div class="quantity-nav">
                                        <div class="quantity-button quantity-up">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-caret-up" viewBox="0 0 16 16">
                                                <path
                                                    d="M3.204 11h9.592L8 5.519 3.204 11zm-.753-.659 4.796-5.48a1 1 0 0 1 1.506 0l4.796 5.48c.566.647.106 1.659-.753 1.659H3.204a1 1 0 0 1-.753-1.659z" />
                                            </svg>
                                        </div>
                                        <div class="quantity-button quantity-down">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-caret-down" viewBox="0 0 16 16">
                                                <path
                                                    d="M3.204 5h9.592L8 10.481 3.204 5zm-.753.659 4.796 5.48a1 1 0 0 0 1.506 0l4.796-5.48c.566-.647.106-1.659-.753-1.659H3.204a1 1 0 0 0-.753 1.659z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="metric">{{ __('calculator.cm') }}</div>
                            </div>

                        </div>

                        <div class="CEB__wrapSlider">
                            <div class="CEBQuestionW__wrap-answer-input-rande">
                                <div id="CEBQuestionW-slide3" class="CEBQuestionW__slider"></div>
                                <input type="hidden" id="CEB__input_wallHeight" name="Высота стенки, см: "
                                    value="0">
                            </div> <!-- .qCEBQuestionW__wrap-answer-input-rande -->
                            <div class="CEB__wrapData">
                                <span class="CEB__Data">80</span>
                                <span class="CEB__Data">120</span>
                                <span class="CEB__Data">160</span>
                                <span class="CEB__Data">200</span>
                                <span class="CEB__Data">240</span>
                                <span class="CEB__Data">280</span>
                                <span class="CEB__Data">320</span>
                            </div>
                        </div>

                    </div>
                    <div class="CEB__row">
                        <div class="CEB__text2">
                            <div>{{ __('calculator.column_height') }}</div>
                            <div class="CEBQuestionW__input-rande-text">
                                <div class="quantity">
                                    <input type="number" value=0 id="CEB__text_columnHeight" min=0
                                        class="change_columnHeight">
                                    <div class="quantity-nav">
                                        <div class="quantity-button quantity-up">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-caret-up" viewBox="0 0 16 16">
                                                <path
                                                    d="M3.204 11h9.592L8 5.519 3.204 11zm-.753-.659 4.796-5.48a1 1 0 0 1 1.506 0l4.796 5.48c.566.647.106 1.659-.753 1.659H3.204a1 1 0 0 1-.753-1.659z" />
                                            </svg>
                                        </div>
                                        <div class="quantity-button quantity-down">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-caret-down" viewBox="0 0 16 16">
                                                <path
                                                    d="M3.204 5h9.592L8 10.481 3.204 5zm-.753.659 4.796 5.48a1 1 0 0 0 1.506 0l4.796-5.48c.566-.647.106-1.659-.753-1.659H3.204a1 1 0 0 0-.753 1.659z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="metric">{{ __('calculator.cm') }}</div>
                            </div>
                        </div>
                        <div class="CEB__wrapSlider">
                            <div class="CEBQuestionW__wrap-answer-input-rande">
                                <div id="CEBQuestionW-slide4" class="CEBQuestionW__slider"></div>
                                <input type="hidden" id="CEB__input_columnHeight" name="Высота колоны, см: "
                                    value="0">
                            </div> <!-- .qCEBQuestionW__wrap-answer-input-rande -->
                            <div class="CEB__wrapData">
                                <span class="CEB__Data">80</span>
                                <span class="CEB__Data">120</span>
                                <span class="CEB__Data">160</span>
                                <span class="CEB__Data">200</span>
                                <span class="CEB__Data">240</span>
                                <span class="CEB__Data">280</span>
                                <span class="CEB__Data">320</span>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        @endif
        <div class="CEB CEB-1">

            <form class="{{ $form }}">
                @csrf
                <input type="hidden" name="form" value="{{ $form }}">
                <div class="flex columns">
                    <div class="CEB__row column">
                        <div class="CEB__text2">
                            <div class="result">{{ __('calculator.result') }}</div>
                            @if ($menu)
                                <div class="flex">
                                    <div>{{ __('calculator.reserve') }}</div>
                                    <div class="quantity">
                                        <input type="number" id="CEB__textReserve" min=0 class="change_reserve"
                                            value="3" step=1 max=100>
                                        <div class="quantity-nav">
                                            <div class="quantity-button quantity-up">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-caret-up" viewBox="0 0 16 16">
                                                    <path
                                                        d="M3.204 11h9.592L8 5.519 3.204 11zm-.753-.659 4.796-5.48a1 1 0 0 1 1.506 0l4.796 5.48c.566.647.106 1.659-.753 1.659H3.204a1 1 0 0 1-.753-1.659z" />
                                                </svg>
                                            </div>
                                            <div class="quantity-button quantity-down">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-caret-down" viewBox="0 0 16 16">
                                                    <path
                                                        d="M3.204 5h9.592L8 10.481 3.204 5zm-.753.659 4.796 5.48a1 1 0 0 0 1.506 0l4.796-5.48c.566-.647.106-1.659-.753-1.659H3.204a1 1 0 0 0-.753 1.659z" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="CEB__wrapTable">
                            @if (count($productsByGroup) > 0)
                                <table class="CEB__table">
                                    <tr>
                                        <td>{{ __('calculator.position') }}</td>
                                        <td>{{ __('calculator.quantity') }}</td>
                                        @if (isset($productsByGroup[array_key_first($productsByGroup)]['colors']))
                                            <td>{{ __('calculator.color') }}</td>
                                        @endif
                                        <td>{{ __('calculator.weight') }}</td>
                                        <td>{{ __('calculator.price') }}</td>
                                        <td>{{ __('calculator.sum') }}</td>
                                    </tr>

                                    @if ($form != 'calcBeton')
                                        @foreach ($productsByGroup as $group)
                                            @include('calculator.row', [
                                                'form' => $form,
                                                'group' => $group,
                                            ])
                                        @endforeach
                                        <tr>
                                            <td>
                                                <div class="flex">
                                                    <span>{{ $pallet->name }}</span>
                                                    <span class="balance {{ $form }}"
                                                        data-id="pallet">{{ $pallet->balance }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="quantity cursor">
                                                    <input class="select__input" type="hidden"
                                                        value="{{ $pallet->ms_id }}"
                                                        name="positions[pallet][product_id]">
                                                    <input type="number" name="positions[pallet][quantity]" value=0 min=0
                                                        readonly step=1 data-price="{{ $pallet->price }}"
                                                        data-weight="{{ $pallet->weight_kg }}">
                                                </div>
                                            </td>
                                            @if (isset($productsByGroup[array_key_first($productsByGroup)]['colors']))
                                                <td></td>
                                            @endif
                                            <td>
                                                <span id="weight_total_pallet" class="weight">0</span>
                                            </td>
                                            <td>
                                                <span id="price_client_pallet">0</span>
                                                <input type="hidden" name="positions[pallet][price]"
                                                    value="{{ $pallet->price }}">
                                            </td>
                                            <td><span id="price_total_pallet" class="price">0</span></td>
                                        </tr>
                                    @else
                                        @include('calculator.row', [
                                            'form' => $form,
                                            'productsByGroup' => $productsByGroup,
                                            'group' => $productsByGroup[$idBeton],
                                        ])
                                    @endif
                                    <tr>
                                        <td>итог:</td>
                                        <td></td>
                                        @if (isset($productsByGroup[array_key_first($productsByGroup)]['colors']))
                                            <td></td>
                                        @endif
                                        <td><span id="weight_total">0</span></td>
                                        <td></td>
                                        <td><span id="price_total">0</span></td>
                                    </tr>
                                </table>
                            @endif
                        </div>
                    </div>
                    <div class="CEB__row column">
                        @include('calculator.info2')
                    </div>
                </div>
                @include('calculator.info')
            </form>
        </div>
    </div>
</div>
@include('calculator.dates', ['dates' => $dates, 'datesFinish' => $datesFinish, 'class' => $form])
<div class="CEB w-11/12 max-w-7xl mx-auto pb-10">
    <div class="img_delivery">
        <img src="{{ Storage::url('shipping_prices.jpg') }}" alt="{{ __('calculator.price_list') }}">
        <img src="{{ Storage::url('brochure.jpg') }}" alt="{{ __('calculator.brochure') }}">
    </div>
</div>
