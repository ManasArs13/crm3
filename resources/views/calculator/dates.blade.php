<div class="datetime-popup {{$class}}  {{($class=="calcBeton")?"CEB w-11/12 max-w-7xl mx-auto pb-10":""}}" data-class="{{$class}}">
    <div class="CEB__row">
        <div class="dates">
            @foreach ($dates as $date)
                @php
                    $dateFormat=new DateTime($date->date)
                @endphp
                <div class="date-time">
                    <div class="date">{{ $dateFormat->format("d.m.Y")}}</div>

                    <div class="times">
                        @php
                            $weight=0;
                        @endphp
                        @foreach ($times as $time)
                            <div class="time">
                                <span data-time="{{$date->date.' '.$time->time}}" class="time-span {{isset($datesFinish[$dateFormat->format("d.m.Y")][$time->time])?"bg-yellow":""}}">
                                    @if (isset($datesFinish[$dateFormat->format("d.m.Y")][$time->time]))
                                        {{round($datesFinish[$dateFormat->format("d.m.Y")][$time->time]["weight"])}}
                                        @php
                                            $weight+=round($datesFinish[$dateFormat->format("d.m.Y")][$time->time]["weight"]);
                                        @endphp
                                    @else
                                        {{$time->time_slot}}
                                    @endif
                                </span>
                            </div>
                        @endforeach
                        @if ($weight!=0)
                            <div class="time">
                                <span class="time-span itogo">
                                    {{$weight}}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
