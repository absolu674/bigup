<?php

namespace App\Http\Resources;

use App\Enums\UserType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $isAdmin = $request->user()->hasRole('admin');
        return [
            'id' =>$this->id,
            'lastname' => $this->lastname,
            'firstname' => $this->firstname,
            'type' => $this->when($isAdmin, $this->type),
            'email' => $this->when($isAdmin, $this->email),
            'phone' => $this->when($isAdmin, $this->phone),
            'bio' => $this->when($this->type == UserType::ARTIST->value, $this->bio),
            'alias' => $this->alias,
            'photo_profile' => $this->photoProfile?->path
        ];
    }
}
