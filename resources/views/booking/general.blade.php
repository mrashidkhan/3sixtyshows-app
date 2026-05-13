{{-- resources/views/booking/general.blade.php --}}
{{-- General Admission ticket selection page --}}

@extends('layouts.master')

@section('title', 'Book Tickets — ' . $show->title)

@push('early_styles')
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}" defer></script>
@endpush

@section('content')

<div class="container py-5">
    <div class="row">

        {{-- LEFT: Show info + Ticket selection --}}
        <div class="col-lg-8">

            {{-- Show header --}}
            <div class="mb-4">
                <h2>{{ $show->title }}</h2>
                <p class="text-muted">
                    <i class="fa fa-calendar"></i> {{ $show->start_date->format('l, F d, Y') }} &nbsp;
                    <i class="fa fa-clock-o"></i> {{ $show->start_date->format('g:i A') }} &nbsp;
                    <i class="fa fa-map-marker"></i> {{ $show->venue->name ?? '' }}
                </p>
            </div>

            {{-- Ticket Types --}}
            @if($ticketTypes->isNotEmpty())
            <div class="card mb-4">
                <div class="card-header"><strong>Select Tickets</strong></div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Ticket Type</th>
                                <th class="text-right">Price</th>
                                <th style="width:140px;">Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ticketTypes as $index => $tt)
                            <tr>
                                <td>
                                    @if($tt->color)
                                        <span style="display:inline-block;width:12px;height:12px;background:{{ $tt->color }};border-radius:2px;margin-right:6px;"></span>
                                    @endif
                                    <strong>{{ $tt->name }}</strong>
                                    @if($tt->description)
                                        <br><small class="text-muted">{{ $tt->description }}</small>
                                    @endif
                                </td>
                                <td class="text-right align-middle">
                                    <strong>{{ $tt->formatted_price }}</strong>
                                </td>
                                <td class="align-middle">
                                    <input type="hidden" name="ticket_types[{{ $index }}][id]"
                                           value="{{ $tt->id }}" form="booking-form">
                                    <input type="number"
                                           name="ticket_types[{{ $index }}][quantity]"
                                           form="booking-form"
                                           class="form-control form-control-sm ticket-qty"
                                           data-price="{{ (float)$tt->price }}"
                                           data-name="{{ $tt->name }}"
                                           min="0" max="20" value="0">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @else
            <div class="alert alert-warning">
                No tickets are currently available for this show.
            </div>
            @endif

        </div>

        {{-- RIGHT: Order summary + Customer form --}}
        <div class="col-lg-4">

            {{-- Order Summary --}}
            <div class="card mb-4" id="order-summary" style="display:none;">
                <div class="card-header"><strong>Order Summary</strong></div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <tbody id="summary-tbody"></tbody>
                        <tfoot>
                            <tr class="table-light">
                                <td><strong>Subtotal</strong></td>
                                <td class="text-right"><strong id="subtotal-display">$0.00</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted small">Service fee (3%)</td>
                                <td class="text-right text-muted small" id="service-fee-display">$0.00</td>
                            </tr>
                            <tr>
                                <td class="text-muted small">Processing fee</td>
                                <td class="text-right text-muted small" id="processing-fee-display">$0.00</td>
                            </tr>
                            <tr class="table-success">
                                <td><strong>Total</strong></td>
                                <td class="text-right"><strong id="grand-total-display">$0.00</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- Customer Details Form --}}
            <div class="card" id="customer-form-card" style="display:none;">
                <div class="card-header"><strong>Your Details</strong></div>
                <div class="card-body">
                    <form id="booking-form"
                          action="{{ route('ga-booking.select-tickets', $show->slug) }}"
                          method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="customer_name">Full Name <span class="text-danger">*</span></label>
                            <input type="text" id="customer_name" name="customer_name"
                                   class="form-control @error('customer_name') is-invalid @enderror"
                                   value="{{ old('customer_name') }}" required>
                            @error('customer_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="customer_email">Email <span class="text-danger">*</span></label>
                            <input type="email" id="customer_email" name="customer_email"
                                   class="form-control @error('customer_email') is-invalid @enderror"
                                   value="{{ old('customer_email') }}" required>
                            @error('customer_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="customer_phone">Phone</label>
                            <input type="tel" id="customer_phone" name="customer_phone"
                                   class="form-control"
                                   value="{{ old('customer_phone') }}">
                        </div>

                        @if($errors->any())
                            <div class="alert alert-danger">
                                @foreach($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        <button type="submit" id="btn-proceed"
                                class="btn btn-success btn-block" disabled>
                            <i class="fa fa-lock"></i> Proceed to Payment
                        </button>
                        <p class="text-muted small mt-2 text-center">
                            Secure checkout — payment on next step.
                        </p>
                    </form>
                </div>
            </div>

            {{-- Prompt --}}
            <div class="alert alert-info" id="select-prompt">
                <i class="fa fa-ticket"></i>
                Select ticket quantities above to continue.
            </div>

        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
(function () {
    const qtyInputs = document.querySelectorAll('.ticket-qty');
    const summary   = document.getElementById('order-summary');
    const formCard  = document.getElementById('customer-form-card');
    const prompt    = document.getElementById('select-prompt');
    const btnProceed = document.getElementById('btn-proceed');
    const tbody     = document.getElementById('summary-tbody');

    function updateSummary() {
        tbody.innerHTML = '';
        let subtotal   = 0;
        let totalQty   = 0;

        qtyInputs.forEach(function (input) {
            const qty   = parseInt(input.value) || 0;
            const price = parseFloat(input.dataset.price) || 0;
            const name  = input.dataset.name;

            if (qty > 0) {
                const lineTotal = qty * price;
                subtotal  += lineTotal;
                totalQty  += qty;

                const row = document.createElement('tr');
                row.innerHTML = '<td>' + name + ' x' + qty + '</td>'
                              + '<td class="text-right">$' + lineTotal.toFixed(2) + '</td>';
                tbody.appendChild(row);
            }
        });

        const serviceFee    = totalQty > 0 ? Math.max(subtotal * 0.03, 2.00) : 0;
        const processingFee = totalQty * 1.50;
        const grandTotal    = subtotal + serviceFee + processingFee;

        document.getElementById('subtotal-display').textContent      = '$' + subtotal.toFixed(2);
        document.getElementById('service-fee-display').textContent   = '$' + serviceFee.toFixed(2);
        document.getElementById('processing-fee-display').textContent = '$' + processingFee.toFixed(2);
        document.getElementById('grand-total-display').textContent   = '$' + grandTotal.toFixed(2);

        const hasSelection = totalQty > 0;
        summary.style.display  = hasSelection ? 'block' : 'none';
        formCard.style.display = hasSelection ? 'block' : 'none';
        prompt.style.display   = hasSelection ? 'none'  : 'block';
        btnProceed.disabled    = !hasSelection;
    }

    qtyInputs.forEach(function (input) {
        input.addEventListener('input', updateSummary);
        input.addEventListener('change', updateSummary);
    });

    updateSummary();
})();
</script>
@endsection
