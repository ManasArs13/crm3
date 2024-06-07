<div class="CEB w-11/12 max-w-7xl mx-auto pb-10">
    <div class="CEB__wrapContent df {{$form}}">
                @if ($left_menu)
                    <div class="CEB__left">
                        <div class="CEB__row">
                            <div class="CEB__text2">Тип забора</div>

                            <fieldset class="CEB__wrapParams" id="group1">

                                <label class="labelCustomRadio labelCustomRadio_js">
                                    <input checked class="labelCustomRadio__input CMR__change_js CMR__input_typeZabor_js"
                                        type="radio" name="calc" value="Французский забор, Комплектация №1"
                                        data-numberType="1">
                                    <span class="labelCustomRadio__psevdo_border"></span>
                                    <p class="labelCustomRadio__text2">Французский забор, Комплектация №1</p>
                                </label>
                                <label class="labelCustomRadio labelCustomRadio_js">
                                    <input class="labelCustomRadio__input CMR__change_js CMR__input_typeZabor_js" type="radio"
                                        name="calc" value="Французский забор, Комплектация №2" data-numberType="2">
                                    <span class="labelCustomRadio__psevdo_border"></span>
                                    <p class="labelCustomRadio__text2">Французский забор, Комплектация №2</p>
                                </label>
                                <label class="labelCustomRadio labelCustomRadio_js">
                                    <input class="labelCustomRadio__input CMR__change_js CMR__input_typeZabor_js" type="radio"
                                        name="calc" value="Французский забор, Комплектация №3" data-numberType="3">
                                    <span class="labelCustomRadio__psevdo_border"></span>
                                    <p class="labelCustomRadio__text2">Французский забор, Комплектация №3</p>
                                </label>

                            </fieldset>

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
                    <div class="CEB CEB-1">
                @endif
                    <form class="{{$form}}">
                        @csrf
                        <input type="hidden" name="form" value="{{$form}}">
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

                                    @if ($form!="calcBeton")
                                        @foreach($productsByGroup as $group)
                                            @include("calculator.row", array('form'=>$form, 'group'=>$group))
                                        @endforeach
                                    @else
                                        @include("calculator.row", array('form'=>$form, 'productsByGroup'=>$productsByGroup, 'group'=>$productsByGroup[$idBeton]))
                                    @endif
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
@include("calculator.dates", array('dates' => $dates, 'datesFinish'=>$datesFinish))

