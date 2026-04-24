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

{{-- DESKTOP LOGS --}}
<div class="logs-container">
    <div class="logs-top-bar">
        <h2>Recent Logs</h2>
        <div class="logs-actions">
            {{-- Filter form --}}
            <form action="{{ route('home') }}" method="GET" class="filter-form">
                <select name="month">
                    <option value="">All Months</option>
                    @foreach(range(1,12) as $m)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                        </option>
                    @endforeach
                </select>
                <select name="year">
                    <option value="">All Years</option>
                    @foreach(range(date('Y'), 2024) as $y)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
                <button type="submit" class="filter-btn">Filter</button>
                @if($month || $year)
                    <a href="{{ route('home') }}" class="clear-btn">Clear</a>
                @endif
            </form>

            {{-- Export button --}}
            <a href="{{ route('savings.export', ['month' => $month, 'year' => $year]) }}" class="export-btn">
                Export Excel
            </a>
        </div>
    </div>

    <div class="log-header-row">
        <div class="log-header-column">Date</div>
        <div class="log-header-column">BF Saved</div>
        <div class="log-header-column">GF Saved</div>
        <div class="log-header-column">Total</div>
        <div class="log-header-column">Surplus</div>
        <div class="cta"></div>
    </div>

    @forelse($savings as $item)
    <div class="log-row-card">
        <div class="log-column">{{ \Carbon\Carbon::parse($item->date_saved)->format('M d, Y') }}</div>
        <div class="log-column">Rp {{ number_format($item->amount_bf_saved) }}</div>
        <div class="log-column">Rp {{ number_format($item->amount_gf_saved) }}</div>
        <div class="log-column"><strong>Rp {{ number_format($item->total) }}</strong></div>
        <div class="log-column">{{ number_format($item->surplus) }}</div>
        <div class="cta"><a href="{{ route('savings.edit', $item) }}">Edit</a></div>
    </div>
    @empty
    <div class="log-empty">No entries found.</div>
    @endforelse

    {{-- Desktop pagination --}}
    <div class="pagination-bar">
        @if($savings->onFirstPage())
            <span class="page-btn disabled">← Prev</span>
        @else 
            <a href="{{ $savings->previousPageUrl() }}" class="page-btn">← Prev</a>
        @endif

        <span class="page-info">{{ $savings->currentPage() }} / {{ $savings->lastPage() }}</span>

        @if($savings->hasMorePages())
            <a href="{{ $savings->nextPageUrl() }}" class="page-btn">Next →</a>
        @else
            <span class="page-btn disabled">Next →</span>
        @endif
    </div>
</div>

{{-- MOBILE LOGS --}}
<div class="logs-container-mobile">
    <div class="logs-mobile-header">
        <h2>Recent Logs</h2>
        <a href="{{ route('savings.all') }}" class="view-all-btn">View All</a>
    </div>
    @forelse($savingsMobile as $item)
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
    <div class="log-empty">No entries found.</div>
    @endforelse

    {{-- Mobile pagination --}}
    <div class="pagination-bar-mobile">
        @if($savingsMobile->onFirstPage())
            <span class="page-btn disabled">← Prev</span>
        @else
            <a href="{{ $savingsMobile->previousPageUrl() }}" class="page-btn">← Prev</a>
        @endif

        <span class="page-info">{{ $savingsMobile->currentPage() }} / {{ $savingsMobile->lastPage() }}</span>

        @if($savingsMobile->hasMorePages())
            <a href="{{ $savingsMobile->nextPageUrl() }}" class="page-btn">Next →</a>
        @else
            <span class="page-btn disabled">Next →</span>
        @endif
    </div>

    {{-- Mobile: View All link --}}
    {{-- <a href="{{ route('savings.all') }}" class="view-all-btn">View All</a> --}}
</div>
@endsection