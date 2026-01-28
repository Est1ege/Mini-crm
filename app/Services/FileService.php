<?php

namespace App\Services;

use App\Models\Ticket;
use Illuminate\Http\UploadedFile;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class FileService
{
    public function attachFiles(Ticket $ticket, array $files): void
    {
        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $ticket->addMedia($file)
                    ->toMediaCollection('attachments');
            }
        }
    }

    public function getMedia(Ticket $ticket): \Illuminate\Support\Collection
    {
        return $ticket->getMedia('attachments');
    }

    public function downloadMedia(Media $media): string
    {
        return $media->getPath();
    }

    public function deleteMedia(Media $media): void
    {
        $media->delete();
    }
}
