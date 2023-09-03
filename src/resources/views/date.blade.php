@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/date.css') }}">
@endsection

@section('content')
    <div class="atte__content">
        <div class="atte__date-container">
            <form action="/attendance" method="post">
            @csrf
                <div class ="atte__date">
                    <button type="button" class="prev-date-button">&lt;</button>
                    <div class ="atte__date-search">
                        <select name="year" class="year">
                            @for($i=2015; $i <= 2030; $i++)
                            <option value="{{$i}}" {{ $i == 2021 ? 'selected' : '' }}>{{$i}}</option>
                            @endfor
                        </select>
                        <p class="date-select">-</p>
                        <select name="month" class="month">
                            @for($i=1; $i <= 12; $i++)
                            <option value="{{$i}}" {{ $i == 11 ? 'selected' : '' }}>{{$i}}</option>
                            @endfor
                        </select>
                        <p class="date-select">-</p>
                        <select name="day" class="day">
                            @for($i=01; $i <= 31; $i++)
                            <option value="{{$i}}" {{ $i == 01 ? 'selected' : '' }}>{{$i}}</option>
                            @endfor
                        </select>
                    </div>
                    <button type="button" class="next-date-button">&gt;</button>
                    <button type="submit" class="submit-button" value="選択">選択</button>
                </div>
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
                            00:30:00
                        </td>
                        <td class="update-form__item">
                            09:30:00
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