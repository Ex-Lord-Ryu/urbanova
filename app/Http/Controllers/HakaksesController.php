<?php

namespace App\Http\Controllers;

use App\Models\hakakses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class HakaksesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $search = $request->get('search');
        if ($search) {
            $data['hakakses'] = hakakses::where('id', 'like', "%{$search}%")
                                        ->orWhere('name', 'like', "%{$search}%")
                                        ->orWhere('email', 'like', "%{$search}%")
                                        ->paginate(10);
        } else {
            $data['hakakses'] = hakakses::paginate(10);
        }
        return view('layouts.hakakses.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('layouts.hakakses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,user',
        ]);

        // Create new user
        $hakakses = new hakakses();
        $hakakses->name = $request->name;
        $hakakses->email = $request->email;
        $hakakses->password = Hash::make($request->password);
        $hakakses->role = $request->role;
        $hakakses->save();

        return redirect()->route('hakakses.index')->with('message', 'User berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(hakakses $hakakses)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
        $hakakses = hakakses::find($id);
        return view('layouts.hakakses.edit', compact('hakakses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, hakakses $hakakses, $id)
    {
        // Find the user
        $hakakses = hakakses::find($id);

        // Validate request
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'role' => 'required|in:admin,user',
        ];

        // Only validate password if it's provided
        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $request->validate($rules);

        // Update user
        $hakakses->name = $request->name;
        $hakakses->email = $request->email;
        $hakakses->role = $request->role;

        // Only update password if it's provided
        if ($request->filled('password')) {
            $hakakses->password = Hash::make($request->password);
        }

        $hakakses->save();

        return redirect()->route('hakakses.index')->with('message', 'User berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(hakakses $hakakses)
    {
        //
        $hakakses->delete();
        return redirect()->route('hakakses.index')->with('message', 'User berhasil dihapus');
    }
}
