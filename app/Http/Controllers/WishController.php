<?php

namespace App\Http\Controllers;

use App\Models\Wish;
use App\Http\Requests\StoreWishRequest;
use App\Http\Requests\UpdateWishRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class WishController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $get_all_wishes = DB::table('wishes')
            ->join('letter_invitations', 'wishes.letter_invitation_id', '=', 'letter_invitations.id')
            ->select('wishes.*', 'letter_invitations.name')
            ->paginate();

        return response()->json([
            'status_code' => 200,
            'message' => 'Data wishes berhasil diambil',
            'data' => $get_all_wishes
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWishRequest $request)
    {
        $create_wish = Wish::create($request->all());

        return response()->json([
            'status_code' => 201,
            'message' => 'Data wish berhasil ditambahkan',
            'data' => $create_wish
        ]);
    }
}
