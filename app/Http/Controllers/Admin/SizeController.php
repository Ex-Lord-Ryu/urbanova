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
        $sizes = Size::withCount('products')->latest()->paginate(10);
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

        // Handle slug
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

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
        $size->load('products');
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

        // Handle slug
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $size->update($data);

        return redirect()->route('sizes.index')->with('success', 'Ukuran berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Size $size)
    {
        // Check if size is being used by products
        if ($size->products()->count() > 0) {
            return redirect()->back()->with('error', 'Ukuran tidak dapat dihapus karena masih digunakan oleh produk.');
        }

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

        // Handle slug
        $data['slug'] = Str::slug($request->name);

        $size = Size::create($data);

        return response()->json([
            'success' => true,
            'size' => $size,
            'message' => 'Ukuran berhasil ditambahkan'
        ]);
    }
}
