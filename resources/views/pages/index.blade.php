@extends('layouts.master')

@section('title', 'Home')

@section('page-title', 'Home')

@section('content')

    @include('partials.hero')

    @include('partials.events')
    @include('partials.artists')
    @include('partials.about')
    @include('partials.faq')
    @include('partials.contactus')




@endsection
