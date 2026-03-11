@extends('layouts.master')

@section('title', 'Login & Register - 3Sixtyshows')
@section('meta_description', 'Login or create your 3Sixtyshows account to book Bollywood events in Texas.')

@section('content')

<div class="login-page">
    <div class="login-container">

        {{-- ══════════════════════════════
             FLASH ALERTS
             ══════════════════════════════ --}}
        @if (session('success'))
        <div class="lp-alert lp-alert--success">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
            <button class="lp-alert__close" onclick="this.parentElement.remove()" aria-label="Close">&times;</button>
        </div>
        @endif

        @if (session('booking_message'))
        <div class="lp-alert lp-alert--info">
            <i class="fas fa-ticket-alt"></i>
            <span>{{ session('booking_message') }}</span>
            <button class="lp-alert__close" onclick="this.parentElement.remove()" aria-label="Close">&times;</button>
        </div>
        @endif

        @if (session('info'))
        <div class="lp-alert lp-alert--info">
            <i class="fas fa-info-circle"></i>
            <span>{{ session('info') }}</span>
            <button class="lp-alert__close" onclick="this.parentElement.remove()" aria-label="Close">&times;</button>
        </div>
        @endif

        @if ($errors->has('error'))
        <div class="lp-alert lp-alert--danger">
            <i class="fas fa-exclamation-triangle"></i>
            <span>{{ $errors->first('error') }}</span>
            <button class="lp-alert__close" onclick="this.parentElement.remove()" aria-label="Close">&times;</button>
        </div>
        @endif

        {{-- ══════════════════════════════
             LOGIN CARD
             ══════════════════════════════ --}}
        <div class="lp-card" id="loginSection">
            <div class="lp-card__header lp-card__header--blue">
                <i class="fas fa-sign-in-alt"></i>
                <h2>Login Here!</h2>
            </div>
            <div class="lp-card__body">

                @if ($errors->has('login_error'))
                <div class="lp-alert lp-alert--danger lp-alert--inline">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ $errors->first('login_error') }}</span>
                </div>
                @endif

                <form action="{{ route('logincheck') }}" method="POST" id="loginForm">
                    @csrf

                    {{-- Email --}}
                    <div class="lp-field">
                        <label for="emaillogin" class="lp-label">Email Address</label>
                        <div class="lp-input-group">
                            <span class="lp-input-icon"><i class="fas fa-envelope"></i></span>
                            <input type="email"
                                   name="emaillogin"
                                   id="emaillogin"
                                   class="lp-input{{ $errors->has('emaillogin') ? ' lp-input--error' : '' }}"
                                   placeholder="Enter your email"
                                   value="{{ old('emaillogin') }}"
                                   autocomplete="email"
                                   required>
                        </div>
                        @error('emaillogin')
                        <p class="lp-field__error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="lp-field">
                        <label for="passwordlogin" class="lp-label">Password</label>
                        <div class="lp-input-group">
                            <span class="lp-input-icon"><i class="fas fa-lock"></i></span>
                            <input type="password"
                                   name="passwordlogin"
                                   id="passwordlogin"
                                   class="lp-input{{ $errors->has('passwordlogin') ? ' lp-input--error' : '' }}"
                                   placeholder="Enter your password"
                                   autocomplete="current-password"
                                   required>
                            <button type="button" class="lp-input-toggle" id="togglePassword" aria-label="Show/hide password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('passwordlogin')
                        <p class="lp-field__error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="lp-btn lp-btn--blue" id="loginBtn">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </button>

                </form>

                <p class="lp-switch-text">
                    Don't have an account?
                    <a href="#signupSection" class="lp-switch-link">Sign up below</a>
                </p>

            </div>
        </div>

        {{-- ══════════════════════════════
             SIGNUP CARD
             ══════════════════════════════ --}}
        <div class="lp-card lp-card--mt" id="signupSection">
            <div class="lp-card__header lp-card__header--dark">
                <i class="fas fa-user-plus"></i>
                <h2>Sign Up Here!</h2>
            </div>
            <div class="lp-card__body">

                @if ($errors->has('first_name') || $errors->has('last_name') || $errors->has('email') || $errors->has('password'))
                <div class="lp-alert lp-alert--danger lp-alert--inline">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div>
                        <strong>Please fix the following errors:</strong>
                        <ul class="lp-error-list">
                            @error('first_name')<li>{{ $message }}</li>@enderror
                            @error('last_name')<li>{{ $message }}</li>@enderror
                            @error('email')<li>{{ $message }}</li>@enderror
                            @error('password')<li>{{ $message }}</li>@enderror
                        </ul>
                    </div>
                </div>
                @endif

                <form action="{{ route('user_store') }}" method="POST" id="signupFormElement">
                    @csrf

                    {{-- First + Last Name --}}
                    <div class="lp-row">
                        <div class="lp-field">
                            <label for="firstName" class="lp-label">First Name</label>
                            <input type="text"
                                   class="lp-input lp-input--full{{ $errors->has('first_name') ? ' lp-input--error' : '' }}"
                                   id="firstName"
                                   name="first_name"
                                   placeholder="First name"
                                   value="{{ old('first_name') }}"
                                   autocomplete="given-name"
                                   required>
                            @error('first_name')
                            <p class="lp-field__error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                            @enderror
                        </div>
                        <div class="lp-field">
                            <label for="lastName" class="lp-label">Last Name</label>
                            <input type="text"
                                   class="lp-input lp-input--full{{ $errors->has('last_name') ? ' lp-input--error' : '' }}"
                                   id="lastName"
                                   name="last_name"
                                   placeholder="Last name"
                                   value="{{ old('last_name') }}"
                                   autocomplete="family-name"
                                   required>
                            @error('last_name')
                            <p class="lp-field__error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="lp-field">
                        <label for="email" class="lp-label">Email Address</label>
                        <div class="lp-input-group">
                            <span class="lp-input-icon"><i class="fas fa-envelope"></i></span>
                            <input type="email"
                                   class="lp-input{{ $errors->has('email') ? ' lp-input--error' : '' }}"
                                   id="email"
                                   name="email"
                                   placeholder="Enter your email"
                                   value="{{ old('email') }}"
                                   autocomplete="email"
                                   required>
                        </div>
                        @error('email')
                        <p class="lp-field__error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="lp-field">
                        <label for="password" class="lp-label">Password</label>
                        <div class="lp-input-group">
                            <span class="lp-input-icon"><i class="fas fa-lock"></i></span>
                            <input type="password"
                                   class="lp-input{{ $errors->has('password') ? ' lp-input--error' : '' }}"
                                   id="password"
                                   name="password"
                                   placeholder="Minimum 6 characters"
                                   autocomplete="new-password"
                                   required>
                            <button type="button" class="lp-input-toggle" id="toggleSignupPassword" aria-label="Show/hide password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <p class="lp-field__hint">Password must be at least 6 characters long.</p>
                        @error('password')
                        <p class="lp-field__error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="lp-btn lp-btn--dark" id="signupBtn">
                        <i class="fas fa-user-plus"></i> Sign Up
                    </button>

                </form>

                <p class="lp-switch-text">
                    Already have an account?
                    <a href="#loginSection" class="lp-switch-link scroll-to-login">Login above</a>
                </p>

            </div>
        </div>

    </div>{{-- /login-container --}}
