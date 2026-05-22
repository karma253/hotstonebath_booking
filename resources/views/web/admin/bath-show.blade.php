@extends('layouts.admin-layout')

@section('title', 'Bath Details')
@section('page-title', 'Bath Details')

@section('styles')
<style>
    :root{
        --accent-brown:#7a4a2b;
        --muted:#6b6b7d;
        --card-bg:#fffaf8;
        --light:#fbf8f6;
        --badge-bg:#f3e9e1;
    }
    .hero {
        border-radius:14px;
        overflow:hidden;
        height:220px;
        background-size:cover;
        background-position:center;
        position:relative;
        box-shadow:0 8px 24px rgba(0,0,0,0.08);
    }
    .hero-overlay{ position:absolute; inset:0; background:linear-gradient(180deg, rgba(0,0,0,0.15), rgba(0,0,0,0.35)); }
    .hero-content{ position:absolute; left:24px; bottom:22px; color:#fff; }
    .bath-title{ font-size:30px; font-weight:800; letter-spacing:0.2px; }
    .owner-meta{ color:rgba(255,255,255,0.9); margin-top:6px; }

    .page-grid{ display:grid; grid-template-columns: 1fr 360px; gap:24px; align-items:start; margin-top:18px; }
    .card { background:white; border-radius:12px; padding:18px; box-shadow:0 6px 20px rgba(18,18,18,0.04); }
    .small-card{ background:var(--light); border-radius:10px; padding:14px; }

    .fac-badges { display:flex; flex-wrap:wrap; gap:10px; }
    .fac-badge{ background:var(--badge-bg); padding:8px 12px; border-radius:10px; color:var(--muted); font-weight:600; display:inline-flex; gap:8px; align-items:center; }

    .gallery-grid{ display:grid; grid-template-columns: 1fr 1fr 1fr; gap:10px; }
    .gallery-grid .main{ grid-column:1/3; grid-row:1/3; height:240px; border-radius:10px; overflow:hidden; }
    .gallery-grid img{ width:100%; height:100%; object-fit:cover; display:block; border-radius:8px; }

    .details-right .detail-row{ display:flex; align-items:center; gap:12px; padding:10px 0; border-bottom:1px dashed #eee; }
    .detail-icon{ width:42px; height:42px; background:var(--card-bg); border-radius:8px; display:flex; align-items:center; justify-content:center; color:var(--accent-brown); font-weight:700; }

    .btn-brown{ background:var(--accent-brown); color:white; border-radius:10px; padding:10px 14px; border:none; }
    .btn-outline-danger{ background:transparent; color:var(--accent-brown); border:1px solid #f1c9bf; border-radius:10px; padding:10px 14px; }

    .service-card{ border-radius:10px; background:#fff; padding:14px; box-shadow:0 6px 18px rgba(0,0,0,0.03); }

    /* Assign Inspection form styles (three step layout) */
    .assign-steps { display:flex; flex-direction:column; gap:18px; }
    .assign-step { background:#fff; border-radius:12px; padding:16px; border:1px solid rgba(122,74,43,0.08); }
    .step-header { display:flex; gap:12px; align-items:center; }
    .step-number { width:36px; height:36px; border-radius:50%; background:#fff3ec; color:var(--accent-brown); display:flex; align-items:center; justify-content:center; font-weight:800; border:1px solid rgba(246,223,210,0.6); }
    .step-title { font-weight:700; display:flex; align-items:center; gap:10px; }
    .step-icon { width:18px; height:18px; display:inline-block; vertical-align:middle; }
    .muted-small { color:var(--muted); font-size:0.92rem; }
    .form-row { display:flex; gap:12px; flex-wrap:wrap; }
    .form-col { flex:1; min-width:180px; }
    .assign-actions { display:flex; gap:12px; justify-content:flex-end; margin-top:8px; }
    .btn-send { background:var(--accent-brown); color:white; padding:10px 18px; border-radius:8px; border:none; display:inline-flex; gap:8px; align-items:center; }
    /* Animations */
    #service-overview, #inspection-summary { transition: transform .22s ease, box-shadow .22s ease; }
    #service-overview.pulse, #inspection-summary.pulse { transform: translateY(-6px); box-shadow:0 12px 30px rgba(122,74,43,0.08); }
    #service-overview img { transition: opacity .28s ease, transform .28s ease; }
    .fade-in { transition: opacity .25s ease, transform .18s ease; }
    .text-fade { opacity:0; transform:translateY(6px); }
    .btn-cancel { background:transparent; border:1px solid #eee; padding:10px 14px; border-radius:8px; }

    @media(max-width: 992px){ .page-grid{ grid-template-columns: 1fr; } .gallery-grid .main{ grid-column:auto; grid-row:auto; height:180px; } }
</style>
@endsection

@section('content')
@php
    // Hero image: prefer first bath image or fallback to placeholder
    $hero = null;
        if(!empty($bath->image)){
            $hero = asset('storage/' . $bath->image);
        } elseif(isset($bath->images) && $bath->images->isNotEmpty()){
            $firstBathImage = $bath->images->first();
            $hero = asset('storage/' . ltrim($firstBathImage->image_path ?? $firstBathImage->path ?? '', '/'));
    } else {
        $bathType = strtolower((string) ($bath->bath_type ?? ''));
        if (in_array($bathType, ['menchu', 'hot_stone_bath', 'hot stone bath'], true)) {
            $hero = asset('image/' . rawurlencode('hot stone bath.png'));
        } elseif (in_array($bathType, ['dotsho', 'medicinal', 'medicinal water'], true)) {
            $hero = asset('image/' . rawurlencode('medicinal water.png'));
        } elseif (in_array($bathType, ['tshachu', 'hot_spring', 'hot spring'], true)) {
            $hero = asset('image/' . rawurlencode('simtokha hot bath.jpg'));
        } else {
            $hero = asset('image/' . rawurlencode('background image.png'));
        }
    }
@endphp

<div class="d-flex justify-content-between align-items-center mb-2">
    <a href="{{ route('admin.baths') }}" class="text-muted" style="text-decoration:none;">← Back to Baths</a>
</div>

<div class="page-grid">
    <div>
        <div class="hero" style="background-image: url('{{ $hero }}')">
            <div class="hero-overlay"></div>
            <div class="hero-content">
                <div class="bath-title">{{ $bath->name }}</div>
                <div class="owner-meta">{{ $bath->owner->name ?? 'Owner' }} · {{ optional($bath->dzongkhag)->name ?? '' }}</div>
            </div>
            
        </div>

        @if(!empty($bath->other_facilities))
            <div class="mt-3 small text-muted">Other Facilities: {{ $bath->other_facilities }}</div>
        @endif

        <div class="card mt-3">
            <h5>Service Images</h5>
            <div class="gallery-grid mt-3">
                @php
                    $imgs = collect();
                    if(!empty($bath->image)) $imgs->push($bath->image);
                    if(method_exists($bath,'images')) $imgs = $imgs->merge($bath->images->pluck('image_path'));
                @endphp

                @if($imgs->isEmpty())
                    <div class="text-muted">No images uploaded.</div>
                @else
                    <div class="main"><img src="{{ asset('storage/' . ltrim($imgs[0], '/')) }}"></div>
                    @foreach($imgs->slice(1,5) as $i)
                        <div><img src="{{ asset('storage/' . ltrim($i, '/')) }}"></div>
                    @endforeach
                @endif
            </div>
            <p class="mt-2 small text-muted">Upload up to 10 images. Allowed formats: JPG, JPEG, PNG, WEBP.</p>
        </div>

        

        </div>

        <div class="card mt-3">
            <h5>Facilities</h5>
            <div class="card-body p-0 mt-2">
                <div class="p-3">
                    <div class="fac-badges">
                        @if($bath->facilities && $bath->facilities->isNotEmpty())
                            @foreach($bath->facilities as $facility)
                                <div class="fac-badge">{{ $facility->facility_name }}</div>
                            @endforeach
                        @else
                            {{-- default facilities when owner hasn't added any --}}
                            @php
                                $defaultFacilities = ['Hot Stone','Private Room','Shower','Changing Room','Tea/Refreshments'];
                            @endphp
                            @foreach($defaultFacilities as $f)
                                <div class="fac-badge">{{ $f }}</div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <h5>Assign Inspection to Staff</h5>
            @php
                $servicesList = $servicesList ?? ($services ?? $bath->services ?? collect());
                $latestOwnerService = $servicesList->first();
                $latestOwnerServiceBath = $latestOwnerService?->bath ?? $latestOwnerBath ?? $bath;
                $latestOwnerServiceImage = null;
                if ($latestOwnerServiceBath && $latestOwnerServiceBath->images && $latestOwnerServiceBath->images->isNotEmpty()) {
                    $latestOwnerServiceImage = $latestOwnerServiceBath->images->first()->image_path ?? $latestOwnerServiceBath->images->first()->path ?? null;
                }
            @endphp
            <div class="small text-muted mb-3">Assign a wellness service for inspection and send notification.</div>

            <form method="POST" action="{{ route('admin.inspection.assign') }}" enctype="multipart/form-data" class="mt-3 assign-steps">
                @csrf

                <div class="assign-step">
                    <div class="step-header">
                        <div class="step-number">1</div>
                        <div>
                            <div class="step-title">Recent Owner-added Service</div>
                            <div class="muted-small">Automatically loaded from the newest owner submission</div>
                        </div>
                    </div>

                    @if($latestOwnerService || $latestOwnerServiceBath)
                        @if($latestOwnerService)
                            <input type="hidden" name="bath_service_id" value="{{ $latestOwnerService->id }}">
                        @endif
                        <input type="hidden" name="bath_id" value="{{ $latestOwnerServiceBath->id ?? '' }}">
                        <div class="mt-3 small border rounded p-3 bg-light d-flex gap-3 align-items-start">
                            <div style="width:88px; height:72px; border-radius:10px; overflow:hidden; background:#f6f2ef; flex:0 0 auto;">
                                @if($latestOwnerServiceImage)
                                    <img src="{{ asset('storage/' . ltrim($latestOwnerServiceImage, '/')) }}" alt="{{ $latestOwnerServiceBath->name ?? 'Owner listing' }}" style="width:100%; height:100%; object-fit:cover; display:block;">
                                @else
                                    <div class="d-flex align-items-center justify-content-center h-100 small text-muted">No image</div>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold">{{ $latestOwnerService->service_type ?? $latestOwnerServiceBath->name ?? 'Owner-added Service' }}</div>
                                <div class="muted-small">Bath: {{ $latestOwnerServiceBath->name ?? 'N/A' }}</div>
                                <div class="muted-small">Owner: {{ $latestOwnerServiceBath->owner->name ?? 'N/A' }}</div>
                                @if($latestOwnerService && $latestOwnerService->price !== null)
                                    <div class="muted-small">Price: Nu. {{ number_format((float) $latestOwnerService->price, 2) }}</div>
                                @endif
                                @if($latestOwnerServiceBath->facilities && $latestOwnerServiceBath->facilities->isNotEmpty())
                                    <div class="muted-small">Facilities: {{ $latestOwnerServiceBath->facilities->pluck('facility_name')->join(', ') }}</div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="mt-3 muted-small">No owner-added service has been added yet.</div>
                    @endif
                </div>

                <div class="assign-step">
                    <div class="step-header">
                        <div class="step-number">2</div>
                        <div>
                            <div class="step-title">
                                <svg class="step-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 12a5 5 0 100-10 5 5 0 000 10zm0 2c-5.33 0-8 2.67-8 4v2h16v-2c0-1.33-2.67-4-8-4z" fill="#7A4A2B"/></svg>
                                Select Staff
                            </div>
                            <div class="muted-small">Assign to staff and set schedule</div>
                        </div>
                    </div>

                    <div class="mt-3 form-row">
                        <div class="form-col">
                            @php
                                $preferredStaffEmail = '11606003606@rim.edu.bt';
                                $preferredStaff = ($staffMembers ?? collect())->firstWhere('email', $preferredStaffEmail);
                            @endphp
                            <label class="form-label small mb-1">Assign To Staff</label>
                            <select name="staff_id" class="form-select form-select-sm" required>
                                <option value="">Select staff member</option>
                                @foreach(($staffMembers ?? collect()) as $staff)
                                    <option value="{{ $staff->id }}" {{ optional($preferredStaff)->id == $staff->id ? 'selected' : '' }}>{{ $staff->name }}{{ !empty($staff->email) ? ' (' . $staff->email . ')' : '' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-col">
                            <label class="form-label small mb-1">Inspection Type</label>
                            <select name="inspection_type" class="form-select form-select-sm">
                                <option value="regular">Regular Inspection</option>
                                <option value="site_check">Site Check</option>
                                <option value="safety_check">Safety Check</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-3 form-row">
                        <div class="form-col">
                            <label class="form-label small mb-1">Expected Date</label>
                            <input type="date" name="expected_date" class="form-control form-control-sm">
                        </div>
                        <div class="form-col">
                            <label class="form-label small mb-1">Expected Time</label>
                            <div class="d-flex gap-2">
                                <select name="expected_hour" class="form-select form-select-sm">
                                    <option value="">Hour</option>
                                    @for($hour = 1; $hour <= 12; $hour++)
                                        <option value="{{ $hour }}" {{ (string) old('expected_hour') === (string) $hour ? 'selected' : '' }}>{{ sprintf('%02d', $hour) }}</option>
                                    @endfor
                                </select>

                                <select name="expected_minute" class="form-select form-select-sm">
                                    <option value="">Minute</option>
                                    @for($minute = 0; $minute < 60; $minute++)
                                        @php $minuteValue = sprintf('%02d', $minute); @endphp
                                        <option value="{{ $minuteValue }}" {{ old('expected_minute') === $minuteValue ? 'selected' : '' }}>{{ $minuteValue }}</option>
                                    @endfor
                                </select>

                                <select name="expected_period" class="form-select form-select-sm">
                                    <option value="">AM/PM</option>
                                    <option value="AM" {{ old('expected_period') === 'AM' ? 'selected' : '' }}>AM</option>
                                    <option value="PM" {{ old('expected_period') === 'PM' ? 'selected' : '' }}>PM</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="assign-step">
                    <div class="step-header">
                        <div class="step-number">3</div>
                        <div>
                            <div class="step-title">
                                <svg class="step-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M20 4H4v12h8l4 4V4z" fill="#7A4A2B"/></svg>
                                Message / Instructions for Staff
                            </div>
                            <div class="muted-small">Provide subject, message and attachments</div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="form-label small mb-1">Message / Instructions</label>
                        <textarea name="message" rows="4" class="form-control form-control-sm" placeholder="Please conduct a thorough inspection and upload photos after finishing."></textarea>
                    </div>

                    <div class="assign-actions">
                        <a href="{{ route('admin.baths', $bath) }}" class="btn-cancel">Cancel</a>
                        <button type="submit" class="btn-send">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" style="transform:rotate(-18deg);"><path d="M2 21l21-9L2 3v7l15 2-15 2v7z" fill="#fff"/></svg>
                            Send Assignment
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <aside class="details-right">
        <div class="card">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <div class="small text-muted">Base Price per Session</div>
                    @php
                        $displayPrice = $bath->final_price ?? $bath->price_per_session ?? 1100.00;
                        $displayMaxGuests = $bath->max_guests ?? 7;
                        $displayOpening = optional($bath->opening_time) ? \Carbon\Carbon::parse($bath->opening_time)->format('h:i A') : '01:54 PM';
                        $displayContact = $bath->owner->phone ?? '77755723';
                    @endphp
                    <div class="h4">Nu. {{ number_format((float) $displayPrice, 2) }}</div>
                    <div class="small text-muted">10% commission will be added automatically.</div>
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-icon">👥</div>
                <div>
                    <div class="small text-muted">Maximum Guests</div>
                    <div>{{ $displayMaxGuests }}</div>
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-icon">🕒</div>
                <div>
                    <div class="small text-muted">Opening Time</div>
                    <div>{{ $displayOpening }}</div>
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-icon">📞</div>
                <div>
                    <div class="small text-muted">Contact</div>
                    <div>{{ $displayContact }}</div>
                </div>
            </div>

            <div class="mt-3">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.baths.edit', $bath) }}" class="btn-brown">Edit Bath</a>
                    <form action="{{ route('admin.baths.delete', $bath) }}" method="POST">@csrf
                        <button type="submit" class="btn-outline-danger" onclick="return confirm('Delete this bath?')">Delete Bath</button>
                    </form>
                </div>
            </div>

        </div>
    </aside>
</div>

@endsection
