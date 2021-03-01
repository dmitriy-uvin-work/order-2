<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 12.11.2019
 * Time: 13:33
 */

namespace App\Http\Services;

use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;


class FileService
{

    public function uploadImage($file, $model, $field, $isGallery): ?string
    {
        if (!is_null($file)) {
            $folderPath = public_path() . $model->folderPath($field);
            $cropSizes = @$model->imageSize($field);

            if(!$isGallery) {
                if (File::exists($folderPath)) {
                    File::deleteDirectory($folderPath);
                }
            }

            File::makeDirectory($folderPath, 0755, true, true);

            $quality = 100;
            $extension = strtolower($file->getClientOriginalExtension());
            $unique = uniqid();
            if (!in_array($extension, ["jpg", "jpeg", "png", "svg"])) {
                File::deleteDirectory($folderPath);
                throw new \Exception('Файл должен быть файлом типа: jpg, jpeg, png, svg' );
            }

            // обрезать изображение и сохранить

            // если изображение не svg и нужно резать его, отправьте его на обрезку
            if (!is_null($cropSizes) && $extension != 'svg') {
                foreach ($cropSizes as $key => $size) {
                    $cropImage = Image::make($file);

                    if (!($size[0] == null && $size[1] == null)) {
                        if ($size[0] != null && $size[1] != null) {
                            $cropImage->fit($size[0], $size[1]);
                        } else {
                            $cropImage->resize($size[0], $size[1], function ($constraint) {
                                $constraint->aspectRatio();
                            });
                        }
                    } else {
                        if(getimagesize($file)[0] > 3000){
                            $cropImage->resize(3000, null, function ($constraint){
                                $constraint->aspectRatio();
                            });
                        }
                    }

                    if (count($size) == 3) {
                        $quality = $size[2];
                    }
                    $cropImage->save($folderPath . '/' . $unique . '_' . $key . '.' . $extension, $quality);
                }
            } else {
                $file->move($folderPath, $unique . '_original.' . $extension);
            }
            return $unique.'.'.$extension;
        }
        return '';
    }
}