</div>{{-- /login-page --}}


<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Toggle password: Login ───────────────────────────
    const togglePwd   = document.getElementById('togglePassword');
    const pwdLogin    = document.getElementById('passwordlogin');
    if (togglePwd && pwdLogin) {
        togglePwd.addEventListener('click', function () {
            const show = pwdLogin.type === 'password';
            pwdLogin.type = show ? 'text' : 'password';
            this.querySelector('i').classList.toggle('fa-eye',      !show);
            this.querySelector('i').classList.toggle('fa-eye-slash', show);
        });
    }

    // ── Toggle password: Signup ──────────────────────────
    const toggleSPwd  = document.getElementById('toggleSignupPassword');
    const pwdSignup   = document.getElementById('password');
    if (toggleSPwd && pwdSignup) {
        toggleSPwd.addEventListener('click', function () {
            const show = pwdSignup.type === 'password';
            pwdSignup.type = show ? 'text' : 'password';
            this.querySelector('i').classList.toggle('fa-eye',      !show);
            this.querySelector('i').classList.toggle('fa-eye-slash', show);
        });
    }

    // ── Login form: loading state ────────────────────────
    const loginForm = document.getElementById('loginForm');
    const loginBtn  = document.getElementById('loginBtn');
    if (loginForm && loginBtn) {
        loginForm.addEventListener('submit', function () {
            loginBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Logging in...';
            loginBtn.disabled  = true;
        });
    }

    // ── Signup form: loading state ───────────────────────
    const signupForm = document.getElementById('signupFormElement');
    const signupBtn  = document.getElementById('signupBtn');
    if (signupForm && signupBtn) {
        signupForm.addEventListener('submit', function () {
            signupBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating Account...';
            signupBtn.disabled  = true;
        });
    }

    // ── Auto-dismiss alerts after 5s ─────────────────────
    document.querySelectorAll('.lp-alert').forEach(function (el) {
        setTimeout(function () { el.remove(); }, 5000);
    });

    // ── Smooth scroll: "Login above" ─────────────────────
    document.querySelectorAll('.scroll-to-login').forEach(function (link) {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.getElementById('loginSection');
            if (target) target.scrollIntoView({ behavior: 'smooth', block: 'center' });
        });
    });

    // ── Auto-scroll to signup on registration errors ─────
    @if ($errors->has('first_name') || $errors->has('last_name') || $errors->has('email') || $errors->has('password'))
    setTimeout(function () {
        const sec = document.getElementById('signupSection');
        if (sec) sec.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }, 120);
    @endif

});
</script>

@endsection
