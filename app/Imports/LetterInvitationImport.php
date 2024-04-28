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
        $collection->chunk(50, function ($rows, $key) {
            if ($key == 0) {
                return;
            }

            $checkHas = LetterInvitation::query()
                ->where('program_id', $this->programId)
                ->whereIn('receiver_number', $rows->pluck(1))
                ->first();

            if ($checkHas) {
                $checkHas->receiver_name = $rows[0];
                $checkHas->save();
                return;
            }

            $model = new LetterInvitation();
            $model->program_id = $this->programId;
            $model->receiver_name = $rows[0];
            $model->receiver_number = $rows[1];
            $model->save();
        });
    }
}