<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SettingsController extends Controller
{
    /**
     * Display a listing of the settings.
     */
    public function index()
    {
        $settings = Setting::all()->groupBy('group');
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Store and update settings.
     */
    public function update(Request $request)
    {
        foreach ($request->except('_token', '_method') as $key => $value) {
            $setting = Setting::where('key', $key)->first();

            if ($setting) {
                // Handle JSON values specially
                if ($setting->type === 'json' || $setting->type === 'array') {
                    // Ensure we're working with a valid JSON string
                    if (is_string($value) && !empty($value)) {
                        // The value is already a JSON string from the form
                        $setting->value = $value;
                    } else {
                        // Fall back to empty array if we received invalid data
                        $setting->value = json_encode([]);
                    }
                } else {
                    $setting->value = $value;
                }

                $setting->save();

                // Clear cache for this setting
                Cache::forget("setting.{$key}");
            }
        }

        // Clear all settings cache
        Setting::clearCache();

        return redirect()->back()->with('success', 'Pengaturan berhasil disimpan.');
    }

    /**
     * Toggle a boolean setting.
     */
    public function toggle(Request $request, $key)
    {
        try {
            // Find the setting or create it if it doesn't exist
            $setting = Setting::firstOrNew(['key' => $key]);

            if (!$setting->exists) {
                // Initialize new setting
                $setting->key = $key;
                $setting->group = 'display';
                $setting->type = 'boolean';
                $setting->label = ucwords(str_replace('_', ' ', $key));
                $setting->description = 'Auto-created setting';
            }

            // Get value from request (0 or 1)
            $newValue = $request->input('value') ? '1' : '0';

            // Simple direct update to the database
            $setting->value = $newValue;
            $setting->save();

            // Clear cache
            Cache::forget("setting.{$key}");

            return response()->json([
                'success' => true,
                'value' => (bool) $newValue,
                'message' => 'Pengaturan berhasil diperbarui'
            ]);

        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error("Error toggling setting {$key}: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the about page content
     */
    public function updateAboutPage(Request $request)
    {
        $request->validate([
            'about_title' => 'required|string|max:100',
            'about_content' => 'required|string',
        ]);

        // Update the settings
        Setting::set('about_title', $request->about_title, [
            'group' => 'content',
            'type' => 'text',
            'label' => 'Judul Halaman About',
            'description' => 'Judul utama pada halaman About Us'
        ]);

        // Save rich HTML content
        Setting::set('about_content', $request->about_content, [
            'group' => 'content',
            'type' => 'html',
            'label' => 'Konten Halaman About',
            'description' => 'Konten utama yang ditampilkan pada halaman About Us'
        ]);

        return redirect()->back()->with('success', 'Konten halaman About berhasil diperbarui.');
    }

    /**
     * Update content for other pages (FAQ, Terms, Privacy, Returns, Shipping)
     */
    public function updatePageContent(Request $request)
    {
        $request->validate([
            'page_type' => 'required|string|in:faq,terms,privacy,returns,shipping',
            'page_title' => 'required|string|max:100',
            'page_content' => 'required|string',
        ]);

        $pageType = $request->page_type;
        $titleKey = $pageType . '_title';
        $contentKey = $pageType . '_content';

        // Get page info based on type
        $pageInfo = $this->getPageInfo($pageType);

        // Update the settings
        Setting::set($titleKey, $request->page_title, [
            'group' => 'content',
            'type' => 'text',
            'label' => $pageInfo['title_label'],
            'description' => $pageInfo['title_desc']
        ]);

        // Save rich HTML content
        Setting::set($contentKey, $request->page_content, [
            'group' => 'content',
            'type' => 'html',
            'label' => $pageInfo['content_label'],
            'description' => $pageInfo['content_desc']
        ]);

        return redirect()->back()->with('success', 'Konten halaman ' . $pageInfo['page_name'] . ' berhasil diperbarui.');
    }

    /**
     * Get page information based on page type
     *
     * @param string $pageType
     * @return array
     */
    private function getPageInfo($pageType)
    {
        $pageInfo = [
            'faq' => [
                'page_name' => 'FAQ',
                'title_label' => 'Judul Halaman FAQ',
                'title_desc' => 'Judul utama pada halaman FAQ',
                'content_label' => 'Konten Halaman FAQ',
                'content_desc' => 'Konten utama yang ditampilkan pada halaman FAQ'
            ],
            'terms' => [
                'page_name' => 'Syarat & Ketentuan',
                'title_label' => 'Judul Halaman Syarat & Ketentuan',
                'title_desc' => 'Judul utama pada halaman Syarat & Ketentuan',
                'content_label' => 'Konten Halaman Syarat & Ketentuan',
                'content_desc' => 'Konten utama yang ditampilkan pada halaman Syarat & Ketentuan'
            ],
            'privacy' => [
                'page_name' => 'Kebijakan Privasi',
                'title_label' => 'Judul Halaman Kebijakan Privasi',
                'title_desc' => 'Judul utama pada halaman Kebijakan Privasi',
                'content_label' => 'Konten Halaman Kebijakan Privasi',
                'content_desc' => 'Konten utama yang ditampilkan pada halaman Kebijakan Privasi'
            ],
            'returns' => [
                'page_name' => 'Kebijakan Pengembalian',
                'title_label' => 'Judul Halaman Kebijakan Pengembalian',
                'title_desc' => 'Judul utama pada halaman Kebijakan Pengembalian',
                'content_label' => 'Konten Halaman Kebijakan Pengembalian',
                'content_desc' => 'Konten utama yang ditampilkan pada halaman Kebijakan Pengembalian'
            ],
            'shipping' => [
                'page_name' => 'Informasi Pengiriman',
                'title_label' => 'Judul Halaman Informasi Pengiriman',
                'title_desc' => 'Judul utama pada halaman Informasi Pengiriman',
                'content_label' => 'Konten Halaman Informasi Pengiriman',
                'content_desc' => 'Konten utama yang ditampilkan pada halaman Informasi Pengiriman'
            ]
        ];

        return $pageInfo[$pageType] ?? [];
    }

    /**
     * Clear all settings cache manually
     */
    public function clearCache()
    {
        Setting::clearCache();
        return redirect()->back()->with('success', 'Cache pengaturan berhasil dihapus.');
    }

    /**
     * Initialize default settings if they don't exist
     */
    public function initializeSettings()
    {
        try {
            $defaults = [
                [
                    'key' => 'show_featured_badge',
                    'value' => '1',
                    'group' => 'display',
                    'type' => 'boolean',
                    'label' => 'Tampilkan Badge Unggulan',
                    'description' => 'Tampilkan badge "Unggulan" pada produk unggulan di halaman shop',
                ],
                [
                    'key' => 'site_name',
                    'value' => 'Urbanova',
                    'group' => 'general',
                    'type' => 'text',
                    'label' => 'Nama Situs',
                    'description' => 'Nama situs web Anda',
                ],
                // About Page Content
                [
                    'key' => 'about_title',
                    'value' => 'Our Story',
                    'group' => 'content',
                    'type' => 'text',
                    'label' => 'Judul Halaman About',
                    'description' => 'Judul utama pada halaman About Us'
                ],
                [
                    'key' => 'about_content',
                    'value' => 'Founded in 2020, Urbanova was built with one mission: to redefine street fashion for the modern generation. We believe that style should be bold, expressive, and sustainable. That\'s why every piece we produce is crafted with care, creativity, and a commitment to the environment.

Our collections are inspired by the energy of the city — vibrant, dynamic, and constantly evolving. Whether you\'re exploring new streets or making your mark, Urbanova equips you with fashion that moves with you.

Join us as we continue to innovate, inspire, and ignite a new wave of urban fashion lovers worldwide.',
                    'group' => 'content',
                    'type' => 'html',
                    'label' => 'Konten Halaman About',
                    'description' => 'Konten utama yang ditampilkan pada halaman About Us'
                ],
                // FAQ Page
                [
                    'key' => 'faq_title',
                    'value' => 'Frequently Asked Questions',
                    'group' => 'content',
                    'type' => 'text',
                    'label' => 'Judul Halaman FAQ',
                    'description' => 'Judul utama pada halaman FAQ'
                ],
                [
                    'key' => 'faq_content',
                    'value' => '<div class="faq-list">
    <div class="faq-item">
        <h3 class="faq-question">How do I track my order?</h3>
        <div class="faq-answer">
            <p>Once your order has shipped, you will receive a shipping confirmation email with a tracking number. You can use this tracking number on our website or directly on the carrier\'s website to track your package.</p>
        </div>
    </div>

    <div class="faq-item">
        <h3 class="faq-question">What is your return policy?</h3>
        <div class="faq-answer">
            <p>We offer a 30-day return policy for unworn items in their original condition with tags attached. Please visit our Returns page for more detailed information.</p>
        </div>
    </div>

    <div class="faq-item">
        <h3 class="faq-question">How do I know what size to order?</h3>
        <div class="faq-answer">
            <p>Each product page includes a size guide specific to that item. We recommend measuring yourself and comparing your measurements to our size chart to find the best fit.</p>
        </div>
    </div>

    <div class="faq-item">
        <h3 class="faq-question">Do you ship internationally?</h3>
        <div class="faq-answer">
            <p>Yes, we ship to most countries worldwide. Shipping costs and delivery times vary depending on your location.</p>
        </div>
    </div>
</div>',
                    'group' => 'content',
                    'type' => 'html',
                    'label' => 'Konten Halaman FAQ',
                    'description' => 'Konten utama yang ditampilkan pada halaman FAQ'
                ],
                // Terms & Conditions Page
                [
                    'key' => 'terms_title',
                    'value' => 'Terms & Conditions',
                    'group' => 'content',
                    'type' => 'text',
                    'label' => 'Judul Halaman Syarat & Ketentuan',
                    'description' => 'Judul utama pada halaman Syarat & Ketentuan'
                ],
                [
                    'key' => 'terms_content',
                    'value' => '<h2>1. Introduction</h2>
<p>Welcome to Urbanova. These terms and conditions govern your use of our website and the purchase of products from our online store.</p>

<h2>2. Acceptance of Terms</h2>
<p>By accessing and using our website, you agree to be bound by these Terms and Conditions. If you do not agree with any part of these terms, please do not use our website or services.</p>

<h2>3. Changes to Terms</h2>
<p>We reserve the right to modify these terms at any time. Your continued use of the website following any changes constitutes your acceptance of the new Terms and Conditions.</p>

<h2>4. Privacy Policy</h2>
<p>Your use of our website is also governed by our Privacy Policy, which is incorporated into these terms by reference.</p>',
                    'group' => 'content',
                    'type' => 'html',
                    'label' => 'Konten Halaman Syarat & Ketentuan',
                    'description' => 'Konten utama yang ditampilkan pada halaman Syarat & Ketentuan'
                ],
                // Privacy Policy Page
                [
                    'key' => 'privacy_title',
                    'value' => 'Privacy Policy',
                    'group' => 'content',
                    'type' => 'text',
                    'label' => 'Judul Halaman Kebijakan Privasi',
                    'description' => 'Judul utama pada halaman Kebijakan Privasi'
                ],
                [
                    'key' => 'privacy_content',
                    'value' => '<h2>Privacy Policy</h2>
<p>This Privacy Policy describes how your personal information is collected, used, and shared when you visit or make a purchase from our website.</p>

<h2>Personal Information We Collect</h2>
<p>When you visit the Site, we automatically collect certain information about your device, including information about your web browser, IP address, time zone, and some of the cookies that are installed on your device.</p>

<h2>How We Use Your Personal Information</h2>
<p>We use the information that we collect to fulfill any orders placed through the Site (including processing your payment information, arranging for shipping, and providing you with invoices and/or order confirmations).</p>

<h2>Sharing Your Personal Information</h2>
<p>We share your Personal Information with third parties to help us use your Personal Information, as described above.</p>',
                    'group' => 'content',
                    'type' => 'html',
                    'label' => 'Konten Halaman Kebijakan Privasi',
                    'description' => 'Konten utama yang ditampilkan pada halaman Kebijakan Privasi'
                ],
                // Returns Policy Page
                [
                    'key' => 'returns_title',
                    'value' => 'Returns Policy',
                    'group' => 'content',
                    'type' => 'text',
                    'label' => 'Judul Halaman Kebijakan Pengembalian',
                    'description' => 'Judul utama pada halaman Kebijakan Pengembalian'
                ],
                [
                    'key' => 'returns_content',
                    'value' => '<h2>Returns Policy</h2>
<p>We want you to be completely satisfied with your purchase. If for any reason you are not, we offer a simple returns process.</p>

<h2>Return Eligibility</h2>
<p>To be eligible for a return, your item must be unused and in the same condition that you received it. It must also be in the original packaging with all tags attached.</p>

<h2>Return Window</h2>
<p>You have 30 days from receipt of your order to initiate a return. Items received after the 30-day window may not be accepted for return.</p>

<h2>Return Process</h2>
<ol>
    <li>Log into your account and go to your order history</li>
    <li>Select the order containing the item(s) you wish to return</li>
    <li>Follow the prompts to complete the return request form</li>
    <li>Print the return shipping label</li>
    <li>Package your return securely and attach the shipping label</li>
    <li>Drop off your package at the carrier location</li>
</ol>',
                    'group' => 'content',
                    'type' => 'html',
                    'label' => 'Konten Halaman Kebijakan Pengembalian',
                    'description' => 'Konten utama yang ditampilkan pada halaman Kebijakan Pengembalian'
                ],
                // Shipping Information Page
                [
                    'key' => 'shipping_title',
                    'value' => 'Shipping Information',
                    'group' => 'content',
                    'type' => 'text',
                    'label' => 'Judul Halaman Informasi Pengiriman',
                    'description' => 'Judul utama pada halaman Informasi Pengiriman'
                ],
                [
                    'key' => 'shipping_content',
                    'value' => '<h2>Shipping Information</h2>
<p>We ship all orders within 1-2 business days of receiving them.</p>

<h2>Shipping Methods</h2>
<table>
    <tr>
        <th>Shipping Method</th>
        <th>Estimated Delivery Time</th>
        <th>Cost</th>
    </tr>
    <tr>
        <td>Standard Shipping</td>
        <td>5-7 business days</td>
        <td>Free for orders over Rp 500.000</td>
    </tr>
    <tr>
        <td>Express Shipping</td>
        <td>2-3 business days</td>
        <td>Rp 50.000</td>
    </tr>
    <tr>
        <td>Next Day Delivery</td>
        <td>1 business day</td>
        <td>Rp 100.000</td>
    </tr>
</table>

<h2>International Shipping</h2>
<p>We ship to most countries worldwide. International shipping costs and delivery times vary depending on the destination.</p>

<h2>Tracking Your Order</h2>
<p>Once your order has shipped, you will receive a shipping confirmation email with tracking information. You can track your package by clicking the tracking link in the email or by logging into your account on our website.</p>',
                    'group' => 'content',
                    'type' => 'html',
                    'label' => 'Konten Halaman Informasi Pengiriman',
                    'description' => 'Konten utama yang ditampilkan pada halaman Informasi Pengiriman'
                ],
                // Footer settings
                [
                    'key' => 'footer_about_title',
                    'value' => 'About',
                    'group' => 'footer',
                    'type' => 'text',
                    'label' => 'Judul Bagian About',
                    'description' => 'Judul untuk bagian About di footer',
                ],
                [
                    'key' => 'footer_about_links',
                    'value' => json_encode([
                        ['text' => 'Our Story', 'url' => '#'],
                        ['text' => 'Careers', 'url' => '#'],
                        ['text' => 'Privacy Policy', 'url' => '#'],
                    ]),
                    'group' => 'footer',
                    'type' => 'json',
                    'label' => 'Link Bagian About',
                    'description' => 'Link untuk bagian About di footer (format JSON)',
                ],
                [
                    'key' => 'footer_customer_service_title',
                    'value' => 'Customer Service',
                    'group' => 'footer',
                    'type' => 'text',
                    'label' => 'Judul Layanan Pelanggan',
                    'description' => 'Judul untuk bagian Layanan Pelanggan di footer',
                ],
                [
                    'key' => 'footer_customer_service_links',
                    'value' => json_encode([
                        ['text' => 'Help Center', 'url' => '#'],
                        ['text' => 'Returns', 'url' => '#'],
                        ['text' => 'Shipping Info', 'url' => '#'],
                    ]),
                    'group' => 'footer',
                    'type' => 'json',
                    'label' => 'Link Layanan Pelanggan',
                    'description' => 'Link untuk bagian Layanan Pelanggan di footer (format JSON)',
                ],
                [
                    'key' => 'footer_social_title',
                    'value' => 'Follow Us',
                    'group' => 'footer',
                    'type' => 'text',
                    'label' => 'Judul Media Sosial',
                    'description' => 'Judul untuk bagian Media Sosial di footer',
                ],
                [
                    'key' => 'footer_social_links',
                    'value' => json_encode([
                        ['icon' => 'fab fa-facebook-f', 'url' => '#'],
                        ['icon' => 'fab fa-twitter', 'url' => '#'],
                        ['icon' => 'fab fa-instagram', 'url' => '#'],
                    ]),
                    'group' => 'footer',
                    'type' => 'json',
                    'label' => 'Link Media Sosial',
                    'description' => 'Link media sosial untuk footer (format JSON dengan kelas icon)',
                ],
                // WhatsApp Customer Service
                [
                    'key' => 'footer_whatsapp_title',
                    'value' => 'WhatsApp Customer Service',
                    'group' => 'footer',
                    'type' => 'text',
                    'label' => 'Judul WhatsApp CS',
                    'description' => 'Judul untuk bagian Customer Service WhatsApp',
                ],
                [
                    'key' => 'footer_whatsapp_description',
                    'value' => 'Butuh bantuan? Chat dengan tim customer service kami',
                    'group' => 'footer',
                    'type' => 'text',
                    'label' => 'Deskripsi WhatsApp',
                    'description' => 'Deskripsi singkat untuk layanan WhatsApp',
                ],
                [
                    'key' => 'footer_whatsapp_contacts',
                    'value' => json_encode([
                        [
                            'name' => 'Layanan Umum',
                            'number' => '628123456789',
                            'message' => 'Halo, saya butuh bantuan terkait pesanan',
                        ],
                        [
                            'name' => 'Layanan Teknis',
                            'number' => '628987654321',
                            'message' => 'Halo, saya membutuhkan bantuan teknis',
                        ],
                    ]),
                    'group' => 'footer',
                    'type' => 'json',
                    'label' => 'Kontak WhatsApp',
                    'description' => 'Daftar kontak WhatsApp untuk layanan pelanggan',
                ],
            ];

            $created = 0;
            foreach ($defaults as $default) {
                if (!Setting::where('key', $default['key'])->exists()) {
                    Setting::create($default);
                    $created++;
                }
            }

            // Clear all settings cache
            Setting::clearCache();

            return redirect()->route('settings.index')
                ->with('success', "Berhasil menginisialisasi {$created} pengaturan default.");

        } catch (\Exception $e) {
            Log::error('Error initializing settings: ' . $e->getMessage());
            return redirect()->route('settings.index')
                ->with('error', 'Gagal menginisialisasi pengaturan: ' . $e->getMessage());
        }
    }
}
