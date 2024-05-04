<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wish;
use Illuminate\Http\Request;

class WishController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $wishes = Wish::query()
                ->with('letterInvitation')
                ->whereHas('letterInvitation', function ($query) {
                    $query->where('program_id', auth()->user()->program?->id);
                })
                ->when(request('type') ?? false, function ($query, $type) {
                    $query->where('confirmation', $type);
                });

            return datatables($wishes)
                ->addIndexColumn()
                ->editColumn('id', function ($wish) {
                    return cryptId($wish->id);
                })
                ->addColumn('sender', function ($wish) {
                    return $wish->letterInvitation?->receiver_name . ($wish->other_people ? ' dan ' . $wish->other_people : '');
                })
                ->addColumn('action', function ($wish) {
                    return "
                        <a href='javascript:;' class='btn btn-sm btn-warning' data-bs-toggle='modal' data-bs-target='#modal-edit-wishes' data-id='". cryptId($wish->id) ."'><i class='ti ti-pencil'></i></a>
                        <a href='javascript:;' class='btn btn-sm btn-danger' data-bs-toggle='modal' data-bs-target='#modal-delete-wishes' data-id='". cryptId($wish->id) ."'><i class='ti ti-trash'></i></a>
                    ";
                })
                ->editColumn('confirmation', function ($wish) {
                    return ucfirst($wish->confirmation);
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('admin.wish.index');
    }

    public function show($id)
    {
        $id = decryptId($id);
        $wish = Wish::query()->findOrFail($id);
        $view = view('admin.wish.show', compact('wish'))->render();
        return response()->json([
            'status_code' => 200,
            'message' => 'Data wish berhasil diambil',
            'data' => $view
        ]);
    }

    public function destroy($id)
    {
        $id = decryptId($id);
        $wish = Wish::query()->findOrFail($id);
        $wish->delete();

        return response()->json([
            'status_code' => 200,
            'message' => 'Data wish berhasil dihapus',
        ]);
    }
}
