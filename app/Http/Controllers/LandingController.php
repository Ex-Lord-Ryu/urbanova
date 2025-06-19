<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LandingController extends Controller
{
    /**
     * Display the landing page with featured products.
     */
    public function index()
    {
        // Get featured products that have at least one variant in stock
        $featuredProducts = Product::where('is_active', true)
            ->where('is_featured', true)
            ->whereHas('variants', function ($query) {
                $query->where('stock', '>', 0);
            })
            ->with([
                'discounts',
                'sizes',
                'colors',
                'variants' => function ($query) {
                    $query->where('stock', '>', 0);
                },
                'variants.size',
                'variants.color'
            ])
            ->latest()
            ->take(4)
            ->get();

        return view('landing.landing_page', compact('featuredProducts'));
    }

    /**
     * Display the about page.
     */
    public function about()
    {
        $aboutTitle = \App\Models\Setting::get('about_title', 'Our Story');
        $aboutContent = \App\Models\Setting::get('about_content', '');

        return view('landing.about', compact('aboutTitle', 'aboutContent'));
    }

    /**
     * Display the contact page.
     */
    public function contact()
    {
        return view('landing.contact');
    }

    /**
     * Display the FAQ page.
     */
    public function faq()
    {
        $pageTitle = \App\Models\Setting::get('faq_title', 'Frequently Asked Questions');
        $pageContent = \App\Models\Setting::get('faq_content', '');

        return view('landing.page', [
            'pageTitle' => $pageTitle,
            'pageContent' => $pageContent,
            'pageType' => 'faq'
        ]);
    }

    /**
     * Display the Terms & Conditions page.
     */
    public function terms()
    {
        $pageTitle = \App\Models\Setting::get('terms_title', 'Terms & Conditions');
        $pageContent = \App\Models\Setting::get('terms_content', '');

        return view('landing.page', [
            'pageTitle' => $pageTitle,
            'pageContent' => $pageContent,
            'pageType' => 'terms'
        ]);
    }

    /**
     * Display the Privacy Policy page.
     */
    public function privacy()
    {
        $pageTitle = \App\Models\Setting::get('privacy_title', 'Privacy Policy');
        $pageContent = \App\Models\Setting::get('privacy_content', '');

        return view('landing.page', [
            'pageTitle' => $pageTitle,
            'pageContent' => $pageContent,
            'pageType' => 'privacy'
        ]);
    }

    /**
     * Display the Returns Policy page.
     */
    public function returns()
    {
        $pageTitle = \App\Models\Setting::get('returns_title', 'Returns Policy');
        $pageContent = \App\Models\Setting::get('returns_content', '');

        return view('landing.page', [
            'pageTitle' => $pageTitle,
            'pageContent' => $pageContent,
            'pageType' => 'returns'
        ]);
    }

    /**
     * Display the Shipping Information page.
     */
    public function shipping()
    {
        $pageTitle = \App\Models\Setting::get('shipping_title', 'Shipping Information');
        $pageContent = \App\Models\Setting::get('shipping_content', '');

        return view('landing.page', [
            'pageTitle' => $pageTitle,
            'pageContent' => $pageContent,
            'pageType' => 'shipping'
        ]);
    }

    /**
     * Display the user profile page on landing layout.
     */
    public function profile()
    {
        // Ensure user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        return view('user.profile.profile', compact('user'));
    }

    /**
     * Display the edit profile form on landing layout.
     */
    public function editProfile()
    {
        // Ensure user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        return view('user.profile.profile_edit', compact('user'));
    }

    /**
     * Update the user profile from landing layout.
     */
    public function updateProfile(Request $request)
    {
        // Ensure user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:15'],
            'gender' => ['nullable', 'in:male,female'],
            'birth_date' => ['nullable', 'date'],
        ]);

        $user = User::find(Auth::id());
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->gender = $request->input('gender');
        $user->birth_date = $request->input('birth_date');
        $user->save();

        return redirect()->route('landing.profile')->with('status', 'Profil Berhasil Di Update!');
    }

    /**
     * Display the user's orders.
     */
    public function orders()
    {
        // Ensure user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        // Use Order model directly to avoid IDE/linter confusion
        $orders = Order::where('user_id', $user->id)->with('items')->latest()->paginate(10);

        return view('user.orders.index', compact('user', 'orders'));
    }

    /**
     * Display a specific order.
     */
    public function orderDetail($orderNumber)
    {
        // Ensure user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', $user->id)
            ->with('items')
            ->firstOrFail();

        return view('user.orders.detail', compact('user', 'order'));
    }
}
