@extends('layouts.landing')

@section('title', 'Urbanova - About Us')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/landing/about.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
@endpush

@section('content')
    <section class="about-content">
        <div class="about-text">
            <h2>Our Story</h2>
            <p>Founded in 2020, Urbanova was built with one mission: to redefine street fashion for the modern generation. We believe that style should be bold, expressive, and sustainable. That's why every piece we produce is crafted with care, creativity, and a commitment to the environment.</p>

            <p>Our collections are inspired by the energy of the city — vibrant, dynamic, and constantly evolving. Whether you're exploring new streets or making your mark, Urbanova equips you with fashion that moves with you.</p>

            <p>Join us as we continue to innovate, inspire, and ignite a new wave of urban fashion lovers worldwide.</p>
        </div>
    </section>
@endsection
