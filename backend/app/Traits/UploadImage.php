<?php

namespace App\Traits;

use function public_path;

trait UploadImage
{
    public function saveImage($image, $path = 'images')
    {
        $imageName = time() . '_' . $image->getClientOriginalName();

        $image->move(public_path($path), $imageName);

        // $image->storeAs($path, $imageName, 'public');
        return $imageName;
    }
}
