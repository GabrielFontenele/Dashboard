<x-layout>
<div class="calendar-container">
    <h2 class="title">Calendar</h2>
    <div class="grid-content">
        <div class="grid">
            <div class="week">
                @foreach($daysOfWeek as $day)
                <div class="week-item">
                    {{$day}}
                </div>
                @endforeach
            </div>
            <div class="time">
                @foreach($hoursRange as $hour)
                <div class="time-item">
                    {{$hour}}
                </div>
                @endforeach
            </div>
            <div class="item-container">
                
                @foreach($hoursRange as $keyHour => $hour)
                @foreach($daysOfWeek as $keyDay => $day)
                @php
                    $date = new DateTime('this week '.$daysOfWeekTime[$keyDay]);
                    $time = explode(':', $hour);
                    // $date = $date->setTime($time[0], $time[1])->format('Y-m-d H:i:s')
                    $date = $date->setTime($time[0], $time[1])->format('Y-m-d H:i:s');
                    $reservation = $reservations[$date] ?? false;
                @endphp
                    @if($reservation)
                    <div
                        class="item slot"
                        @style({
                            'grid-row': $keyDay + 1,
                            'grid-column': $keyHour + 1,
                        })
                    >
                        <div @class([
                            'item-overflow-'.$reservation['overFlow'],
                            'item-active'
                        ])>
                            {{$reservation['name']}}
                            {{$reservation['overFlow']}}
                            <br />
                            <span>{{$reservation['description']}}</span>
                        </div>
                    </div>
                    @else
                    <div class="item border"></div>
                    @endif
                @endforeach
                @endforeach
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="css/dashboard.css">

</x-layout>