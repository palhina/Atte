@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/date.css') }}">
@endsection

@section('content')
    <div class="atte__content">
        <div class="atte__date-container">
            <form action="/attendance" method="get">
            @csrf
                <div class ="atte__date-search">
                    <button type="submit" class="prev-date-button" value="{{ \Carbon\Carbon::parse($today)->subDay()->toDateString() }}">&lt;</button>
                    <div class ="atte__date">
                        <input class="search__date-form" type="text" name="search_date" value="{{ $today }}" required/>
                    </div>
                    <button type="submit" class="next-date-button" value="{{ \Carbon\Carbon::parse($today)->addDay()->toDateString() }}">&gt;</button>
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
                            訂正中
                        </td>
                        <td class="update-form__item">
                            後で訂正
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