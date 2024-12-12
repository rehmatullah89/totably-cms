<?php

namespace App\Services;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;

class ImageService
{
    public $withImage = false;
    public $withImageThumb = false;
    public $imageName = "image";
    public $thumbnailName = "thumbnail";
    public $filePath = "1";

    public function __construct($withImage, $withImageThumb, $imageName, $thumbnailName, $filePath)
    {
        $this->withImage = $withImage;
        $this->withImageThumb = $withImageThumb;
        $this->imageName = $imageName;
        $this->thumbnailName = $thumbnailName;
        $this->filePath = $filePath;
    }

    /**
     * Description: the following method is used to attach images to the respective model instance or on relational instance
     * @author Shuja Ahmed - I2L
     * @param $input
     * @param $model
     * @param bool $isRelationUpload
     * @return bool
     */
    public function attachImage($input, $model, $isRelationUpload = false)
    {

        //validate the request
        if (!$isRelationUpload && !$input->hasFile($this->imageName)) {
            return true;
        }

        //remove existing image
        $this->deleteImage($model);

        // if relational model instance then input is actually supposed to tbe a file
        $file = $isRelationUpload ? $input : $input->{$this->imageName};
        $this->uploadImage($file, $model);
        return $model;
    }

    /**
     * Description: The following method is used to upload the image
     * @author Shuja Ahmed - I2L
     * @param $file File
     * @param $model
     * @return bool
     */
    public function uploadImage($file, $model)
    {

        //get file path
        $filePath = $this->getFilePath();

        //Set public folder path
        $folderPath = public_path($filePath);
        //renaming the file
        $name = time() . '_' . rand(5000, 100000) . "." . $file->getClientOriginalExtension();


        if (!$file->move($folderPath, $name)) {
            return false;
        }

        //resize image quality
        Image::make($folderPath . $name)->resize(
            700,
            null,
            function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            }
        )->save($folderPath . $name, 75);

        //if thumbnail is to be uploaded
        if ($this->withImageThumb) {
            //thumbnail file name
            $thumbName = "thumb_" . $name;

            //creating a thumbnail image
            Image::make($folderPath . $name)->resize(
                200,
                null,
                function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                }
            )->save($folderPath . $thumbName, 60);

            //update permission
            chmod($folderPath . $thumbName, 0777);

            $model->{$this->thumbnailName} = $filePath . $thumbName;
        }

        // applying the necessary public permissions
        chmod($folderPath . $name, 0777);
        $model->{$this->imageName} = $filePath . $name;
    }

    /**
     * Description:  The following method is used to physically delete the image
     * @author Shuja Ahmed - I2L
     * @param $model
     * @return bool
     */
    public function deleteImage($model)
    {
        //if not image return back
        if (!$this->withImage || !$model->{$this->imageName}) {
            return false;
        }


        // the following is done to get the physical path of the file , from the server path of the respective image
        $imagePublicPath = explode(env('APP_URL', 'https://stg-api.skrambler-app.com'), $model->{$this->imageName}, 2);
        $existingImage = public_path($imagePublicPath[0]);

        if (is_file($existingImage)) {
            unlink($existingImage);
        }
        $model->{$this->imageName} = "";

        //check Thumbnail
        if ($this->withImageThumb && $model->{$this->thumbnailName}) {
            $existingThumImage = public_path($model->{$this->thumbnailName});
            if (is_file($existingThumImage)) {
                unlink($existingThumImage);
            }
            $model->{$this->thumbnailName} = "";
        }

        //updating the record
        $model->save();

        return true;
    }

    /**
     * Description: The following method is used to get the file path
     * @author Shuja Ahmed - I2L
     * @return string
     */
    public function getFilePath(): string
    {
        $value = $this->filePath;

        //Replace user id if exist
        if ($user = Auth::user()) {
            $value = str_replace("{user_id}", $user->id, $value);
        }

        return $value;
    }
}
