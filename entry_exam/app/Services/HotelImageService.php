<?php

namespace App\Services;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Hotel images on the "hotel_images" disk (public/assets/img).
 * Paths are relative to that directory, the same format as hotels.file_path.
 */
class HotelImageService
{
    public const DISK = 'hotel_images';

    /**
     * Images uploaded from the admin screens
     */
    public const DIR = 'hotel';

    /**
     * Images waiting for the edit confirmation
     */
    public const TMP_DIR = 'hotel/tmp';

    public function store(UploadedFile $file): string
    {
        return $file->store(self::DIR, self::DISK);
    }

    public function storeTemporary(UploadedFile $file): string
    {
        return $file->store(self::TMP_DIR, self::DISK);
    }

    /**
     * Move a temporary image to the hotel image directory
     */
    public function promote(string $tmpPath): string
    {
        $path = self::DIR.'/'.basename($tmpPath);
        $this->disk()->move($tmpPath, $path);

        return $path;
    }

    /**
     * Delete an uploaded image. Shared sample images (e.g. hoteltype/*) are never deleted.
     */
    public function delete(?string $path): void
    {
        if ($this->isUploaded($path)) {
            $this->disk()->delete($path);
        }
    }

    public function isUploaded(?string $path): bool
    {
        return $path !== null && str_starts_with($path, self::DIR.'/');
    }

    private function disk(): Filesystem
    {
        return Storage::disk(self::DISK);
    }
}
