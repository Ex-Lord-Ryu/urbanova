<?php

namespace App\Http\Controllers;

use App\Models\DescriptionTemplate;
use App\Models\Product;
use Illuminate\Http\Request;

class DescriptionTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $templates = DescriptionTemplate::latest()->paginate(10);
        $categories = DescriptionTemplate::getCategories();
        return view('admin.description_templates.index', compact('templates', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = DescriptionTemplate::getCategories();
        return view('admin.description_templates.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'content' => 'required|string',
            'is_active' => 'nullable|boolean',
        ]);

        DescriptionTemplate::create([
            'name' => $request->name,
            'category' => $request->category,
            'content' => $request->content,
            'is_active' => $request->is_active ? true : false,
        ]);

        return redirect()->route('description-templates.index')
            ->with('success', 'Template deskripsi berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(DescriptionTemplate $descriptionTemplate)
    {
        $products = $descriptionTemplate->products()->paginate(5);
        return view('admin.description_templates.show', compact('descriptionTemplate', 'products'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DescriptionTemplate $descriptionTemplate)
    {
        $categories = DescriptionTemplate::getCategories();
        return view('admin.description_templates.edit', compact('descriptionTemplate', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DescriptionTemplate $descriptionTemplate)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'content' => 'required|string',
            'is_active' => 'nullable|boolean',
        ]);

        $descriptionTemplate->update([
            'name' => $request->name,
            'category' => $request->category,
            'content' => $request->content,
            'is_active' => $request->is_active ? true : false,
        ]);

        return redirect()->route('description-templates.index')
            ->with('success', 'Template deskripsi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DescriptionTemplate $descriptionTemplate)
    {
        $descriptionTemplate->delete();
        return redirect()->route('description-templates.index')
            ->with('success', 'Template deskripsi berhasil dihapus.');
    }

    /**
     * Get templates as JSON for AJAX requests
     */
    public function getTemplatesJson(Request $request)
    {
        $templates = DescriptionTemplate::where('is_active', true)
            ->when($request->category, function($query, $category) {
                return $query->where('category', $category);
            })
            ->get(['id', 'name', 'category', 'content']);

        return response()->json($templates);
    }

    /**
     * Attach template to product via AJAX
     */
    public function attachToProduct(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'template_id' => 'required|exists:description_templates,id',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Check if already attached
        if (!$product->descriptionTemplates()->where('description_template_id', $request->template_id)->exists()) {
            $product->descriptionTemplates()->attach($request->template_id);
            return response()->json(['success' => true, 'message' => 'Template berhasil ditambahkan ke produk.']);
        }

        return response()->json(['success' => false, 'message' => 'Template sudah terpasang pada produk ini.']);
    }

    /**
     * Detach template from product via AJAX
     */
    public function detachFromProduct(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'template_id' => 'required|exists:description_templates,id',
        ]);

        $product = Product::findOrFail($request->product_id);
        $product->descriptionTemplates()->detach($request->template_id);

        return response()->json(['success' => true, 'message' => 'Template berhasil dihapus dari produk.']);
    }
}
