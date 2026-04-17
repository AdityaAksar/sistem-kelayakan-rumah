<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'pendata')->latest()->paginate(15);
        return view('admin.pengguna.index', compact('users'));
    }

    public function create()
    {
        return view('admin.pengguna.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name'    => $request->name,
            'email'   => $request->email,
            'password'=> Hash::make($request->password),
            'role'    => 'pendata',
            'is_active' => true,
        ]);

        return redirect()->route('admin.pengguna.index')->with('success', 'Akun petugas pendata berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('admin.pengguna.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $data = $request->only('name', 'email');
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        return redirect()->route('admin.pengguna.index')->with('success', 'Akun berhasil diperbarui.');
    }

    public function toggleAktif(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Akun {$user->name} berhasil {$status}.");
    }

    public function destroy(Request $request, User $user)
    {
        // Konfirmasi 2 langkah – ketik kata HAPUS (RF-ADM-04)
        if ($request->konfirmasi !== 'HAPUS') {
            return back()->with('error', 'Konfirmasi tidak valid. Ketik kata HAPUS untuk menghapus.');
        }
        // Soft delete via is_active agar data survei lama tidak rusak
        $user->update(['is_active' => false]);
        return redirect()->route('admin.pengguna.index')->with('success', 'Akun berhasil dinonaktifkan (soft delete).');
    }
}
