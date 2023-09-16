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
                <!-- アットフォーリーチ -->
                <tr class="customer-table__row">
                    <div class="customer-table__item">
                        <td class="update-form__item">
                            2000/01/01
                        </td>
                        <td class="update-form__item">
                            09:00
                        </td>
                        <td class="update-form__item">
                            18:00
                        </td>
                        <td class="update-form__item">
                            01:00
                        </td>
                        <td class="update-form__item">
                            08:00
                        </td>
                    </div>
                </tr>
                <!-- エンドフォーリーチ -->
            </table>
        <div class="pagination">
            <!-- ページネーション -->
        </div>
    </div>
@endsection