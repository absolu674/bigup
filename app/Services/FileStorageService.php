<?php

namespace App\Services;

use App\Models\DedicationVideo;
use App\Models\Fichier;
use App\Models\PhotoProfile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class FileStorageService
{
    protected string $disk;
    protected string $directory;
    protected string $type;

    public function __construct(string $disk = 'public', string $directory = 'uploads', string $type = 'image')
    {
        $this->disk = $disk;
        $this->directory = $directory;
        $this->type = $type;
    }

    public function downloadAndStoreImage(string $imageUrl, int $userId): ?Fichier
    {
        try {
            $response = Http::get($imageUrl);
            if ($response->failed()) {
                return null;
            }

            $extension = pathinfo(parse_url($imageUrl, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
            $fileName = uniqid('avatar_') . '.' . $extension;
            $filePath = $this->directory . '/' . $fileName;

            Storage::disk($this->disk)->put($filePath, $response->body());

            $fichier = Fichier::create([
                'name' => $fileName,
                'path' => $filePath,
                'type' => $this->type,
                'mime_type' => 'image/' . $extension,
                'size' => strlen($response->body()),
                'extension' => $extension,
            ]);

            PhotoProfile::create([
                'user_id' => $userId,
                'fichier_id' => $fichier->id,
            ]);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function setDirectory(string $directory): void
    {
        $this->directory = $directory;
    }

    public function deleteFile(Fichier $file): bool
    {
        if (Storage::disk($file->disk)->exists($file->path)) {
            Storage::disk($file->disk)->delete($file->path);
        }
        return $file->delete();
    }

    public function storeFile(UploadedFile $file, string $type = null): Fichier
    {
        $path = $file->store($this->directory, $this->disk);

        $fichier = Fichier::create([
            'name' => $file->getClientOriginalName(),
            'path' => $path,
            'type' => $type ?? $this->type,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'extension' => $file->getClientOriginalExtension(),
        ]);

        return $fichier;
    }

    public function getFileUrl(string $filePath): ?string
    {
        return Storage::disk($this->disk)->exists($filePath) ? Storage::disk($this->disk)->url($filePath) : null;
    }

    public function storePhotoProfile(Fichier $fichier, int $userId): PhotoProfile
    {
        return PhotoProfile::create([
            'user_id' => $userId,
            'fichier_id' => $fichier->id,
        ]);
    }

    public function storeDedicationDemandVideo(Fichier $fichier, int $dedicationId): DedicationVideo
    {
        return DedicationVideo::create([
            'dedication_id' => $dedicationId,
            'fichier_id' => $fichier->id,
        ]);
    }
}
