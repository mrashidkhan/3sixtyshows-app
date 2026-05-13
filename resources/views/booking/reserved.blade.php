@extends('layouts.master')

@push('head_scripts')
    <script src="https://cdn-na.seatsio.net/chart.js"></script>
@endpush

@push('early_styles')
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}" defer></script>
@endpush

@section('title', 'Book Tickets — ' . $show->title)

@section('content')

<div class="container py-5">
    <div class="row">

        {{-- LEFT: Show info + Seating chart widget --}}
        <div class="col-lg-8">

            <div class="mb-4">
                <h2>{{ $show->title }}</h2>
                <p class="text-muted">
                    <i class="fa fa-calendar"></i> {{ $show->start_date->format('l, F d, Y') }} &nbsp;
                    <i class="fa fa-clock-o"></i> {{ $show->start_date->format('g:i A') }} &nbsp;
                    <i class="fa fa-map-marker"></i> {{ $show->venue->name ?? '' }}
                </p>
            </div>

            @if($ticketTypes->isNotEmpty())
            <div class="card mb-4">
                <div class="card-header"><strong>Ticket Pricing</strong></div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Category</th>
                                <th>Seats.io Key</th>
                                <th class="text-right">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ticketTypes as $tt)
                            <tr>
                                <td>
                                    @if($tt->color)
                                        <span style="display:inline-block;width:12px;height:12px;background:{{ $tt->color }};border-radius:2px;margin-right:6px;"></span>
                                    @endif
                                    {{ $tt->name }}
                                </td>
                                <td><code>{{ $tt->seatsio_category_key ?? '—' }}</code></td>
                                <td class="text-right"><strong>{{ $tt->formatted_price }}</strong></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong>Select Your Seats</strong>
                    <span class="text-muted small">
                        <i class="fa fa-clock-o"></i>
                        Seats held for <span id="hold-timer">15:00</span>
                    </span>
                </div>
                <div class="card-body p-2">
                    <div id="seating-chart" style="min-height: 480px;"></div>
                </div>
            </div>

        </div>

        {{-- RIGHT: Booking summary + Customer details form --}}
        <div class="col-lg-4">

            <div class="card mb-4" id="selection-summary" style="display: none;">
                <div class="card-header"><strong>Your Selection</strong></div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Seat</th>
                                <th>Category</th>
                                <th class="text-right">Price</th>
                            </tr>
                        </thead>
                        <tbody id="selected-seats-tbody"></tbody>
                        <tfoot>
                            <tr class="table-light">
                                <td colspan="2"><strong>Subtotal</strong></td>
                                <td class="text-right"><strong id="subtotal-display">$0.00</strong></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-muted small">Service fee (3%)</td>
                                <td class="text-right text-muted small" id="service-fee-display">$0.00</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-muted small">Processing fee</td>
                                <td class="text-right text-muted small" id="processing-fee-display">$0.00</td>
                            </tr>
                            <tr class="table-success">
                                <td colspan="2"><strong>Total</strong></td>
                                <td class="text-right"><strong id="grand-total-display">$0.00</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="card" id="checkout-form-card" style="display: none;">
                <div class="card-header"><strong>Your Details</strong></div>
                <div class="card-body">
                    <form id="booking-form"
                          action="{{ route('booking.initiate', $show->id) }}"
                          method="POST">
                        @csrf
                        <input type="hidden" name="hold_token" id="form-hold-token" value="{{ $holdToken }}">
                        <input type="hidden" name="selected_seats" id="form-selected-seats" value="">

                        <div class="form-group">
                            <label for="customer_name">Full Name <span class="text-danger">*</span></label>
                            <input type="text" id="customer_name" name="customer_name"
                                   class="form-control @error('customer_name') is-invalid @enderror"
                                   value="{{ old('customer_name') }}" required>
                            @error('customer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="customer_email">Email <span class="text-danger">*</span></label>
                            <input type="email" id="customer_email" name="customer_email"
                                   class="form-control @error('customer_email') is-invalid @enderror"
                                   value="{{ old('customer_email') }}" required>
                            @error('customer_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="customer_phone">Phone</label>
                            <input type="tel" id="customer_phone" name="customer_phone"
                                   class="form-control" value="{{ old('customer_phone') }}">
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                @foreach ($errors->all() as $error)<div>{{ $error }}</div>@endforeach
                            </div>
                        @endif

                        <button type="submit" id="submit-booking" class="btn btn-success btn-block" disabled>
                            <i class="fa fa-lock"></i> Proceed to Payment
                        </button>
                        <p class="text-muted small mt-2 text-center">
                            Your seats are held for 15 minutes. Payment completes the booking.
                        </p>
                    </form>
                </div>
            </div>

            <div class="alert alert-info" id="select-prompt">
                <i class="fa fa-hand-pointer-o"></i>
                Click seats on the chart to select them.
            </div>

        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
(function () {
    const PUBLIC_KEY  = @json($seatsioPublicKey);
    const EVENT_KEY   = @json($show->seatsio_event_key);
    const HOLD_TOKEN  = @json($holdToken);
    const SHOW_ID     = {{ $show->id }};
    const REFRESH_URL = '{{ route("booking.refresh-hold", $show->id) }}';

    const PRICE_MAP = {
        @foreach($ticketTypes as $tt)
        @if($tt->seatsio_category_key)
        '{{ $tt->seatsio_category_key }}': {{ (float)$tt->price }},
        @endif
        @endforeach
    };

    let selectedObjects = [];

    const chart = new window.SeatsioSeatingChart({
        publicKey:  PUBLIC_KEY,
        event:      EVENT_KEY,
        holdToken:  HOLD_TOKEN,
        divId:      'seating-chart',
        region:     'na',
        pricing: Object.entries(PRICE_MAP).map(([key, price]) => ({
            category: key, price: price, formattedPrice: '$' + price.toFixed(2)
        })),
        onObjectSelected:   function (obj) { selectedObjects.push(obj); updateSummary(); },
        onObjectDeselected: function (obj) { selectedObjects = selectedObjects.filter(o => o.id !== obj.id); updateSummary(); },
        onHoldTokenExpired: function () { alert('Your seat hold has expired. The page will reload.'); window.location.reload(); },
        tooltipInfo: function (object) {
            const cat = object.category ? object.category.label : '';
            const price = PRICE_MAP[object.category ? object.category.key : ''];
            return price !== undefined ? cat + ' — $' + price.toFixed(2) : cat;
        },
    });

    chart.render();

    function updateSummary() {
        const tbody       = document.getElementById('selected-seats-tbody');
        const summary     = document.getElementById('selection-summary');
        const formCard    = document.getElementById('checkout-form-card');
        const prompt      = document.getElementById('select-prompt');
        const submitBtn   = document.getElementById('submit-booking');
        const hiddenInput = document.getElementById('form-selected-seats');

        tbody.innerHTML = '';
        let subtotal = 0;

        selectedObjects.forEach(function (obj) {
            const catKey   = obj.category ? obj.category.key : '';
            const catLabel = obj.categoryLabel || (obj.category && obj.category.label) || '';
            const price    = PRICE_MAP[catKey] || 0;
            subtotal += price;
            const row = document.createElement('tr');
            row.innerHTML = '<td>' + (obj.label || obj.id) + '</td><td>' + catLabel + '</td><td class="text-right">$' + price.toFixed(2) + '</td>';
            tbody.appendChild(row);
        });

        const serviceFee    = Math.max(subtotal * 0.03, 2.00);
        const processingFee = selectedObjects.length * 1.50;
        const grandTotal    = subtotal + serviceFee + processingFee;

        document.getElementById('subtotal-display').textContent       = '$' + subtotal.toFixed(2);
        document.getElementById('service-fee-display').textContent    = '$' + serviceFee.toFixed(2);
        document.getElementById('processing-fee-display').textContent = '$' + processingFee.toFixed(2);
        document.getElementById('grand-total-display').textContent    = '$' + grandTotal.toFixed(2);

        const hasSelection     = selectedObjects.length > 0;
        summary.style.display  = hasSelection ? 'block' : 'none';
        formCard.style.display = hasSelection ? 'block' : 'none';
        prompt.style.display   = hasSelection ? 'none'  : 'block';
        submitBtn.disabled     = !hasSelection;

        hiddenInput.value = JSON.stringify(selectedObjects.map(obj => ({
            id: obj.id, label: obj.label, categoryLabel: obj.categoryLabel || '',
            category: { key: obj.category ? obj.category.key : '', label: obj.category ? obj.category.label : '' },
            labels: { section: obj.labels ? obj.labels.section : null, parent: obj.labels ? obj.labels.parent : null, own: obj.labels ? obj.labels.own : null },
        })));
    }

    let secondsLeft = 15 * 60;
    const timerEl   = document.getElementById('hold-timer');
    setInterval(function () {
        secondsLeft--;
        const m = Math.floor(secondsLeft / 60);
        const s = secondsLeft % 60;
        if (timerEl) timerEl.textContent = m + ':' + (s < 10 ? '0' : '') + s;
        if (secondsLeft <= 0) clearInterval(this);
    }, 1000);

    setInterval(function () {
        fetch(REFRESH_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify({ hold_token: HOLD_TOKEN }),
        })
        .then(r => r.json())
        .then(data => { if (data.success) secondsLeft = data.expires_in_minutes * 60; })
        .catch(err => console.warn('Hold token refresh failed', err));
    }, 10 * 60 * 1000);

})();
</script>
@endsection
