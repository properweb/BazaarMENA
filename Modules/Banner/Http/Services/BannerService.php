<?php

namespace Modules\Banner\Http\Services;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Modules\Banner\Entities\Banner;
use Carbon\Carbon;


class BannerService
{
    private string $absPath = "";
    private string $relPath = "";

    public function __construct()
    {
        $this->absPath = public_path('uploads/banners');
        $this->relPath = 'uploads/banners/';
    }


    /**
     * Add Banner
     *
     * @param array $requestData
     * @return array
     */
    public function addBanner(array $requestData): array
    {
        try{
            $absPath = $this->absPath;
            $relPath = $this->relPath;

            if (!File::exists($absPath)) {
                mkdir($absPath, 0777, true);
            }
            if ($requestData['banner_image']) {
                $file = $requestData['banner_image'];
                $fileName = Str::random(10) . '_banner_image.' . $file->extension();
                $file->move($absPath, $fileName);
                $requestData['banner_image'] = $relPath . $fileName;
            }
            $requestData['active']='1';
            $banner = new Banner();
            $banner->fill($requestData);
            $banner->save();

            $response = ['res' => true,'msg' => '','data' => $banner];
        } catch (Exception $e) {
            $errorCode = $e->getCode();
            $response = ['res' => false, 'msg' => $e->getMessage(), 'data' => $errorCode];
        }
        return $response;
    }


}
