@extends('layouts.base')

@section('title', 'Savings Sanctuary')

@section('body')
<div class="header-container">
    <div class="daily-target">
        <h2>DAILY TARGET</h2>
        <div class="money">Rp 92,000</div>
    </div>
    <div class="running-total">
        <h2>RUNNING TOTAL</h2>
        <div class="money">Rp {{ number_format($grandTotal) }}</div>
    </div>
</div>

<div class="saving-form">
    <form action="{{ route('savings.store') }}" method="POST">
        @csrf
        <label for="dateSaved">Date</label><br>
        <input name="dateSaved" type="date" required><br>

        <div class="amount-field-container">
            <div class="amount-field">
                <label for="bfSaved">BF Amount</label>
                <input type="number" name="bfSaved" required min="10000" placeholder="10000">
            </div>
            <div class="amount-field">
                <label for="gfSaved">GF Amount</label>
                <input type="number" name="gfSaved" required min="10000" placeholder="10000">
            </div>
        </div>

        <button type="submit" class="submitBtn">Add Entry</button>
    </form>
</div>

{{-- Desktop logs --}}
<div class="logs-container">
    <h2>Recent Logs</h2>
    <div class="log-header-row">
        <div class="log-header-column">Date</div>
        <div class="log-header-column">BF Saved</div>
        <div class="log-header-column">GF Saved</div>
        <div class="log-header-column">Total</div>
        <div class="log-header-column">Surplus</div>
        <div class="cta"></div>
    </div>

    @foreach($savings as $item)
    <div class="log-row-card">
        <div class="log-column">{{ \Carbon\Carbon::parse($item->date_saved)->format('M d, Y') }}</div>
        <div class="log-column">Rp {{ number_format($item->amount_bf_saved) }}</div>
        <div class="log-column">Rp {{ number_format($item->amount_gf_saved) }}</div>
        <div class="log-column"><strong>Rp {{ number_format($item->total) }}</strong></div>
        <div class="log-column">{{ number_format($item->surplus) }}</div>
        <div class="cta">
            <a href="{{ route('savings.edit', $item) }}">Edit</a>
        </div>
    </div>
    @endforeach
</div>

{{-- Mobile logs --}}
<div class="logs-container-mobile">
    <h2>Recent Logs</h2>
    @foreach($savings as $item)
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
    @endforeach
</div>
@endsection