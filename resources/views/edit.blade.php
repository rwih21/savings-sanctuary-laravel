@extends('layouts.base')

@section('title', 'Edit Entry')

@section('body')
<div class="saving-form">
    <h2 style="margin-top:0">Edit Entry</h2>
    <form action="{{ route('savings.update', $moneySave) }}" method="POST">
        @csrf
        @method('PUT')

        <label for="dateSaved">Date</label><br>
        <input name="dateSaved" type="date" required
            value="{{ \Carbon\Carbon::parse($moneySave->date_saved)->format('Y-m-d') }}"><br>

        <div class="amount-field-container">
            <div class="amount-field">
                <label for="bfSaved">BF Amount</label>
                <input type="number" name="bfSaved" required min="10000"
                    value="{{ $moneySave->amount_bf_saved }}">
            </div>
            <div class="amount-field">
                <label for="gfSaved">GF Amount</label>
                <input type="number" name="gfSaved" required min="10000"
                    value="{{ $moneySave->amount_gf_saved }}">
            </div>
        </div>

        <button type="submit" class="submitBtn">Save Changes</button>
    </form>
</div>
@endsection