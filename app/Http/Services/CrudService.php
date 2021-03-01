<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 25.08.2020
 * Time: 14:15
 */

namespace App\Http\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CrudService
{

    private $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function changeStatus($status = null)
    {
        if (isset($status)) {
            return 1;
        }
        return 0;
    }

    public function dateFormat($date)
    {
        if ($date) {
            return Date::createFromFormat('d.m.Y', $date);
        } else {
            return now()->format('Y-m-d');
        }
    }

    public function applyImages($model, $files, $noChangedGallery = [])
    {
        $new_data = [];
        if (count($files) > 0) {
            foreach ($files as $key => $file) {
                if (is_array($file)) {
                    foreach ($file as $f) {
                        $noChangedGallery[] = $this->fileService->uploadImage($f, $model, $key, true);
                    }
                    $new_data[$key] = json_encode($noChangedGallery);
                } else {
                    $new_data[$key] = $this->fileService->uploadImage($file, $model, $key, false);
                }
            }
        }
        return $new_data;
    }

    public function linkedCategories($categories, $model)
    {
        if (!empty($categories)) {
            $model->categories()->sync($categories);
        } else {
            $model->categories()->detach();
        }
    }

    public function linkedTags($tags, $model)
    {
        if (!empty($tags)) {
            $model->tags()->sync($tags);
        } else {
            $model->tags()->detach();
        }
    }

    public function linkedOptions($options, $model)
    {
        if (!empty($options)) {
            $model->options()->sync($options);
        } else {
            $model->options()->detach();
        }
    }

    public function linkedSingleOptions($options, $model)
    {
        DB::table('category_option')->where('category_id', $model->id)->whereNotNull('single_option_id')->delete();
        if (!empty($options)) {
            foreach ($options as $key => $option) {
                DB::table('category_option')->insert(['category_id'=>$model->id, 'single_option_id'=>$option]);
            }
        }
    }

    public function linkedBrands($brands, $model)
    {
        DB::table('category_brand')->where(['category_id'=>$model->id])->delete();
        if (!empty($brands)) {
            foreach ($brands as $key => $brand) {
                DB::table('category_brand')->insert(['category_id'=>$model->id, 'brand_id'=>$brand]);
            }
        }
    }

    public function CREATE_OR_UPDATE($model, $request, $id)
    {
        DB::beginTransaction();
        try {
            $dataReq = $request->all();

            // get files
            $files = $request->files->all();

            $data = new $model;

            $dataReq['status'] = $this->changeStatus($dataReq['status'] ?? null);
            $dataReq['on_top'] = $this->changeStatus($dataReq['on_top'] ?? null);
            $dataReq['site_active'] = $this->changeStatus($dataReq['site_active'] ?? null);
            $noChangedGallery = $request->get('gallery');
            $dataReq['gallery'] = json_encode($noChangedGallery) ?? json_encode([]);
            if ($id) {
                $data = $model::findOrFail($id);
                $data->update($dataReq);
            } else {
                $data = $data->create($dataReq);
            }

            // applyImages
            $uploadedImages = $this->applyImages($data, $files, $noChangedGallery);
            if (!empty($uploadedImages)) {
                $data->update($uploadedImages);
            }

            // linkedCategories
            if (isset($data->categories)) {
                $this->linkedCategories($request->get('categories'), $data);
            }

            // linkedOptions
            if (isset($data->options)) {
                $this->linkedOptions($request->get('options'), $data);
            }

            // linkedSingleOptions
            if (isset($data->singleOptions)) {
                $this->linkedSingleOptions($request->get('singleOptions'), $data);
            }

            //linkedTags
            if (isset($data->tags)) {
                $this->linkedTags($request->get('tags'), $data);
            }

            //linkedBrands
            if (isset($data->brands)) {
                $this->linkedBrands($request->get('brands'), $data);
            }

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new \DomainException($exception->getMessage(), $exception->getCode());
        }

        return $data;
    }
}
