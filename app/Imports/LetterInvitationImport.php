<?php

namespace App\Imports;

use App\Models\LetterInvitation;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class LetterInvitationImport implements ToCollection
{
    function __construct(public $programId)
    {}
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $collection->each(function ($rows, $key) {
            if ($key == 0) {
                return;
            }
            
            $receiver_number = str_replace([' ', '-', '+'], ['', '', ''], $rows[1]);

            $checkHas = LetterInvitation::query()
                ->where('program_id', $this->programId)
                ->whereIn('receiver_number', [$receiver_number])
                ->first();

            if ($checkHas) {
                $checkHas->receiver_name = $rows[0];
                $checkHas->save();
                return;
            }

            if (empty($rows[0]) || empty($rows[1])) {
                return;
            }

            $model = new LetterInvitation();
            $model->program_id = $this->programId;
            $model->receiver_name = $rows[0];
            $model->receiver_number = $receiver_number;
            $model->save();
        });
    }
}
