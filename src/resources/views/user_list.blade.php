@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/date.css') }}">
@endsection

@section('content')
    <div class="atte__content">
        <div class ="atte__date-search">
            ユーザー一覧
        </div>
        <div class="atte__customer">
            <table class="customer-table__inner">
                <tr class="customer-table__row">
                    <div class="customer-table__header">
                        <th class="customer-table__header-span">名前</th>
                    </div>
                </tr>
                @foreach($users as user)
                <tr class="customer-table__row">
                    <div class="customer-table__item">
                        <td class="update-form__item">
                        {{ $user->name }}
                        </td>
                    </div>
                </tr>
                @endforeach
            </table>
        <div class="pagination">
            <!-- ページネーション挿入 -->
        </div>
    </div>
@endsection