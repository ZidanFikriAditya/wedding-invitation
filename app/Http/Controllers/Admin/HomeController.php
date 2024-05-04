<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LetterInvitation;
use App\Models\Wish;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $wish = Wish::query()
            ->whereHas('letterInvitation', function ($query) {
                $query->where('program_id', auth()->user()->program?->id);
            })
            ->select('letter_invitation_id', 'confirmation')
            ->groupBy('letter_invitation_id', 'confirmation')
            ->get();

        $data['attend'] = $wish->where('confirmation', 'datang')->count();
        $data['notAttend'] = $wish->where('confirmation', 'tidak datang')->count();

        $data['totalInvitation'] = LetterInvitation::query()
            ->where('program_id', auth()->user()->program?->id)
            ->count();
        
        return view('admin.index', $data);;
    }
}
