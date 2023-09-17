@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/date.css') }}">
@endsection

@section('content')
    <div class="atte__content">
        <div class ="atte__date-search">
            {{ $user->name }}さんの勤怠一覧
        </div>
        <div class="atte__customer">
            <table class="customer-table__inner">
                <tr class="customer-table__row">
                    <div class="customer-table__header">
                        <th class="customer-table__header-span">日付</th>
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
                            {{\Carbon\Carbon::parse($attendance->start_time)->format('Y-m-d')}}
                        </td>
                        <td class="update-form__item">
                            {{\Carbon\Carbon::parse($attendance->start_time)->format('H:i:s')}}
                        </td>
                        <td class="update-form__item">
                            @if(isset($attendance->end_time))
                                {{\Carbon\Carbon::parse($attendance->end_time)->format('H:i:s')}}
                            @else
                                ―
                            @endif
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