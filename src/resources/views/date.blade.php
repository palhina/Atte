@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/date.css') }}">
@endsection

@section('content')
    <div class="atte__content">
        <div class ="atte__date-search">
            <form method="post" action="/attendance">
            @csrf
                <input type="hidden" name="date" value="{{ $previousDate }}">
                <button type="submit" class="prev-date-button">&lt;</button>
            </form>
            <div class ="atte__date">
                <p class="search__date-form"> {{ $selectedDate }}</p> 
            </div>
            <form method="post" action="/attendance">
            @csrf
                <input type="hidden" name="date" value="{{ $nextDate }}">
                <button type="submit" class="next-date-button">&gt;</button>
            </form>
        </div>
        <div class="atte__customer">
            <table class="customer-table__inner">
                <tr class="customer-table__row">
                    <div class="customer-table__header">
                        <th class="customer-table__header-span">名前</th>
                        <th class="customer-table__header-span">勤務開始</th>
                        <th class="customer-table__header-span">勤務終了</th>
                        <th class="customer-table__header-span">休憩時間</th>
                        <th class="customer-table__header-span">勤務時間</th>
                    </div>
                </tr>
                @foreach ($attendances as $attendance)
                <tr class="customer-table__row">
                    <div class="customer-table__item">
                        <td class="update-form__item">
                            {{$attendance->user->name}}
                        </td>
                        <td class="update-form__item">
                            {{\Carbon\Carbon::parse($attendance->start_time)->format('H:i:s')}}
                        </td>
                        <td class="update-form__item">
                            {{\Carbon\Carbon::parse($attendance->end_time)->format('H:i:s')}}
                        </td>
                        <td class="update-form__item">
                            @if (isset($breakTimes[$attendance->id]))
                                {{ $breakTimes[$attendance->id] }}
                            @else
                                00:00:00
                            @endif
                        </td>
                        <td class="update-form__item">
                            @if (isset($workTimes[$attendance->id]))
                                {{ $workTimes[$attendance->id] }}
                            @endif
                        </td>
                    </div>
                </tr>
                @endforeach
            </table>
        <div class="pagination">
            {{$attendances->links('vendor.pagination.custom')}}
        </div>
    </div>
@endsection