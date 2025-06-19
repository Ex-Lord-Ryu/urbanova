@extends('layouts.landing')

@section('title', 'Urbanova - Contact Us')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/landing/contact.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
@endpush

@section('content')
    <section class="contact-content">
        <div class="contact-info">
            <h2>Get In Touch</h2>
            <p>We'd love to hear from you! Whether you have a question about our products, need assistance with an order, or want to collaborate with us, we're here to help.</p>

            <div class="contact-details">
                <div class="contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <h4>Address</h4>
                        <p>123 Urban Street, Fashion District<br>New York, NY 10001</p>
                    </div>
                </div>

                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <div>
                        <h4>Phone</h4>
                        <p>+1 (555) 123-4567</p>
                    </div>
                </div>

                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <h4>Email</h4>
                        <p>info@urbanova.com</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="contact-form">
            <h3>Send Us a Message</h3>
            <form action="#" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" id="subject" name="subject" required>
                </div>

                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="5" required></textarea>
                </div>

                <button type="submit">Send Message</button>
            </form>
        </div>
    </section>
    @include('components.footer')
@endsection
