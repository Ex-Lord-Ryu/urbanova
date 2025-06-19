<link rel="stylesheet" href="{{ asset('css/components/footer.css') }}">

<footer>
    <div class="footer-container">
        <div class="footer-section">
            <h4>{{ \App\Models\Setting::get('footer_about_title', 'About') }}</h4>
            <ul>
                @php
                    $aboutLinks = \App\Models\Setting::get('footer_about_links', []);
                    if (is_string($aboutLinks)) {
                        $aboutLinks = json_decode($aboutLinks, true) ?? [];
                    }
                @endphp
                @foreach($aboutLinks as $link)
                    <li><a href="{{ $link['url'] ?? '#' }}">{{ $link['text'] ?? '' }}</a></li>
                @endforeach
            </ul>
        </div>
        <div class="footer-section">
            <h4>{{ \App\Models\Setting::get('footer_customer_service_title', 'Customer Service') }}</h4>
            <ul>
                @php
                    $customerServiceLinks = \App\Models\Setting::get('footer_customer_service_links', []);
                    if (is_string($customerServiceLinks)) {
                        $customerServiceLinks = json_decode($customerServiceLinks, true) ?? [];
                    }
                @endphp
                @foreach($customerServiceLinks as $link)
                    <li><a href="{{ $link['url'] ?? '#' }}">{{ $link['text'] ?? '' }}</a></li>
                @endforeach
            </ul>
        </div>
        <div class="footer-section">
            <h4>{{ \App\Models\Setting::get('footer_social_title', 'Follow Us') }}</h4>
            <div class="socials">
                @php
                    $socialLinks = \App\Models\Setting::get('footer_social_links', []);
                    if (is_string($socialLinks)) {
                        $socialLinks = json_decode($socialLinks, true) ?? [];
                    }
                @endphp
                @foreach($socialLinks as $link)
                    <a href="{{ $link['url'] ?? '#' }}"><i class="{{ $link['icon'] ?? 'fab fa-link' }}"></i></a>
                @endforeach
            </div>
        </div>
        <div class="footer-section whatsapp-section">
            <h4>{{ \App\Models\Setting::get('footer_whatsapp_title', 'WhatsApp Customer Service') }}</h4>
            <p>{{ \App\Models\Setting::get('footer_whatsapp_description', 'Need help? Chat with our customer service team') }}</p>
            <div class="whatsapp-buttons">
                @php
                    $waContacts = \App\Models\Setting::get('footer_whatsapp_contacts', []);
                    if (is_string($waContacts)) {
                        $waContacts = json_decode($waContacts, true) ?? [];
                    }
                @endphp
                @foreach($waContacts as $contact)
                    @php
                        $waNumber = $contact['number'] ?? '';
                        $waMessage = $contact['message'] ?? 'Hi, I need assistance';
                        $waName = $contact['name'] ?? 'Customer Service';
                        $waUrl = 'https://wa.me/' . $waNumber . '?text=' . urlencode($waMessage);
                    @endphp
                    <a href="{{ $waUrl }}" class="whatsapp-button" target="_blank">
                        <i class="fab fa-whatsapp"></i> {{ $waName }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</footer>

<style>
.whatsapp-section {
    display: flex;
    flex-direction: column;
}

.whatsapp-buttons {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.whatsapp-button {
    display: inline-flex;
    align-items: center;
    background-color: #25D366;
    color: white;
    padding: 10px 15px;
    border-radius: 5px;
    text-decoration: none;
    font-weight: 500;
    transition: background-color 0.3s ease;
}

.whatsapp-button:hover {
    background-color: #128C7E;
    color: white;
}

.whatsapp-button i {
    margin-right: 8px;
    font-size: 18px;
}

@media (max-width: 768px) {
    .footer-container {
        flex-direction: column;
    }

    .footer-section {
        width: 100%;
        margin-bottom: 20px;
    }
}
</style>

