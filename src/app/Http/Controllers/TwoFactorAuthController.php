<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\CustomTwoFactorAuthenticationNotification;
use Laravel\Fortify\TwoFactorQrCodeResponse;

class TwoFactorAuthController extends Controller
{
    protected function sendTwoFactorCodeNotification(Request $request)
    {
        $user = $request->user();
        // カスタムの通知クラスを使用してメールを送信
        $user->notify(new CustomTwoFactorAuthenticationNotification());
        return response()->json(['status' => 'メールが送信されました']);
    }
    public function enable(Request $request)
    {
        // 2要素認証を有効にするロジックを実装
        
        // 2要素認証が成功したら、リダイレクトする
        return redirect()->route('profile')->with('status', 'two-factor-authentication-enabled');
    }

    public function show(Request $request)
    {
        // ユーザーからQRコードを取得
        $qrCode = new TwoFactorQrCodeResponse($request->user());

        return response()->json(['svg' => $qrCode->toSvg()]);
    }
}


