<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.program.index');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'type_medsos.*' => 'required|string',
            'social_media.*' => 'required|string',
        ]);

        $user = Auth::user();

        try {
            if ($user->program) {
                $program = Program::query()
                    ->where('owned_id', $user->id)
                    ->first();
    
                $medsos = [];
                foreach ($request->type_medsos as $key => $type) {
                    $medsos[$type] = $request->social_media[$key];
                }
    
                $program->update([
                    'name' => $request->name,
                    'description' => $request->description,
                    'phone_number' => $request->phone_number,
                    'address' => $request->address,
                    'social_media' => $medsos,
                ]);
            } else {
                $medsos = [];
                foreach ($request->type_medsos as $key => $type) {
                    $medsos[$type] = $request->social_media[$key];
                }
    
                $program = Program::create([
                    'name' => $request->name,
                    'description' => $request->description,
                    'phone_number' => $request->phone_number,
                    'address' => $request->address,
                    'social_media' => $medsos,
                    'owned_id' => $user->id,
                ]);
            }

            return redirect()->back()->with('success', 'Berhasil mengubah acara');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('error', 'Gagal membuat acara baru');
        }
    }

}
