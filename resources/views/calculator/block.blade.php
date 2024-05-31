    <div class="CEB w-11/12 max-w-7xl mx-auto py-4 pb-10" id="CEB">
        <div class="CEB__wrapContent df">
            @if ($left_menu)
                <div class="CEB__left">
                    <div class="CEB__row">
                        <div class="CEB__text2">Тип забора</div>

                        <div class="CEB__wrapParams">

                            <label class="labelCustomRadio labelCustomRadio_js">
                                <input checked class="labelCustomRadio__input CMR__change_js CMR__input_typeZabor_js"
                                    type="radio" name="Тип забора" value="Французский забор, Комплектация №1"
                                    data-numberType="1">
                                <span class="labelCustomRadio__psevdo_border"></span>
                                <p class="labelCustomRadio__text2">Французский забор, Комплектация №1</p>
                            </label>
                            <label class="labelCustomRadio labelCustomRadio_js">
                                <input class="labelCustomRadio__input CMR__change_js CMR__input_typeZabor_js" type="radio"
                                    name="Тип забора" value="Французский забор, Комплектация №2" data-numberType="2">
                                <span class="labelCustomRadio__psevdo_border"></span>
                                <p class="labelCustomRadio__text2">Французский забор, Комплектация №2</p>
                            </label>
                            <label class="labelCustomRadio labelCustomRadio_js">
                                <input class="labelCustomRadio__input CMR__change_js CMR__input_typeZabor_js" type="radio"
                                    name="Тип забора" value="Французский забор, Комплектация №3" data-numberType="3">
                                <span class="labelCustomRadio__psevdo_border"></span>
                                <p class="labelCustomRadio__text2">Французский забор, Комплектация №3</p>
                            </label>

                        </div>

                    </div>
                    <div class="CEB__row">
                        <div class="CEB__text2">Длина забора, м</div>

                        <div class="CEB__wrapSlider">
                            <div class="CEBQuestionW__input-rande-text"><span id="CEB__textLength">0</span> м.</div>
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
                        <div class="CEB__text2">Количество столбов, шт</div>

                        <div class="CEB__wrapSlider">
                            <div class="CEBQuestionW__input-rande-text"><span id="CEB__textPost_quantity">0</span> шт.</div>
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
                    <div class="CEB__row">
                        <div class="CEB__text2">Высота стенки, см</div>

                        <div class="CEB__wrapSlider">
                            <div class="CEBQuestionW__input-rande-text"><span id="CEB__text_wallHeight">0</span> см.</div>
                            <div class="CEBQuestionW__wrap-answer-input-rande">
                                <div id="CEBQuestionW-slide3" class="CEBQuestionW__slider"></div>
                                <input type="hidden" id="CEB__input_wallHeight" name="Высота стенки, см: " value="0">
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
                        <div class="CEB__text2">Высота колоны, см</div>
                        <div class="CEB__wrapSlider">
                            <div class="CEBQuestionW__input-rande-text"><span id="CEB__text_columnHeight">0</span> см.</div>
                            <div class="CEBQuestionW__wrap-answer-input-rande">
                                <div id="CEBQuestionW-slide4" class="CEBQuestionW__slider"></div>
                                <input type="hidden" id="CEB__input_columnHeight" name="Высота колоны, см: "
                                    value="0">
                            </div> <!-- .qCEBQuestionW__wrap-answer-input-rande -->
                            <div class="CEB__wrapData">
                                <span class="CEB__Data">100</span>
                                <span class="CEB__Data">140</span>
                                <span class="CEB__Data">180</span>
                                <span class="CEB__Data">220</span>
                                <span class="CEB__Data">260</span>
                                <span class="CEB__Data">300</span>
                                <span class="CEB__Data">340</span>
                                <span class="CEB__Data">380</span>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="CEB__right">

            @else
                <div class="CEB">
            @endif
                <form class="{{$form}}">
                    @csrf
                    <div class="CEB__row">
                        <div class="CEB__text2">Результат</div>
                        <div class="CEB__wrapTable">
                        @if (count($productsByGroup)>0)
                            <table class="CEB__table">
                                <tr>
                                    <td>позиция</td>
                                    <td>кол-во</td>
                                    @if (isset($productsByGroup[array_key_first($productsByGroup)]["colors"]))
                                        <td>цвет</td>
                                    @endif
                                    <td>вес, кг</td>
                                    <td>цена, руб/ед</td>
                                    <td>сумма, руб</td>
                                </tr>
                                @foreach($productsByGroup as $group)
                                    <tr>
                                        <td>{{$group["name"]}}</td>
                                        <td>
                                            <input type="number"
                                            name="positions[{{$group["id"]}}][quantity]"
                                            data-color="{{ (isset($group["colors"]))?1:0}}"
                                            data-id="{{$group["id"]}}"
                                            {{
                                                ($left_menu)?'readonly=""':''
                                            }}
                                            value=0
                                            min=0
                                            class="change_js">
                                        </td>
                                        @if (isset($group["colors"]))
                                            <td>
                                                <select name="positions[{{ $group["id"]}}][product_id]"
                                                        data-id="{{$group["id"]}}"
                                                        class="CEB__select_color_js CEB__select_color">
                                                    @foreach($group["colors"] as $color)
                                                        <option data-price="{{$color["price"]}}"
                                                                data-weight="{{$color["weight"]}}"
                                                                value="{{$color["product"]}}"
                                                                data-codeColor="#{{$color["hex"]}}"
                                                                data-codecolortext="#{{$color["font_color"]}}"
                                                                style="background-color:#{{$color["hex"]}}; padding: 10px"
                                                                {{$color["selected"]}}
                                                                >
                                                                {{ $color["name"] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        @else
                                            <input type="hidden" name="positions[{{$group["id"]}}][product_id]"
                                            value="{{ $group["product"] }}"
                                            data-id="{{$group["id"]}}"
                                            data-price="{{$group["price"]}}"
                                            data-weight="{{$group["weight"]}}">
                                        @endif

                                        <td>
                                            <span id="weight_total_{{$group["id"]}}" class="weight">0</span>
                                        </td>
                                        <td>
                                            <span id="price_client_{{$group["id"]}}">
                                                @if (isset($group["colors"]))
                                                    {{$group["colors"][0]["price"]}}
                                                @else
                                                    {{$group["price"]}}
                                                @endif
                                            </span>
                                            <input type="hidden" name="positions[{{$group["id"]}}][price]" value="{{isset($group["colors"])?$group["colors"][0]["price"]:$group["price"]}}">
                                        </td>
                                        <td><span id="price_total_{{$group["id"]}}" class="price">0</span></td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td>итог:</td>
                                    <td></td>
                                    @if (isset($productsByGroup[array_key_first($productsByGroup)]["colors"]))
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

                    @include("calculator.info")
                </form>
            </div>
        </div>
    </div>
