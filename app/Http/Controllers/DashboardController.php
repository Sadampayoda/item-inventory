<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }

    public function profile(Request $request)
    {
        $user = auth()->user();

        if ($request->isMethod('put')) {

            $data = $request->validate([
                'image'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            if ($request->hasFile('image')) {
                if ($user->image) {
                    Storage::disk('public')->delete($user->image);
                }

                $data['image'] = $request->file('image')
                    ->store('profiles', 'public');
            }

            $user->update($data);

        }
        return back()->with('success', 'Profile berhasil diperbarui');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.index');
    }
}
