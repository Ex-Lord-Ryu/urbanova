<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SizeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sizes = Size::latest()->paginate(10);
        return view('admin.sizes.index', compact('sizes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.sizes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:sizes',
            'slug' => 'nullable|string|max:255|unique:sizes',
            'is_active' => 'required|boolean',
        ]);

        $data = $request->all();

        // Convert name to uppercase
        $data['name'] = strtoupper($data['name']);

        // Handle slug - if empty, it will be automatically generated in the model
        // If provided, it will be converted to lowercase in the model

        Size::create($data);

        return redirect()->route('sizes.index')->with('success', 'Ukuran berhasil ditambahkan');
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
    public function edit(Size $size)
    {
        return view('admin.sizes.edit', compact('size'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Size $size)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:sizes,name,' . $size->id,
            'slug' => 'nullable|string|max:255|unique:sizes,slug,' . $size->id,
            'is_active' => 'required|boolean',
        ]);

        $data = $request->all();

        // Convert name to uppercase
        $data['name'] = strtoupper($data['name']);

        // Handle slug - if empty, it will be automatically generated in the model
        // If provided, it will be converted to lowercase in the model

        $size->update($data);

        return redirect()->route('sizes.index')->with('success', 'Ukuran berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Size $size)
    {
        $size->delete();
        return redirect()->route('sizes.index')->with('success', 'Ukuran berhasil dihapus');
    }

    /**
     * Store a size via AJAX request
     */
    public function ajaxStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:sizes',
            'is_active' => 'required|boolean',
        ]);

        $data = $request->all();

        // Convert name to uppercase
        $data['name'] = strtoupper($data['name']);

        // Slug will be automatically generated in the model

        $size = Size::create($data);

        return response()->json([
            'success' => true,
            'size' => $size,
            'message' => 'Ukuran berhasil ditambahkan'
        ]);
    }
}
