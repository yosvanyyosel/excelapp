<?php

namespace App\Http\Controllers;

use App\Models\DiscoveryCenter;
use App\Models\User;
use App\Models\TestResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard() {
        $centers = DiscoveryCenter::with('users')->get();
        $admins = User::where('role', 'admin')->get();
        return view('admin.dashboard', compact('centers', 'admins'));
    }

    public function showCenter($id) {
        $center = DiscoveryCenter::with('users')->findOrFail($id);
        $pairs = $center->users->where('role', 'participant')->groupBy('pair_name');
        return view('admin.center_details', compact('center', 'pairs'));
    }

    public function showPair($centerId, $pairName) {
        $center = DiscoveryCenter::findOrFail($centerId);
        $users = User::where('center_id', $centerId)
                     ->where('pair_name', $pairName)
                     ->get();

        $husband = $users->first();
        $wife = $users->count() > 1 ? $users->last() : null;

        // Buscar resultados de ambos (por nombre y apellido como está en la tabla test_results actualmente)
        // Nota: Sería mejor por user_id, pero mantenemos compatibilidad con lo previo
        $husbandResults = TestResult::where('user_name', $husband->name)->get();
        $wifeResults = $wife ? TestResult::where('user_name', $wife->name)->get() : collect();

        return view('admin.pair_details', compact('center', 'pairName', 'husband', 'wife', 'husbandResults', 'wifeResults'));
    }

    // --- CENTROS ---
    public function createCenter(Request $request) {
        $center = DiscoveryCenter::create($request->only(['name', 'quiz_timer']));
        if ($request->hasFile('banner_photo')) {
            $path = $request->file('banner_photo')->store('banners', 'public');
            $center->update(['banner_photo' => $path]);
        }
        return redirect()->route('dashboard')->with('success', 'Centro creado exitosamente.');
    }

    public function updateCenter(Request $request, $id) {
        $center = DiscoveryCenter::findOrFail($id);
        $center->update($request->only(['name', 'quiz_timer']));
        if ($request->hasFile('banner_photo')) {
            if ($center->banner_photo) Storage::disk('public')->delete($center->banner_photo);
            $path = $request->file('banner_photo')->store('banners', 'public');
            $center->update(['banner_photo' => $path]);
        }
        return back()->with('success', 'Centro actualizado.');
    }

    public function deleteCenter($id) {
        $center = DiscoveryCenter::findOrFail($id);
        foreach ($center->users as $user) {
            if ($user->pair_photo) Storage::disk('public')->delete($user->pair_photo);
            $user->delete();
        }
        if ($center->banner_photo) Storage::disk('public')->delete($center->banner_photo);
        $center->delete();
        return back()->with('success', 'Centro y todos sus datos eliminados.');
    }

    // --- ADMINISTRADORES ---
    public function addAdmin(Request $request) {
        if (Auth::user()->role !== 'master') return abort(403);
        $request->validate(['username' => 'required|unique:users,username', 'password' => 'required']);
        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'admin',
        ]);
        return back()->with('success', 'Administrador creado.');
    }

    // --- PAREJAS ---
    public function addPair(Request $request) {
        $request->validate([
            'center_id' => 'required',
            'pair_name' => 'required',
            'husband_name' => 'required',
            'husband_username' => 'required|unique:users,username',
            'husband_password' => 'required',
            'wife_name' => 'required',
            'wife_username' => 'required|unique:users,username',
            'wife_password' => 'required',
        ]);

        $pair_photo_path = null;
        if ($request->hasFile('pair_photo')) {
            $pair_photo_path = $request->file('pair_photo')->store('pairs', 'public');
        }

        User::create([
            'name' => $request->husband_name,
            'username' => $request->husband_username,
            'password' => Hash::make($request->husband_password),
            'role' => 'participant',
            'center_id' => $request->center_id,
            'pair_name' => $request->pair_name,
            'pair_photo' => $pair_photo_path,
        ]);

        User::create([
            'name' => $request->wife_name,
            'username' => $request->wife_username,
            'password' => Hash::make($request->wife_password),
            'role' => 'participant',
            'center_id' => $request->center_id,
            'pair_name' => $request->pair_name,
            'pair_photo' => $pair_photo_path,
        ]);

        return back()->with('success', 'Pareja registrada exitosamente.');
    }

    public function updatePair(Request $request) {
        $request->validate([
            'old_pair_name' => 'required',
            'center_id' => 'required',
            'pair_name' => 'required',
            'husband_id' => 'required',
            'husband_name' => 'required',
            'wife_id' => 'required',
            'wife_name' => 'required',
        ]);

        $husband = User::findOrFail($request->husband_id);
        $wife = User::findOrFail($request->wife_id);

        $pair_photo_path = $husband->pair_photo;
        if ($request->hasFile('pair_photo')) {
            if ($pair_photo_path) Storage::disk('public')->delete($pair_photo_path);
            $pair_photo_path = $request->file('pair_photo')->store('pairs', 'public');
        }

        $husband->update([
            'name' => $request->husband_name,
            'username' => $request->husband_username ?? $husband->username,
            'pair_name' => $request->pair_name,
            'pair_photo' => $pair_photo_path,
        ]);

        if ($request->husband_password) {
            $husband->update(['password' => Hash::make($request->husband_password)]);
        }

        $wife->update([
            'name' => $request->wife_name,
            'username' => $request->wife_username ?? $wife->username,
            'pair_name' => $request->pair_name,
            'pair_photo' => $pair_photo_path,
        ]);

        if ($request->wife_password) {
            $wife->update(['password' => Hash::make($request->wife_password)]);
        }

        return back()->with('success', 'Datos de la pareja actualizados.');
    }

    public function deletePair(Request $request) {
        $users = User::where('pair_name', $request->pair_name)
                     ->where('center_id', $request->center_id)
                     ->get();

        foreach ($users as $user) {
            if ($user->pair_photo) Storage::disk('public')->delete($user->pair_photo);
            $user->delete();
        }
        return back()->with('success', 'Pareja eliminada.');
    }

    public function generatePairPdf($userId) {
        $user = User::findOrFail($userId);
        $pdf = Pdf::loadView('pdf.pair_cover', compact('user'));
        return $pdf->stream("portada_{$user->pair_name}.pdf");
    }
}
