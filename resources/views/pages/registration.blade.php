@extends('layouts.master')

@section('title', 'Free Ticket Registration — Bismil ki Mehfil Houston | 3Sixtyshows')
@section('meta_description', 'Register now to claim your free tickets for Bismil ki Mehfil Houston. First 250 sign-ups receive complimentary tickets. Only on 3Sixtyshows.')

@section('og_title',       'Free Ticket Registration — Bismil ki Mehfil Houston | 3Sixtyshows')
@section('og_description', 'Register now for a chance to claim FREE tickets to Bismil ki Mehfil Houston. Limited to first 250 sign-ups!')

@section('twitter_title',       'Free Ticket Registration — Bismil ki Mehfil Houston | 3Sixtyshows')
@section('twitter_description', 'Register now for a chance to claim FREE tickets to Bismil ki Mehfil Houston. Limited to first 250 sign-ups!')

@push('styles')
<style>
    /* Dark background for full registration page */
    body {
        background: #0d1535 !important;
    }
    /* Ensure no white gap between header and banner */
    .reg-top-banner {
        margin-top: 0;
    }
</style>
@endpush

@section('content')

    @include('partials.registration')

@endsection
