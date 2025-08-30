<?php

namespace App\Services;

use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Api\Admin\AdminApi;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class CloudinaryService
{
    /**
     * Upload an image to Cloudinary
     *
     * @param UploadedFile $file
     * @param string $folder
     * @return array
     */
    public function uploadImage(UploadedFile $file, string $folder): array
    {
        try {
            $uploadApi = new UploadApi();
            $result = $uploadApi->upload($file->getRealPath(), [
                'folder' => $folder,
            ]);
            
            // Check if upload was successful
            if (!$result || !isset($result['public_id']) || !isset($result['secure_url'])) {
                return [
                    'public_id' => null,
                    'url' => null,
                ];
            }
            
            return [
                'public_id' => $result['public_id'],
                'url' => $result['secure_url'],
            ];
        } catch (\Exception $e) {
            Log::error('Error uploading image to Cloudinary: ' . $e->getMessage());
            Log::error('Exception details:', [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return [
                'public_id' => null,
                'url' => null,
            ];
        }
    }

    /**
     * Delete an image from Cloudinary
     *
     * @param string $publicId
     * @return bool
     */
    public function deleteImage(string $publicId): bool
    {
        try {
            $uploadApi = new UploadApi();
            $result = $uploadApi->destroy($publicId);
            
            // Check if deletion was successful
            if (!$result || !isset($result['result'])) {
                return false;
            }
            
            return $result['result'] === 'ok';
        } catch (\Exception $e) {
            Log::error('Error deleting image from Cloudinary: ' . $e->getMessage());
            return false;
        }
    }
}