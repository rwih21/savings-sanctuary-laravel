@extends('layouts.base')

@section('title', 'All Entries')

@section('body')
<div class="logs-container-mobile" style="display:flex">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px">
        <h2 style="margin:0">All Entries</h2>
        <a href="{{ route('home') }}" class="edit" style="font-size:14px">← Back</a>
    </div>

    @forelse($savings as $item)
    <div class="log-card">
        <div class="log-row-mob">
            <div class="date">{{ \Carbon\Carbon::parse($item->date_saved)->format('M d, Y') }}</div>
            <div class="surplus">{{ number_format($item->surplus) }}</div>
        </div>
        <div class="log-row-mob">
            <div class="amount">
                <div>BF: {{ \App\Models\MoneySave::formatCurrency($item->amount_bf_saved) }}</div>
                <div> | </div>
                <div>GF: {{ \App\Models\MoneySave::formatCurrency($item->amount_gf_saved) }}</div>
            </div>
            <div class="total">Rp {{ number_format($item->total) }}</div>
        </div>
        <div class="cta-mob">
            <a href="{{ route('savings.edit', $item) }}" class="edit">Edit</a>
            <form action="{{ route('savings.destroy', $item) }}" method="POST" style="display:inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="delete" onclick="return confirm('Delete this entry?')">
                    <img class="delete-icon-red" src="{{ asset('img/delete-icon-red.svg') }}" alt="Delete">
                    <img class="delete-icon" src="{{ asset('img/delete_icon.svg') }}" alt="Delete Hover">
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="log-empty">No entries yet.</div>
    @endforelse
</div>
@endsection