<@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/date.css') }}">
@endsection

@section('content')
    <div class="atte__content">
        <!-- formタグで日付からの検索機能を作成 -->
        <div class="atte__date-title">
            <h2>日付を入力</h2>
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
                <!-- アットforeach挿入 -->
                <tr class="customer-table__row">
                    <div class="customer-table__item">
                        <td class="update-form__item">
                            テスト太郎
                            <!--  配列やじるしnameに書き換え、以下4つ同様 -->
                        </td>
                        <td class="update-form__item">
                            10:00:00
                        </td>
                        <td class="update-form__item">
                            20:00:00
                        </td>
                        <td class="update-form__item">
                            00:30:00
                        </td>
                        <td class="update-form__item">
                            09:30:00
                        </td>
                    </div>
                </tr>
            <div class="pagination">
                <!-- ぺジネーション挿入 -->
            </div>
        </table>
    </div>
@endsection