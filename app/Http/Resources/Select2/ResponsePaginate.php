<?php

namespace App\Http\Resources\Select2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResponsePaginate extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => md5('--' . $this->id . '--'),
            'text' => $this->text,
        ];
    }
}
