<?php

namespace App\Http\Controllers;

use App\Models\LetterInvitation;
use Illuminate\Http\Request;

class PreviewController extends Controller
{
    public function preview($id, $phone)
    {
        $letter_invitation = LetterInvitation::where('receiver_number', $phone)->where('program_id', $id)->firstOrFail();
        // $program = $letter_invitation->program;
        // $array['legends'] = [];
        // $array['value'] = [];

        // if (isset($program->others['legends']) && count($program->others['legends']) > 0) {
        //     foreach ($program->others['legends'] as $key => $value) {
        //         $explodePoint = explode('.', $value);
        //         if (isset($explodePoint[1]) && in_array($explodePoint[1], ['png', 'jpg', 'jpeg', 'webp', 'svg'])) {
        //             $array['legends'][] = $key;
        //             $array['value'][] = url('storage') . '/' . $value;
        //         } else {
        //             $array['legends'][] = $key;
        //             $array['value'][] = $value;
        //         }
        //     }
        // }
        $data = str_replace(['{to}', '{url_wishes}'], [$letter_invitation->receiver_name, url('api/wishes') .'/' . cryptId($letter_invitation->id)], $letter_invitation->program->current_template_letter);
        // echo $program->current_template_letter;

        echo $data;
    }
}
