<?php

namespace App\Http\Controllers;

use App\Models\MoneySave;
use Illuminate\Http\Request;

class MoneySaveController extends Controller
{
    public function index()
    {
        $savings = MoneySave::orderBy('date_saved', 'desc')->get();
        $grandTotal = MoneySave::sum('amount_bf_saved') + MoneySave::sum('amount_gf_saved');

        return view('index', compact('savings', 'grandTotal'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'dateSaved' => 'required|date',
            'bfSaved'   => 'required|integer|min:10000',
            'gfSaved'   => 'required|integer|min:10000',
        ]);

        MoneySave::create([
            'date_saved'       => $request->dateSaved,
            'amount_bf_saved'  => $request->bfSaved,
            'amount_gf_saved'  => $request->gfSaved,
        ]);

        return redirect('/');
    }

    public function edit(MoneySave $moneySave)
    {
        return view('edit', compact('moneySave'));
    }

    public function update(Request $request, MoneySave $moneySave)
    {
        $request->validate([
            'dateSaved' => 'required|date',
            'bfSaved'   => 'required|integer|min:10000',
            'gfSaved'   => 'required|integer|min:10000',
        ]);

        $moneySave->update([
            'date_saved'       => $request->dateSaved,
            'amount_bf_saved'  => $request->bfSaved,
            'amount_gf_saved'  => $request->gfSaved,
        ]);

        return redirect('/');
    }

    public function destroy(MoneySave $moneySave)
    {
        $moneySave->delete();
        return redirect('/');
    }
}