<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');

        $users = User::query()
            ->where('role', 'customer') // or ->where('is_admin', 0)
            ->with('wallet')
            ->when($q, function ($query) use ($q) {
                $query->where(function ($q2) use ($q) {
                    $q2->where('name', 'like', "%{$q}%")
                       ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        // ✅ auto-create missing wallets
        foreach ($users as $u) {
            if (!$u->wallet) {
                $u->wallet()->create([
                    'balance' => 0,
                    'total_deposit' => 0,
                    'used_balance' => 0,
                    'discount_percent' => 0,
                ]);
                $u->load('wallet');
            }
        }

        return view('admin.wallets.index', compact('users', 'q'));
    }

    public function edit(Wallet $wallet)
    {
        $wallet->load('user');

        return view('admin.wallets.edit', compact('wallet'));
    }

    public function update(Request $request, Wallet $wallet)
    {
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
            'discount_percent' => ['nullable', 'integer', 'min:0', 'max:100'],
        ]);

        $amount = (float) $data['amount'];

        $wallet->balance = $wallet->balance + $amount;
        $wallet->total_deposit = $wallet->total_deposit + $amount;

        if ($request->filled('discount_percent')) {
            $wallet->discount_percent = (int) $data['discount_percent'];
        }

        $wallet->save();

        return redirect()
            ->route('admin.wallets.index')
            ->with('success', 'Wallet topped up successfully.');
    }
}
