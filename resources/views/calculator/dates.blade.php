<div class="datetime-popup {{$class}}  {{($class=="calcBeton")?"CEB w-11/12 max-w-7xl mx-auto pb-10":""}}">
    <div class="CEB__row">
        <div class="dates">
            @foreach ($dates as $date)
                @php
                    $dateFormat=new DateTime($date->date)
                @endphp
                <div class="date-time">
                    <div class="date">{{ $dateFormat->format("d.m.Y")}}</div>

                    <div class="times">
                        @foreach ($times as $time)
                            <div class="time">
                                <span data-time="{{$date->date.' '.$time->time}}" class="time-span {{isset($datesFinish[$dateFormat->format("d.m.Y")][$time->time])?"bg-yellow":""}}">
                                    {{$time->time_slot}}
                                    @if (isset($datesFinish[$dateFormat->format("d.m.Y")][$time->time]))
                                        {{round($datesFinish[$dateFormat->format("d.m.Y")][$time->time]["weight"])}}
                                    @endif
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
