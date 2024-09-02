<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContactsResource extends JsonResource
{
    
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this -> id,
            'name' => $this ->name,
            'description' => $this ->description,
            'email' => $this ->email,
            'number' => $this ->number,
            'other' => $this ->other,
            'status' => $this ->status
        ];
    }
}
