<?php

namespace App\Repositories;

use App\Enums\DedicationState;
use App\Http\Requests\DedicationRequest;
use App\Http\Resources\DedicationResource;
use App\Models\Dedication;
use App\Models\DedicationVideo;
use App\Services\FileStorageService;
use Illuminate\Http\Exceptions\HttpResponseException;

class DedicationRepository
{
    protected $dedication;
    protected $fileStorageService;
    /**
     * Create a new class instance.
     */
    public function __construct(Dedication $dedication, FileStorageService $fileStorageService)
    {
        $this->dedication = $dedication;
        $this->fileStorageService = $fileStorageService;
    }

    public function index()
    {
        $user = auth()->user();
        $field = $user->hasRole('client') ? 'user_id' : ($user->hasRole('artist') ? 'artist_id' : 'artist_name');
        $dedications = $user->hasRole('admin') ? $this->dedication->all() : $this->getDedicationByUserId($user->id, $field);
        return $dedications;
    }

    public function getUserDedication($i)
    {
        return $this->dedication->where('user_id', $i)->get();
    }

    public function getDedicationBySlug($slug): Dedication|null
    {
        $dedication = $this->dedication->where('slug', $slug)->first();
        return $dedication;
    }

    public function getDedicationByUserId($id, $field)
    {
        return $this->dedication->where($field, $id)->get();
    }

    public function getDedicationByArtistId($id)
    {
        return $this->dedication->where('user_id', $id)->get();
    }

    public function createDedication(DedicationRequest $data): Dedication
    {
        $dedication = $this->dedication::create($data->except('dedication_video'));
        if($data->hasFile('dedication_video')){
            $fichier = $this->fileStorageService->storeFile($data->dedication_video, 'video');
            $dedication->dedication_video_path = $fichier->path;
            $dedication->save();
        }
        return $dedication;
    }

    public function acceptDedication(string $slug): Dedication
    {
        $dedication = $this->dedication::where('slug', $slug)->firstOrFail();
        if ($dedication->state === DedicationState::ACCEPTED->value) {
            throw new HttpResponseException(response()->json([
                'message' => 'An error occurred.',
                'error' => 'The dedication is already accepted'
            ], 400));
        }

        $dedication->update(['state' => DedicationState::ACCEPTED->value]);
        return $dedication;
    }


    public function rejectDedication($slug, $message): Dedication
    {
        $dedication = $this->dedication::where('slug', $slug)->firstOrFail();
        if ($dedication->state === DedicationState::ACCEPTED->value) {
            throw new HttpResponseException(response()->json([
                'message' => 'An error has occurred.',
                'error' => 'The dedication is already accepted'
            ], 400));
        }

        $dedication->update([
            'state' => DedicationState::REJECTED->value,
            'message_rejected' => $message
        ]);

        return $dedication;
    }

    public function shipDedication($slug, $request): Dedication
    {
        $dedication = $this->dedication::where('slug', $slug)->firstOrFail();
        if ($dedication->state === DedicationState::REJECTED->value) {
            throw new HttpResponseException(response()->json([
                'message' => 'An error has occurred.',
                'error' => 'The dedication is rejected you cannot ship.'
            ], 400));
        }

        $fichier = $this->fileStorageService->storeFile($request->ship_video, 'video');
        DedicationVideo::create([
            'dedication_id' => $dedication->id,
            'fichier_id' => $fichier->id,
        ]);

        $dedication->update([
            'state' => DedicationState::SHIPPED->value,
        ]);

        return $dedication;
    }
}
