<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $colors = Color::latest()->paginate(10);
        return view('admin.colors.index', compact('colors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.colors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:colors',
            'slug' => 'nullable|string|max:255|unique:colors',
            'hex_code' => 'nullable|string|max:6|regex:/^[0-9A-F]{6}$/i',
            'is_active' => 'required|boolean',
        ]);

        $data = $request->all();

        // Convert name to uppercase
        $data['name'] = strtoupper($data['name']);

        // Handle slug - if empty, it will be automatically generated in the model
        // If provided, it will be converted to lowercase in the model

        // Format hex_code (ensure no # is included)
        if (isset($data['hex_code']) && !empty($data['hex_code'])) {
            $data['hex_code'] = ltrim($data['hex_code'], '#');
        }

        Color::create($data);

        return redirect()->route('colors.index')->with('success', 'Warna berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Color $color)
    {
        return view('admin.colors.edit', compact('color'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Color $color)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:colors,name,' . $color->id,
            'slug' => 'nullable|string|max:255|unique:colors,slug,' . $color->id,
            'hex_code' => 'nullable|string|max:6|regex:/^[0-9A-F]{6}$/i',
            'is_active' => 'required|boolean',
        ]);

        $data = $request->all();

        // Convert name to uppercase
        $data['name'] = strtoupper($data['name']);

        // Handle slug - if empty, it will be automatically generated in the model
        // If provided, it will be converted to lowercase in the model

        // Format hex_code (ensure no # is included)
        if (isset($data['hex_code']) && !empty($data['hex_code'])) {
            $data['hex_code'] = ltrim($data['hex_code'], '#');
        }

        $color->update($data);

        return redirect()->route('colors.index')->with('success', 'Warna berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Color $color)
    {
        $color->delete();
        return redirect()->route('colors.index')->with('success', 'Warna berhasil dihapus');
    }

    /**
     * Store a color via AJAX request
     */
    public function ajaxStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:colors',
            'hex_code' => 'nullable|string|max:6',
            'is_active' => 'required|boolean',
        ]);

        $data = $request->all();

        // Convert name to uppercase
        $data['name'] = strtoupper($data['name']);

        // Slug will be automatically generated in the model

        // Format hex_code (ensure no # is included)
        if (isset($data['hex_code']) && !empty($data['hex_code'])) {
            $data['hex_code'] = ltrim($data['hex_code'], '#');
        }

        $color = Color::create($data);

        return response()->json([
            'success' => true,
            'color' => $color,
            'message' => 'Warna berhasil ditambahkan'
        ]);
    }
}
