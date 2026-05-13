{{-- File: resources/views/partials/seating-map.blade.php --}}

<style>
.sm-preview{border-radius:10px;overflow:hidden;margin-bottom:14px;position:relative;cursor:pointer;height:190px;background:#111}
.sm-preview:hover .sm-preview__ov{opacity:1}
#sm-mini{width:100%;height:100%;pointer-events:none}
.sm-preview__ov{position:absolute;inset:0;background:rgba(0,0,0,.5);display:flex;flex-direction:column;align-items:center;justify-content:center;opacity:0;transition:opacity .2s;gap:6px;color:#fff;font-size:13px;font-weight:600}
.sm-btn{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;background:linear-gradient(135deg,#C8102E 0%,#9e0b22 100%);color:#fff!important;text-decoration:none;font-family:'DM Sans',sans-serif;font-size:14px;font-weight:800;letter-spacing:1px;text-transform:uppercase;padding:14px;border-radius:10px;border:none;cursor:pointer;box-shadow:0 4px 20px rgba(200,16,46,.45);transition:transform .2s,box-shadow .2s}
.sm-btn:hover{transform:translateY(-2px);box-shadow:0 8px 28px rgba(200,16,46,.60);color:#fff}
.sm-legend{display:flex;flex-wrap:wrap;gap:5px 12px;margin-bottom:12px}
.sm-legend__item{display:flex;align-items:center;gap:5px;font-size:11px;color:rgba(255,255,255,.7)}
.sm-legend__dot{width:8px;height:8px;border-radius:50%;flex-shrink:0}

/* Modal */
.sm-bd{display:none;position:fixed;inset:0;background:rgba(0,0,0,.75);z-index:9999;align-items:center;justify-content:center;padding:12px}
.sm-bd.open{display:flex}
.sm-modal{background:#fff;border-radius:14px;width:100%;max-width:1100px;max-height:90vh;display:flex;flex-direction:column;overflow:hidden;box-shadow:0 24px 80px rgba(0,0,0,.5);animation:smIn .2s ease}
@keyframes smIn{from{opacity:0;transform:scale(.95) translateY(10px)}to{opacity:1;transform:scale(1) translateY(0)}}
.sm-hd{display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-bottom:1px solid #eee;flex-shrink:0}
.sm-hd__title{font-size:16px;font-weight:700;color:#111;margin:0}
.sm-hd__sub{font-size:12px;color:#888;margin:2px 0 0}
.sm-hd__x{background:#f0f0f0;border:none;border-radius:50%;width:32px;height:32px;display:flex;align-items:center;justify-content:center;font-size:16px;color:#555;cursor:pointer;transition:background .15s;flex-shrink:0}
.sm-hd__x:hover{background:#ddd}

/* Zoom hint bar */
.sm-zoom-hint{
    display:none;
    background:#f0f4ff;
    border-bottom:1px solid #dde4f5;
    padding:7px 16px;
    font-size:12px;
    color:#3b5bdb;
    align-items:center;
    justify-content:space-between;
    gap:10px;
    flex-shrink:0;
}
.sm-zoom-hint span{display:flex;align-items:center;gap:6px}
.sm-zoom-hint__reset{
    background:#3b5bdb;color:#fff;border:none;
    border-radius:6px;padding:4px 12px;
    font-size:11px;font-weight:700;cursor:pointer;
    white-space:nowrap;transition:background .15s;
}
.sm-zoom-hint__reset:hover{background:#2f4bc7}

/* +/- zoom buttons overlaid on chart - IMPROVED STYLES */
.sm-zoom-btns{position:absolute;top:16px;left:16px;z-index:25;display:none;flex-direction:column;border-radius:8px;overflow:hidden;box-shadow:0 4px 16px rgba(0,0,0,.3)}
.sm-zoom-btns button{width:40px;height:40px;background:rgba(13,13,13,0.85);backdrop-filter:blur(8px);border:none;border-bottom:1px solid rgba(255,255,255,0.1);font-size:20px;font-weight:500;color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;line-height:1;transition:all 0.12s ease}
.sm-zoom-btns button:last-child{border-bottom:none}
.sm-zoom-btns button:hover{background:rgba(200,16,46,0.8)}
.sm-zoom-btns button:active{transform:scale(0.95)}

/* IMPROVED SCROLLABLE CHART CONTAINER */
.sm-body{flex:1;min-height:0;display:flex;overflow:auto}
.sm-chart{flex:1;min-width:0;position:relative;background:#f5f5f5;overflow:hidden;min-height:550px;max-height:70vh}

/* seats.io renders here — with scrolling support */
#sm-full{width:100%;height:600px;min-height:600px;overflow:hidden;transform-origin:top left;will-change:transform}
#sm-full iframe{width:100%;height:100%;border:0;display:block}

/* Custom scrollbar styling */
.sm-chart::-webkit-scrollbar{width:8px;height:8px}
.sm-chart::-webkit-scrollbar-track{background:#e0e0e0;border-radius:4px}
.sm-chart::-webkit-scrollbar-thumb{background:#C8102E;border-radius:4px}
.sm-chart::-webkit-scrollbar-thumb:hover{background:#9e0b22}

.sm-sb{width:300px;flex-shrink:0;border-left:1px solid #eee;display:flex;flex-direction:column;overflow-y:auto}
@media(max-width:700px){.sm-body{flex-direction:column}.sm-sb{width:100%;border-left:none;border-top:1px solid #eee;max-height:280px}}
.sm-sec{padding:14px 16px;border-bottom:1px solid #f2f2f2}
.sm-sec__h{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:#aaa;margin:0 0 10px}
.sm-empty{text-align:center;padding:28px 16px;color:#bbb;font-size:13px;line-height:1.7}
.sm-empty svg{display:block;margin:0 auto 10px;opacity:.25}
.sm-list{list-style:none;padding:0;margin:0}
.sm-list li{display:flex;align-items:center;padding:7px 0;border-bottom:1px solid #f5f5f5;gap:8px}
.sm-list li:last-child{border-bottom:none}
.sm-list__info{flex:1;min-width:0}
.sm-list__lbl{font-size:13px;font-weight:700;color:#111}
.sm-list__cat{font-size:11px;color:#888;margin-top:1px}
.sm-list__px{font-size:13px;font-weight:700;color:#111;white-space:nowrap;flex-shrink:0}
.sm-list__del{flex-shrink:0;width:22px;height:22px;border-radius:50%;background:#fee2e2;border:none;color:#dc2626;font-size:13px;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:background .14s}
.sm-list__del:hover{background:#fca5a5}
.sm-fee{display:flex;justify-content:space-between;font-size:12px;color:#666;margin-bottom:5px}
.sm-fee--t{font-size:14px;font-weight:700;color:#111;margin-top:8px;padding-top:8px;border-top:1px solid #eee}
.sm-form .sm-fld{margin-bottom:9px}
.sm-form label{display:block;font-size:12px;font-weight:600;color:#444;margin-bottom:3px}
.sm-form input{width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;color:#111;box-sizing:border-box;transition:border-color .14s}
.sm-form input:focus{outline:none;border-color:#C8102E}
.sm-form input.err{border-color:#e53e3e}
.sm-timer{background:#fff8f0;border-top:1px solid #fde8d0;padding:7px 16px;font-size:12px;color:#b34a00;display:flex;align-items:center;gap:6px;flex-shrink:0}
.sm-timer.urgent{background:#fff0f0;border-top-color:#fca5a5;color:#c00}
.sm-cta{padding:13px 16px;border-top:1px solid #eee;flex-shrink:0}
.sm-proceed{display:flex;align-items:center;justify-content:center;gap:7px;width:100%;padding:12px 16px;background:#C8102E;color:#fff!important;border:none;border-radius:8px;font-size:14px;font-weight:700;cursor:pointer;transition:background .15s}
.sm-proceed:hover:not(:disabled){background:#9e0b22}
.sm-proceed:disabled{background:#ccc;cursor:not-allowed}
.sm-spin-wrap{position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:12px;background:#f5f5f5;z-index:10;transition:opacity .4s}
.sm-spin-wrap.gone{opacity:0;pointer-events:none}
.sm-spin{width:38px;height:38px;border:3px solid #e5e5e5;border-top-color:#C8102E;border-radius:50%;animation:spin .7s linear infinite}
@keyframes spin{to{transform:rotate(360deg)}}
.sm-spin-wrap p{font-size:13px;color:#999;margin:0}
</style>

@php
    $smColors = ['#C8102E','#1a7ad4','#2eaa5e','#9b59b6','#f39c12','#e74c3c'];
    $smTypes  = $show->activeTicketTypes()->get();
@endphp

@if($smTypes->count())
<div class="sm-legend">
    @foreach($smTypes as $i => $tt)
    <div class="sm-legend__item">
        <span class="sm-legend__dot" style="background:{{ $smColors[$i % count($smColors)] }}"></span>
        <span>{{ $tt->name }} — {{ $tt->formatted_price }}</span>
    </div>
    @endforeach
</div>
@endif

<div class="sm-preview" onclick="smOpen()">
    <div id="sm-mini"></div>
    <div class="sm-preview__ov">
        <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/>
        </svg>
        Click to explore &amp; select seats
    </div>
</div>

<button class="sm-btn" onclick="smOpen()">
    <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/>
    </svg>
    Buy on Map
</button>

<div class="sm-bd" id="smBd" onclick="smBdClick(event)">
    <div class="sm-modal" onclick="event.stopPropagation()">

        <div class="sm-hd">
            <div>
                <p class="sm-hd__title">{{ $show->title }}</p>
                <p class="sm-hd__sub">
                    {{ $show->start_date?->format('l, F j, Y · g:i A') }}
                    @if($show->venue) · {{ $show->venue->name }} @endif
                </p>
            </div>
            <button class="sm-hd__x" onclick="smClose()" aria-label="Close">&#x2715;</button>
        </div>

        {{-- Zoom instruction bar (shown after chart loads) --}}
        <div class="sm-zoom-hint" id="smZoomHint">
            <span>
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0">
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35M11 8v6M8 11h6"/>
                </svg>
                Scroll mouse wheel to zoom · Click a section to zoom in · Pinch on mobile
            </span>
            <button class="sm-zoom-hint__reset" id="smResetView">Reset View</button>
        </div>

        <div class="sm-body">
            <div class="sm-chart">
                <div class="sm-spin-wrap" id="smSpinner">
                    <div class="sm-spin"></div>
                    <p>Loading seating map…</p>
                </div>
                <div id="sm-full"></div>
                <div class="sm-zoom-btns" id="smZoomBtns">
                    <button id="smZoomIn" title="Zoom in">+</button>
                    <button id="smZoomOut" title="Zoom out">&minus;</button>
                </div>
            </div>

            <div class="sm-sb">
                <div class="sm-sec" id="smEmpty">
                    <div class="sm-empty">
                        <svg width="42" height="42" fill="none" stroke="currentColor" stroke-width="1.1" viewBox="0 0 24 24">
                            <rect x="2" y="7" width="20" height="14" rx="2"/>
                            <path d="M16 7V5a2 2 0 0 0-4 0v2M8 7V5a2 2 0 0 0-4 0v2"/>
                        </svg>
                        Click any seat on the map to add it here.
                    </div>
                </div>

                <div class="sm-sec" id="smSeats" style="display:none">
                    <p class="sm-sec__h">Your Selection</p>
                    <ul class="sm-list" id="smList"></ul>
                </div>

                <div class="sm-sec" id="smSummary" style="display:none">
                    <p class="sm-sec__h">Order Summary</p>
                    <div class="sm-fee"><span>Subtotal</span><span id="smSub">$0.00</span></div>
                    <div class="sm-fee"><span>Service fee (3%)</span><span id="smSvc">$0.00</span></div>
                    <div class="sm-fee"><span>Processing fee</span><span id="smPrc">$0.00</span></div>
                    <div class="sm-fee sm-fee--t"><span>Total</span><span id="smTot">$0.00</span></div>
                </div>

                <div class="sm-sec" id="smFormWrap" style="display:none">
                    <p class="sm-sec__h">Your Details</p>
                    <form class="sm-form" id="smForm"
                          action="{{ route('booking.initiate', $show->id) }}"
                          method="POST">
                        @csrf
                        <input type="hidden" name="hold_token"     id="smHold" value="{{ $holdToken ?? '' }}">
                        <input type="hidden" name="selected_seats" id="smData" value="">
                        <div class="sm-fld">
                            <label>Full Name <span style="color:#C8102E">*</span></label>
                            <input type="text" name="customer_name" id="smName"
                                   placeholder="Your full name" required value="{{ old('customer_name') }}">
                        </div>
                        <div class="sm-fld">
                            <label>Email <span style="color:#C8102E">*</span></label>
                            <input type="email" name="customer_email" id="smEmail"
                                   placeholder="your@email.com" required value="{{ old('customer_email') }}">
                        </div>
                        <div class="sm-fld">
                            <label>Phone</label>
                            <input type="tel" name="customer_phone" id="smPhone"
                                   placeholder="Optional" value="{{ old('customer_phone') }}">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="sm-timer" id="smTimer">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
            </svg>
            Seats held for <strong id="smTimerVal">15:00</strong> — complete checkout before time expires.
        </div>

        <div class="sm-cta">
            <button class="sm-proceed" id="smGo" disabled onclick="smSubmit()">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
                Proceed to Payment
            </button>
        </div>
    </div>
</div>

<script>
(function () {

    var WS_KEY  = @json($show->seatsio_public_key);
    var EVT_KEY = @json($show->seatsio_event_key);
    var HOLD    = @json($holdToken ?? '');
    var RURL    = '{{ route("booking.refresh-hold", $show->id) }}';
    var CSRF    = (document.querySelector('meta[name="csrf-token"]') || {}).content || '';

    var PRICES = {
        @foreach($smTypes as $tt)
        @if($tt->seatsio_category_key)
        @json($tt->seatsio_category_key): {{ (float)$tt->price }},
        @endif
        @endforeach
    };

    var miniDone   = false;
    var fullInited = false;
    var chartInst  = null;
    var sel        = [];

    /* ── Zoom state — outer scope so all callbacks can access ── */
    var zoomLevel = 1;
    var minZoom   = 0.5;
    var maxZoom   = 3.0;
    var zoomStep  = 0.2;
    var smFull    = null;
    var smChart   = null;

    function applyZoom() {
        if (!smFull || !smChart) return;
        smFull.style.transform       = 'scale(' + zoomLevel + ')';
        smFull.style.transformOrigin = 'top left';
        smFull.style.transition      = 'transform 0.18s ease';
        smChart.style.overflow       = 'auto';
        smFull.style.marginBottom    = ((zoomLevel - 1) * 600) + 'px';
        smFull.style.marginRight     = ((zoomLevel - 1) * smChart.clientWidth) + 'px';
    }

    function loadSDK(cb) {
        if (window.seatsio) { cb(); return; }
        var s = document.createElement('script');
        s.src = 'https://cdn-na.seatsio.net/chart.js';
        s.onload = cb;
        s.onerror = function () { console.error('seats.io SDK failed to load'); };
        document.head.appendChild(s);
    }

    /* ── Mini static preview ─────────────────────────────────── */
    function initMini() {
        if (miniDone) return;
        miniDone = true;
        loadSDK(function () {
            new seatsio.SeatingChart({
                divId: 'sm-mini', workspaceKey: WS_KEY, event: EVT_KEY,
                mode: 'static', showLegend: false, showMinimap: false,
            }).render();
        });
    }

    /* ── Full interactive chart ──────────────────────────────── */
    function initFull() {
        if (fullInited) return;
        fullInited = true;

        loadSDK(function () {
            new seatsio.SeatingChart({
                divId:                    'sm-full',
                workspaceKey:             WS_KEY,
                event:                    EVT_KEY,
                holdToken:                HOLD || undefined,
                showLegend:               true,
                showMinimap:              true,
                showZoomOutButtonOnMobile: true,
                fitTo:                    'width',
                region:                   'na',
                showSectionContents:      'always',
                objectWithoutPricingSelectable: false,
                canGASelectionBeIncreased: function() { return true; },

                // Zoom and scroll settings
                scrollWheelZoom: true,
                pinchToZoom: true,
                doubleClickZoom: true,

                // Margin to prevent clipping
                margin: { top: 20, right: 20, bottom: 20, left: 20 },

                pricing: Object.keys(PRICES).map(function (k) {
                    return { category: k, price: PRICES[k], formattedPrice: '$' + PRICES[k].toFixed(2) };
                }),

                tooltipInfo: function (obj) {
                    var key = obj.category ? obj.category.key : '';
                    var lbl = obj.category ? obj.category.label : '';
                    var p   = PRICES[key];
                    return p !== undefined ? lbl + ' — $' + p.toFixed(2) : lbl;
                },

                onChartRendered: function (chart) {
                    chartInst = chart;
                    smFull  = document.getElementById('sm-full');
                    smChart = smFull ? smFull.parentElement : null;

                    /* Hide spinner */
                    document.getElementById('smSpinner').classList.add('gone');

                    /* Show zoom instruction bar */
                    document.getElementById('smZoomHint').style.display = 'flex';

                    /* Wire Reset View button */
                    document.getElementById('smResetView').onclick = function () {
                        chartInst.resetView();
                        zoomLevel = 1;
                        if (smFull) {
                            smFull.style.transform    = 'scale(1)';
                            smFull.style.marginBottom = '0';
                            smFull.style.marginRight  = '0';
                        }
                        if (smChart) {
                            smChart.scrollTop  = 0;
                            smChart.scrollLeft = 0;
                        }
                    };

                    /* Show +/- zoom buttons */
                    document.getElementById('smZoomBtns').style.display = 'flex';

                    document.getElementById('smZoomIn').onclick = function () {
                        zoomLevel = Math.min(maxZoom, zoomLevel + zoomStep);
                        applyZoom();
                    };

                    document.getElementById('smZoomOut').onclick = function () {
                        zoomLevel = Math.max(minZoom, zoomLevel - zoomStep);
                        applyZoom();
                    };
                },

                onObjectSelected: function (obj) {
                    sel.push(obj);
                    renderSidebar();
                    /* Cancel seats.io auto-zoom: reset view then re-apply our CSS scale */
                    setTimeout(function () {
                        if (chartInst) chartInst.resetView();
                        applyZoom();
                    }, 50);
                },

                onObjectDeselected: function (obj) {
                    sel = sel.filter(function (s) { return s.id !== obj.id; });
                    renderSidebar();
                    setTimeout(function () {
                        if (chartInst) chartInst.resetView();
                        applyZoom();
                    }, 50);
                },

                onHoldTokenExpired: function () {
                    alert('Your seat hold has expired. The page will reload.');
                    window.location.reload();
                },
            }).render();
        });
    }

    /* ── Remove seat ─────────────────────────────────────────── */
    window.smRemoveSeat = function (seatId) {
        if (!chartInst) return;
        chartInst.deselectObjects([seatId]);
    };

    /* ── Rebuild sidebar ─────────────────────────────────────── */
    function renderSidebar() {
        var has = sel.length > 0;
        document.getElementById('smEmpty').style.display    = has ? 'none'  : 'block';
        document.getElementById('smSeats').style.display    = has ? 'block' : 'none';
        document.getElementById('smSummary').style.display  = has ? 'block' : 'none';
        document.getElementById('smFormWrap').style.display = has ? 'block' : 'none';
        document.getElementById('smGo').disabled            = !has;

        var list = document.getElementById('smList');
        list.innerHTML = '';
        var subtotal = 0;

        sel.forEach(function (obj) {
            var key   = obj.category ? obj.category.key   : '';
            var lbl   = obj.category ? obj.category.label : '';
            var price = PRICES[key] || 0;
            subtotal += price;
            var li = document.createElement('li');
            li.innerHTML =
                '<div class="sm-list__info">'
                +   '<div class="sm-list__lbl">' + (obj.label || obj.id) + '</div>'
                +   '<div class="sm-list__cat">' + lbl + '</div>'
                + '</div>'
                + '<span class="sm-list__px">$' + price.toFixed(2) + '</span>'
                + '<button class="sm-list__del" title="Remove seat" '
                +   'onclick="smRemoveSeat(' + JSON.stringify(obj.id) + ')">&#x2715;</button>';
            list.appendChild(li);
        });

        var n  = sel.length;
        var sf = n > 0 ? Math.max(subtotal * 0.03, 2.00) : 0;
        var pf = n * 1.50;
        document.getElementById('smSub').textContent = '$' + subtotal.toFixed(2);
        document.getElementById('smSvc').textContent = '$' + sf.toFixed(2);
        document.getElementById('smPrc').textContent = '$' + pf.toFixed(2);
        document.getElementById('smTot').textContent = '$' + (subtotal + sf + pf).toFixed(2);

        document.getElementById('smData').value = JSON.stringify(
            sel.map(function (obj) {
                return {
                    id: obj.id, label: obj.label,
                    categoryLabel: obj.category ? obj.category.label : '',
                    category: { key: obj.category ? obj.category.key : '', label: obj.category ? obj.category.label : '' },
                    labels: { section: obj.labels ? obj.labels.section : null, parent: obj.labels ? obj.labels.parent : null, own: obj.labels ? obj.labels.own : null },
                };
            })
        );
    }

    window.smSubmit = function () {
        var n = document.getElementById('smName');
        var e = document.getElementById('smEmail');
        n.classList.remove('err'); e.classList.remove('err');
        if (!n.value.trim())                              { n.classList.add('err'); n.focus(); return; }
        if (!e.value.trim() || e.value.indexOf('@') < 0) { e.classList.add('err'); e.focus(); return; }
        document.getElementById('smGo').disabled    = true;
        document.getElementById('smGo').textContent = 'Redirecting…';
        document.getElementById('smForm').submit();
    };

    window.smOpen = function () {
        document.getElementById('smBd').classList.add('open');
        document.body.style.overflow = 'hidden';
        initFull();
    };
    window.smClose = function () {
        document.getElementById('smBd').classList.remove('open');
        document.body.style.overflow = '';
    };
    window.smBdClick = function (e) {
        if (e.target.id === 'smBd') smClose();
    };
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') smClose();
    });

    var secs = 15 * 60;
    var timerEl = document.getElementById('smTimerVal');
    var timerBr = document.getElementById('smTimer');
    var tick = setInterval(function () {
        secs = Math.max(0, secs - 1);
        var m = Math.floor(secs / 60), s = secs % 60;
        if (timerEl) timerEl.textContent = m + ':' + (s < 10 ? '0' : '') + s;
        if (secs <= 120) timerBr.classList.add('urgent');
        if (secs === 0)  clearInterval(tick);
    }, 1000);

    if (HOLD) {
        setInterval(function () {
            fetch(RURL, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                body: JSON.stringify({ hold_token: HOLD }),
            }).then(function (r) { return r.json(); })
              .then(function (d) { if (d.success) secs = d.expires_in_minutes * 60; })
              .catch(function () {});
        }, 10 * 60 * 1000);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMini);
    } else {
        initMini();
    }

})();
</script>
