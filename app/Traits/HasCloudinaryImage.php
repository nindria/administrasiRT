<?php

namespace App\Traits;

use App\Services\CloudinaryService;

trait HasCloudinaryImage
{
    /**
     * Boot the trait and add model events for Cloudinary image management
     */
    protected static function bootHasCloudinaryImage()
    {
        // Auto-delete image from Cloudinary when model is deleted
        static::deleting(function ($model) {
            if ($model->public_id) {
                $cloudinaryService = new CloudinaryService;
                $cloudinaryService->deleteImage($model->public_id);
            }
        });
    }

    /**
     * Upload image to Cloudinary and update model attributes
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @param  string  $folder
     * @param  string  $imageField
     * @return array
     */
    public function uploadToCloudinary($file, $folder, $imageField = 'image')
    {
        // Delete old image if exists
        if ($this->public_id) {
            $cloudinaryService = new CloudinaryService;
            $cloudinaryService->deleteImage($this->public_id);
        }

        // Upload new image
        $cloudinaryService = new CloudinaryService;
        $uploadResult = $cloudinaryService->uploadImage($file, $folder);

        if ($uploadResult['url']) {
            $this->{$imageField} = $uploadResult['url'];
            $this->public_id = $uploadResult['public_id'];

            return $uploadResult;
        }

        return [
            'url' => null,
            'public_id' => null,
        ];
    }

    /**
     * Delete image from Cloudinary
     *
     * @param  string  $imageField
     * @return bool
     */
    public function deleteFromCloudinary($imageField = 'image')
    {
        if ($this->public_id) {
            $cloudinaryService = new CloudinaryService;
            $result = $cloudinaryService->deleteImage($this->public_id);

            if ($result) {
                $this->{$imageField} = null;
                $this->public_id = null;

                return true;
            }
        }

        return false;
    }
}
