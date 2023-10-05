<?php

namespace Modules\Home\Http\Services;

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


class HomeService
{
    private string $absPath = "";
    private string $relPath = "";

    public function __construct()
    {
        $this->absPath = public_path('uploads/banners');
        $this->relPath = 'uploads/banners/';
    }


    /**
     * Get Home Page Data
     *
     * @return array
     */
    public function getData(): array
    {
        try{
            $data['banners'] = Banner::where('active', '1')->get()->toArray();
            $response = ['res' => false, 'msg' => '', 'data' => $data];
        } catch (Exception $e) {
            $errorCode = $e->getCode();
            if ($errorCode == 23000) {
                $errorMessage = "direct link already exists";
            } else {
                $errorMessage = $e->getMessage();//"something went wrong, please try again";
            }
            $response = ['res' => false, 'msg' => $errorMessage, 'data' => $errorCode];
        }
        return $response;
    }

}
