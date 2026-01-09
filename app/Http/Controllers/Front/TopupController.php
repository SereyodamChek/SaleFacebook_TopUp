<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Topup;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

// KHQR
use KHQR\BakongKHQR;
use KHQR\Helpers\KHQRData;
use KHQR\Models\IndividualInfo;

class TopupController extends Controller
{
    // Show form: amount input
    public function create()
    {
        return view('front.topup.create');
    }

    /**
     * ✅ NEW: Manual QR scan page (user enters amount -> goes here)
     * This does NOT create Topup yet. It only shows the manual scan instructions page.
     */
    public function manualQr(Request $request)
    {
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
        ]);

        return view('front.topup.manual-qr-code-scan', [
            'amount' => $data['amount'],
        ]);
    }

    // Create topup + generate QR
    public function store(Request $request)
    {
       $data = $request->validate([
        'amount' => ['required', 'numeric', 'min:1'],
    ]);

    $user = Auth::user();

        // Ensure wallet exists
        Wallet::firstOrCreate(['user_id' => $user->id], [
            'balance' => 0,
            'total_deposit' => 0,
            'used_balance' => 0,
            'discount_percent' => 0,
        ]);

        // Create pending topup row
        $topup = Topup::create([
            'user_id'  => $user->id,
            'amount'   => $data['amount'],
            'currency' => '$', // UI currency; KHQR below uses KHR
            'status'   => 'pending',
        ]);

        // KHQR Merchant Info (from config)
        $merchant = new IndividualInfo(
            bakongAccountID: config('bakong.account_id'),
            merchantName: config('bakong.merchant_name'),
            merchantCity: config('bakong.merchant_city'),
            currency: KHQRData::CURRENCY_KHR,
            amount: (float) $topup->amount
        );

        $qrResponse = BakongKHQR::generateIndividual($merchant);

        $qr  = $qrResponse->data['qr']  ?? null;
        $md5 = $qrResponse->data['md5'] ?? null;

        if (!$qr || !$md5) {
            $topup->update(['status' => 'failed']);
            return back()->with('error', 'Failed to generate KHQR. Please try again.');
        }

        $topup->update([
            'qr'  => $qr,
            'md5' => $md5,
        ]);

        return redirect()->route('topup.show', $topup->id);
    }

    // Show QR page (with polling)
    public function show(Topup $topup)
    {
        abort_unless($topup->user_id === Auth::id(), 403);

        return view('front.topup.show', [
            'topup' => $topup,
        ]);
    }

    /**
     * Verify endpoint (AJAX polling)
     * Debug enabled with Log::info
     */
    public function verify(Request $request, Topup $topup)
    {
        abort_unless($topup->user_id === Auth::id(), 403);

        // ✅ Debug: request hit
        Log::info('TOPUP VERIFY HIT', [
            'topup_id' => $topup->id,
            'user_id'  => Auth::id(),
            'status'   => $topup->status,
            'md5'      => $topup->md5,
            'time'     => now()->toDateTimeString(),
        ]);

        // Already paid => stop polling
        if ($topup->status === 'paid') {
            Log::info('TOPUP ALREADY PAID - STOP POLLING', [
                'topup_id' => $topup->id,
            ]);

            return response()->json(['responseCode' => 0, 'already_paid' => true]);
        }

        if (!$topup->md5) {
            Log::warning('TOPUP VERIFY MISSING MD5', [
                'topup_id' => $topup->id,
            ]);

            return response()->json(['error' => 'Missing md5'], 422);
        }

        try {
            $token = config('bakong.token');

            if (!$token) {
                Log::error('TOPUP VERIFY MISSING TOKEN', [
                    'topup_id' => $topup->id,
                ]);

                return response()->json(['error' => 'Missing BAKONG_TOKEN'], 500);
            }

            // Bakong checker
            $bakong = new \KHQR\BakongKHQR($token);

            $result = $bakong->checkTransactionByMD5($topup->md5);

            // ✅ Debug: full raw response
            Log::info('TOPUP VERIFY RESULT', [
                'topup_id' => $topup->id,
                'md5'      => $topup->md5,
                'result'   => $result,
            ]);

            // Store payload (Topup.verify_payload should be JSON column)
            $topup->update([
                'verify_payload' => $result,
            ]);

            // ✅ responseCode could be in different places + string/int
            $code = data_get($result, 'responseCode', data_get($result, 'data.responseCode'));

            Log::info('TOPUP VERIFY CODE', [
                'topup_id' => $topup->id,
                'code_raw' => $code,
                'code_int' => (int) $code,
            ]);

            // SUCCESS condition
            if ((int) $code === 0) {

                DB::transaction(function () use ($topup) {

                    $lockedTopup = Topup::where('id', $topup->id)->lockForUpdate()->first();

                    Log::info('TOPUP LOCKED', [
                        'topup_id' => $lockedTopup->id,
                        'status'   => $lockedTopup->status,
                    ]);

                    if ($lockedTopup->status === 'paid') {
                        Log::info('TOPUP ALREADY PAID INSIDE TX', [
                            'topup_id' => $lockedTopup->id,
                        ]);
                        return;
                    }

                    $wallet = Wallet::where('user_id', $lockedTopup->user_id)->lockForUpdate()->first();

                    if (!$wallet) {
                        $wallet = Wallet::create([
                            'user_id' => $lockedTopup->user_id,
                            'balance' => 0,
                            'total_deposit' => 0,
                            'used_balance' => 0,
                            'discount_percent' => 0,
                        ]);

                        Log::info('WALLET CREATED', [
                            'wallet_id' => $wallet->id,
                            'user_id'   => $wallet->user_id,
                        ]);
                    }

                    $amount = (float) $lockedTopup->amount;

                    $beforeBalance = (float) $wallet->balance;
                    $beforeDeposit = (float) $wallet->total_deposit;

                    $wallet->balance       = $beforeBalance + $amount;
                    $wallet->total_deposit = $beforeDeposit + $amount;
                    $wallet->save();

                    Log::info('WALLET UPDATED', [
                        'user_id'         => $wallet->user_id,
                        'amount_added'    => $amount,
                        'balance_before'  => $beforeBalance,
                        'balance_after'   => (float) $wallet->balance,
                        'deposit_before'  => $beforeDeposit,
                        'deposit_after'   => (float) $wallet->total_deposit,
                    ]);

                    $lockedTopup->update([
                        'status'  => 'paid',
                        'paid_at' => now(),
                    ]);

                    Log::info('TOPUP MARKED PAID', [
                        'topup_id' => $lockedTopup->id,
                        'paid_at'  => $lockedTopup->paid_at,
                    ]);
                });

                // ✅ Return flat success for JS
                return response()->json(['responseCode' => 0, 'message' => 'PAID']);
            }

            // Pending / not found
            Log::info('TOPUP STILL PENDING', [
                'topup_id' => $topup->id,
                'code'     => $code,
            ]);

            return response()->json($result);

        } catch (\Exception $e) {
            Log::error('TOPUP VERIFY ERROR', [
                'topup_id' => $topup->id,
                'error'    => $e->getMessage(),
            ]);

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
