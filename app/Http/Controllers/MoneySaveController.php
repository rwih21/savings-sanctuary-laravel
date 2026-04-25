<?php

namespace App\Http\Controllers;

use App\Models\MoneySave;
use App\Exports\MoneySavesExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class MoneySaveController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month');
        $year  = $request->input('year');

        $query = MoneySave::orderBy('date_saved', 'desc');

        if ($month && $year) {
            $query->whereMonth('date_saved', $month)
                  ->whereYear('date_saved', $year);
        } elseif ($year) {
            $query->whereYear('date_saved', $year);
        }

        // grand total is always ALL time, not filtered
        $grandTotal = MoneySave::sum('amount_bf_saved') + MoneySave::sum('amount_gf_saved');

        // paginate: controller sends enough for both mobile (3) and desktop (5)
        // we handle the display limit in the blade
        $savings        = $query->paginate(5)->withQueryString();
        $savingsMobile  = MoneySave::orderBy('date_saved', 'desc')
                            ->when($month && $year, fn($q) => $q->whereMonth('date_saved', $month)->whereYear('date_saved', $year))
                            ->when(!$month && $year, fn($q) => $q->whereYear('date_saved', $year))
                            ->paginate(3, ['*'], 'mobile_page')
                            ->withQueryString();

        return view('index', compact('savings', 'savingsMobile', 'grandTotal', 'month', 'year'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'dateSaved' => 'required|date|unique:money_saves,date_saved',
            'bfSaved'   => 'required|integer|min:10000',
            'gfSaved'   => 'required|integer|min:10000',
        ]);

        MoneySave::create([
            'date_saved'      => $request->dateSaved,
            'amount_bf_saved' => $request->bfSaved,
            'amount_gf_saved' => $request->gfSaved,
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
            'date_saved'      => $request->dateSaved,
            'amount_bf_saved' => $request->bfSaved,
            'amount_gf_saved' => $request->gfSaved,
        ]);

        return redirect('/');
    }

    public function destroy(MoneySave $moneySave)
    {
        $moneySave->delete();
        return redirect('/');
    }

    public function export(Request $request)
    {
        $month    = $request->input('month');
        $year     = $request->input('year');
        $filename = 'savings';
        if ($year)  $filename .= '-' . $year;
        if ($month) $filename .= '-' . str_pad($month, 2, '0', STR_PAD_LEFT);
        $filename .= '.xlsx';

        return Excel::download(new MoneySavesExport($month, $year), $filename);
    }

    public function all(Request $request)
    {
        $month = $request->input('month');
        $year  = $request->input('year');

        $query = MoneySave::orderBy('date_saved', 'desc');

        if ($month && $year) {
            $query->whereMonth('date_saved', $month)
                  ->whereYear('date_saved', $year);
        } elseif ($year) {
            $query->whereYear('date_saved', $year);
        }

        $savings    = $query->get();
        $grandTotal = MoneySave::sum('amount_bf_saved') + MoneySave::sum('amount_gf_saved');

        return view('all', compact('savings', 'grandTotal', 'month', 'year'));
    }
}