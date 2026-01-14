<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Topup;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

// ✅ OFFICIAL KHQR SDK
use KHQR\BakongKHQR;
use KHQR\Helpers\KHQRData;
use KHQR\Models\IndividualInfo;

class TopupController extends Controller
{
    public function create()
    {
        return view('front.topup.create');
    }

    // ✅ Generate KHQR
    public function store(Request $request)
    {
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
        ]);

        $user = Auth::user();

        Wallet::firstOrCreate(
            ['user_id' => $user->id],
            ['balance' => 0, 'total_deposit' => 0, 'used_balance' => 0, 'discount_percent' => 0]
        );

        $topup = Topup::create([
            'user_id'  => $user->id,
            'amount'   => $data['amount'], // USD
            'currency' => '$',
            'status'   => 'pending',
        ]);

        // ✅ USD → KHR
        $amountKHR = (int) round($topup->amount * 4100);

        // ✅ Merchant info
        $merchant = new IndividualInfo(
            bakongAccountID: config('bakong.account_id'),
            merchantName: config('bakong.merchant_name'),
            merchantCity: config('bakong.merchant_city'),
            currency: KHQRData::CURRENCY_KHR,
            amount: $amountKHR
        );

        // ✅ Generate QR
        $bakong = new BakongKHQR(config('bakong.token'));
        $response = $bakong->generateIndividual($merchant);

        if (
            empty($response->data['qr']) ||
            empty($response->data['md5'])
        ) {
            $topup->update(['status' => 'failed']);
            return back()->with('error', 'Cannot generate KHQR');
        }

        $topup->update([
            'qr'  => $response->data['qr'],
            'md5' => $response->data['md5'],
        ]);

        return redirect()->route('topup.show', $topup->id);
    }

    public function show(Topup $topup)
    {
        abort_unless($topup->user_id === Auth::id(), 403);

        return view('front.topup.show', compact('topup'));
    }

    // ✅ Manual Verify (cPanel friendly)
    public function verify(Topup $topup)
    {
        abort_unless($topup->user_id === Auth::id(), 403);

        if ($topup->status === 'paid') {
            return response()->json(['responseCode' => 0]);
        }

        $bakong = new BakongKHQR(config('bakong.token'));
        $result = $bakong->checkTransactionByMD5($topup->md5);

        $code = data_get($result, 'responseCode', data_get($result, 'data.responseCode'));

        if ((int) $code === 0) {
            DB::transaction(function () use ($topup) {
                $locked = Topup::lockForUpdate()->find($topup->id);
                if ($locked->status === 'paid') return;

                $wallet = Wallet::lockForUpdate()->firstOrCreate(
                    ['user_id' => $locked->user_id],
                    ['balance' => 0, 'total_deposit' => 0, 'used_balance' => 0, 'discount_percent' => 0]
                );

                $wallet->balance += $locked->amount;
                $wallet->total_deposit += $locked->amount;
                $wallet->save();

                $locked->update([
                    'status'  => 'paid',
                    'paid_at' => now(),
                ]);
            });

            return response()->json(['responseCode' => 0, 'message' => 'PAID']);
        }

        return response()->json(['responseCode' => 1, 'message' => 'NOT PAID']);
    }
}
