<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Display the landing page with featured products.
     */
    public function index()
    {
        $featuredProducts = Product::where('is_active', true)
                                  ->where('is_featured', true)
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
        return view('landing.about');
    }

    /**
     * Display the contact page.
     */
    public function contact()
    {
        return view('landing.contact');
    }
}