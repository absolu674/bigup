<?php

namespace App\Http\Resources;

use App\Enums\DedicationState;
use App\Models\Dedication;
use App\Models\Fichier;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DedicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $isAdmin = auth()->user()->hasRole('admin');
        $isClient = auth()->user()->hasRole('client');
        $isArtist = auth()->user()->hasRole('artist');
        return [
            'id' => $this->id,
            'message' => $this->message,
            'dedication_type' => new DedicationTypeResource($this->dedicationType),
            'fan' => $this->when($isAdmin || $isArtist, new UserResource( $this->user)),
            'artist' => $this->when($isAdmin || $isClient,new UserResource($this->artist)),
            'demand_video_path' => $this->dedication_video_path,
            'shiped_video_path' => $this->when($this->state == DedicationState::SHIPPED->value,$this->videoFile()->first()?->path),
            'state' => $this->state,
            'firstname' => $this->when($this->self, $this->firstname),
            'lastname' => $this->when($this->self, $this->lastname),
            'email' => $this->when($this->self, $this->email),
            'slug' => $this->slug
        ];
    }
}
