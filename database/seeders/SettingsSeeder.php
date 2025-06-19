<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'show_featured_badge',
                'value' => '1',
                'group' => 'shop',
                'type' => 'boolean',
                'label' => 'Show Featured Badge',
                'description' => 'Show or hide the "Unggulan" badge on featured products in the shop page',
            ],
            [
                'key' => 'site_name',
                'value' => 'Urbanova',
                'group' => 'general',
                'type' => 'text',
                'label' => 'Site Name',
                'description' => 'The name of your site',
            ],
            // Footer settings
            [
                'key' => 'footer_about_title',
                'value' => 'About',
                'group' => 'footer',
                'type' => 'text',
                'label' => 'Footer About Title',
                'description' => 'Title for the About section in footer',
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
                'label' => 'Footer About Links',
                'description' => 'Links for the About section in footer (JSON format)',
            ],
            [
                'key' => 'footer_customer_service_title',
                'value' => 'Customer Service',
                'group' => 'footer',
                'type' => 'text',
                'label' => 'Footer Customer Service Title',
                'description' => 'Title for the Customer Service section in footer',
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
                'label' => 'Footer Customer Service Links',
                'description' => 'Links for the Customer Service section in footer (JSON format)',
            ],
            [
                'key' => 'footer_social_title',
                'value' => 'Follow Us',
                'group' => 'footer',
                'type' => 'text',
                'label' => 'Footer Social Media Title',
                'description' => 'Title for the Social Media section in footer',
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
                'label' => 'Footer Social Links',
                'description' => 'Social media links for the footer (JSON format with icon classes)',
            ],
            // WhatsApp Customer Service
            [
                'key' => 'footer_whatsapp_title',
                'value' => 'WhatsApp Customer Service',
                'group' => 'footer',
                'type' => 'text',
                'label' => 'WhatsApp CS Title',
                'description' => 'Title for the WhatsApp Customer Service section',
            ],
            [
                'key' => 'footer_whatsapp_description',
                'value' => 'Need help? Chat with our customer service team',
                'group' => 'footer',
                'type' => 'text',
                'label' => 'WhatsApp Description',
                'description' => 'Short description for the WhatsApp service',
            ],
            [
                'key' => 'footer_whatsapp_contacts',
                'value' => json_encode([
                    [
                        'name' => 'General Support',
                        'number' => '628123456789',
                        'message' => 'Hi, I need assistance with my order',
                    ],
                    [
                        'name' => 'Technical Support',
                        'number' => '628987654321',
                        'message' => 'Hi, I need technical assistance',
                    ],
                ]),
                'group' => 'footer',
                'type' => 'json',
                'label' => 'WhatsApp Contacts',
                'description' => 'Multiple WhatsApp contacts for customer service',
            ],
            // Shipping Settings
            [
                'key' => 'available_couriers',
                'value' => json_encode([
                    [
                        'name' => 'JNE',
                        'code' => 'jne',
                        'active' => true,
                    ],
                    [
                        'name' => 'J&T Express',
                        'code' => 'jnt',
                        'active' => true,
                    ],
                    [
                        'name' => 'SiCepat',
                        'code' => 'sicepat',
                        'active' => true,
                    ],
                    [
                        'name' => 'Pos Indonesia',
                        'code' => 'pos',
                        'active' => true,
                    ],
                    [
                        'name' => 'Anteraja',
                        'code' => 'anteraja',
                        'active' => false,
                    ],
                ]),
                'group' => 'shipping',
                'type' => 'json',
                'label' => 'Kurir yang Tersedia',
                'description' => 'Daftar kurir yang tersedia untuk pengiriman',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
