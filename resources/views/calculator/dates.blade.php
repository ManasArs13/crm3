<div class="datetime-popup">
    <div class="CEB__row">
        <div class="dates">
            @foreach ($dates as $date)
                @php
                    $dateFormat=new DateTime($date->date)
                @endphp
                <div class="date">{{ $dateFormat->format("d.m.Y")}}</div>

                <div class="times">
                    @foreach ($times as $time)
                        <div class="time">
                            <span data-time="{{$date->date.' '.$time->time}}" class="time-span {{isset($datesFinish[$dateFormat->format("d.m.Y")][$time->time])?"bg-yellow":""}}"> {{$time->time_slot}} </span>
                            @if (isset($datesFinish[$dateFormat->format("d.m.Y")][$time->time]))
                                <span class="order-count">{{count($datesFinish[$dateFormat->format("d.m.Y")][$time->time])}}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
</div>
