<?php

namespace App\Http\Controllers;

use App\Models\DiscoveryCenter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    public function dashboard() {
        $centers = DiscoveryCenter::all();
        return view('admin.dashboard', compact('centers'));
    }

    public function createCenter(Request $request) {
        $center = DiscoveryCenter::create($request->only(['name', 'quiz_timer']));
        if ($request->hasFile('banner_photo')) {
            $path = $request->file('banner_photo')->store('banners', 'public');
            $center->update(['banner_photo' => $path]);
        }
        return back()->with('success', 'Centro creado');
    }

    public function addParticipant(Request $request) {
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'participant',
            'center_id' => $request->center_id,
            'pair_name' => $request->pair_name,
        ]);

        if ($request->hasFile('pair_photo')) {
            $path = $request->file('pair_photo')->store('pairs', 'public');
            $user->update(['pair_photo' => $path]);
        }

        return back()->with('success', 'Participante añadido');
    }

    public function generatePairPdf($userId) {
        $user = User::findOrFail($userId);
        $pdf = Pdf::loadView('pdf.pair_cover', compact('user'));
        return $pdf->download("portada_{$user->pair_name}.pdf");
    }
}
