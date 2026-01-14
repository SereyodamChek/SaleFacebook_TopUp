<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Topup;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

// KHQR SDK
use KHQR\BakongKHQR;
use KHQR\Helpers\KHQRData;
use KHQR\Models\IndividualInfo;

class TopupController extends Controller
{
    /**
     * Show topup form
     */
    public function create()
    {
        return view('front.topup.create');
    }

    /**
     * Generate KHQR
     * Route: GET /topup/pay
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
        ]);

        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        Wallet::firstOrCreate(
            ['user_id' => $user->id],
            [
                'balance' => 0,
                'total_deposit' => 0,
                'used_balance' => 0,
                'discount_percent' => 0,
            ]
        );

        $topup = Topup::create([
            'user_id'  => $user->id,
            'amount'   => $data['amount'],
            'currency' => '$',
            'status'   => 'pending',
        ]);

        $amountKHR = (int) round($topup->amount * 4100);

        $merchant = new IndividualInfo(
            bakongAccountID: config('bakong.account_id'),
            merchantName: config('bakong.merchant_name'),
            merchantCity: config('bakong.merchant_city'),
            currency: KHQRData::CURRENCY_KHR,
            amount: $amountKHR
        );

        try {
            $bakong   = new BakongKHQR(config('bakong.token'));
            $response = $bakong->generateIndividual($merchant);

            $qrData = $response->data ?? null;

            if (!is_array($qrData) || empty($qrData['qr']) || empty($qrData['md5'])) {
                throw new \Exception('Invalid KHQR response');
            }

            $topup->update([
                'qr'  => $qrData['qr'],
                'md5' => $qrData['md5'],
            ]);

            return redirect()->route('topup.show', $topup->id);

        } catch (\Throwable $e) {

            Log::error('Topup KHQR Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            $topup->update(['status' => 'failed']);

            return redirect()
                ->route('topup.create')
                ->with('error', 'Topup កើតមានបញ្ហា សូមព្យាយាមម្ដងទៀត');
        }
    }

    /**
     * Show QR page
     */
     /**
 * Show QR page
 */
public function show($id)
{
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    $topup = Topup::where('id', $id)
        ->where('user_id', Auth::id())
        ->first();

    if (!$topup) {
        return redirect()
            ->route('topup.create')
            ->with('error', 'Topup នេះមិនមែនជារបស់អ្នកទេ');
    }

    return view('front.topup.show', compact('topup'));
}
public function verify($id)
{
    $topup = Topup::find($id);

    if (!$topup) {
        return response()->json(['responseCode' => 1], 200);
    }

    // Already paid
    if ($topup->status === 'paid') {
        return response()->json([
            'responseCode' => 0,
            'message' => 'PAID'
        ], 200);
    }

    // ⏳ Wait at least 2 minutes
    if ($topup->created_at->diffInSeconds(now()) < 120) {
        return response()->json([
            'responseCode' => 1,
            'message' => 'PROCESSING'
        ], 200);
    }

    // ✅ SOFT CREDIT (industry practice)
    DB::transaction(function () use ($topup) {

        $locked = Topup::lockForUpdate()->find($topup->id);
        if ($locked->status === 'paid') {
            return;
        }

        $wallet = Wallet::lockForUpdate()->firstOrCreate(
            ['user_id' => $locked->user_id],
            [
                'balance' => 0,
                'total_deposit' => 0,
                'used_balance' => 0,
                'discount_percent' => 0,
            ]
        );

        $wallet->balance += $locked->amount;
        $wallet->total_deposit += $locked->amount;
        $wallet->save();

        $locked->update([
            'status'  => 'paid',
            'paid_at' => now(),
        ]);
    });

    return response()->json([
        'responseCode' => 0,
        'message' => 'PAID'
    ], 200);
}
}