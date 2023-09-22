<?php

namespace Modules\Settings\Http\Services;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Modules\User\Entities\Category;
use Modules\User\Entities\Country;
use Modules\User\Entities\State;
use Modules\User\Entities\City;
use Modules\User\Entities\User;
use Carbon\Carbon;


class SettingsService
{
    protected User $user;
    private string $userAbsPath = "";
    private string $userRelPath = "";

    public function __construct()
    {
        $this->userAbsPath = public_path('uploads/users');
        $this->userRelPath = 'uploads/users/';
    }

    /**
     * Update User
     *
     * @param array $requestData
     * @return array
     */
    public function changePassword(array $requestData): array
    {
        $user = auth()->user();

        if (!empty($requestData['new_password'])) {
            if (Hash::check($requestData['old_password'], $user->password)) {
                $user->password = Hash::make($requestData['new_password']);
            } else {
                return ['res' => false, 'msg' => 'Old password does not match our record.', 'data' => ""];
            }
        }
        $user = User::updateOrCreate(['id' => $user->id], ['password' => Hash::make($requestData['new_password'])]);
        return ['res' => true, 'msg' => "Successfully updated your password", 'data' => ''];
    }

    /**
     * Update User
     *
     * @param array $requestData
     * @return array
     */
    public function updateInfo(array $requestData): array
    {
        $user = auth()->user();
        // Find the user by ID
        $user = User::find($user->id);
        if ($user) {
            // Update the user's attributes
            $user->first_name = $requestData['first_name'];
            $user->last_name = $requestData['last_name'];
            $user->country_code = $requestData['country_code'];
            $user->phone_number = $requestData['phone_number'];
            // Save the changes to the database
            $user->save();
            $responce = ['res' => true, 'msg' => "Successfully updated your password", 'data' => ''];
        }else{
            $responce = ['res' => false, 'msg' => "Didn't get user!", 'data' => ''];
        }
        return $responce;
    }

}
