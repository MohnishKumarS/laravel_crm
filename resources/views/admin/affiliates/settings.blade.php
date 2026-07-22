@extends('admin.layouts.main')

@section('title', 'Affiliate Settings | Yuukke Dashboard')

@section('content')
    <div class="page-header">
        <h3 class="fw-bold mb-3">Affiliate Settings</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home">
                <a href="{{ route('dashboard') }}">
                    <i class="icon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="{{ route('affiliates.index') }}">Affiliate Program</a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#">Settings</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Program Settings</div>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <h5 class="alert alert-success">{{ session('success') }}</h5>
                    @endif

                    <form action="{{ route('affiliates.settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="default_commission_rate">Default Commission Rate (%)</label>
                            <input type="number" step="0.01" name="default_commission_rate" class="form-control"
                                id="default_commission_rate"
                                value="{{ old('default_commission_rate', $values['default_commission_rate'] ?? 10) }}" required />
                            <small class="text-muted">Flat percentage of order total. Applied to new affiliates on approval; can be overridden per-affiliate.</small>
                            @error('default_commission_rate')
                                <span class="text-danger d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="cookie_duration_days">Cookie / Attribution Window (days)</label>
                            <input type="number" name="cookie_duration_days" class="form-control"
                                id="cookie_duration_days"
                                value="{{ old('cookie_duration_days', $values['cookie_duration_days'] ?? 30) }}" required />
                            <small class="text-muted">How long a referral link click stays credited to an affiliate if no order is placed yet.</small>
                            @error('cookie_duration_days')
                                <span class="text-danger d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="refund_hold_days">Refund Hold Period (days)</label>
                            <input type="number" name="refund_hold_days" class="form-control"
                                id="refund_hold_days"
                                value="{{ old('refund_hold_days', $values['refund_hold_days'] ?? 14) }}" required />
                            <small class="text-muted">Commissions stay "pending" for this many days after the order is paid, in case of a refund, before becoming payable.</small>
                            @error('refund_hold_days')
                                <span class="text-danger d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="min_payout_amount">Minimum Payout Amount</label>
                            <input type="number" step="0.01" name="min_payout_amount" class="form-control"
                                id="min_payout_amount"
                                value="{{ old('min_payout_amount', $values['min_payout_amount'] ?? 50) }}" required />
                            @error('min_payout_amount')
                                <span class="text-danger d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <hr class="mt-4 mb-3">

                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="auto_approve_affiliates"
                                    name="auto_approve_affiliates" value="1"
                                    {{ old('auto_approve_affiliates', $values['auto_approve_affiliates'] ?? false) ? 'checked' : '' }} />
                                <label class="form-check-label" for="auto_approve_affiliates">
                                    Auto-approve new affiliate signups
                                </label>
                            </div>
                            <small class="text-muted">If off, every application sits as "pending" until an admin approves it here.</small>
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="self_referral_block"
                                    name="self_referral_block" value="1"
                                    {{ old('self_referral_block', $values['self_referral_block'] ?? true) ? 'checked' : '' }} />
                                <label class="form-check-label" for="self_referral_block">
                                    Block self-referrals
                                </label>
                            </div>
                            <small class="text-muted">Prevents an affiliate from earning commission on their own account's orders.</small>
                        </div>

                        <div class="card-action mt-3">
                            <button type="submit" class="btn btn-success">Save Settings</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
