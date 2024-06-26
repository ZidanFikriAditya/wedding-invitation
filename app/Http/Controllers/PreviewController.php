<?php

namespace App\Http\Controllers;

use App\Models\LetterInvitation;
use Illuminate\Http\Request;

class PreviewController extends Controller
{
    public function preview($id, $phone)
    {
        $letter_invitation = LetterInvitation::where('receiver_number', $phone)->where('program_id', $id)->firstOrFail();
        $letter_invitation->status = 'sended';
        $letter_invitation->save();
        
        $data = str_replace(['{to}', '{url_wishes}'], [$letter_invitation->receiver_name, url('api/wishes') .'/' . cryptId($letter_invitation->id)], $letter_invitation->program->current_template_letter);

        echo $data;
    }
}
