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
use Modules\Settings\Entities\Shipping;
use Modules\Settings\Entities\PaymentTerms;
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

    /**
     * Update User
     *
     * @param array $requestData
     * @return array
     */
    public function updateCompanyProfileInfo(array $requestData): array
    {
        $user = auth()->user();
        // Find the user by ID
        $user = User::find($user->id);
        if ($user) {
            // Update the user's attributes
            $user->industry = $requestData['industry'];
            $user->address = $requestData['address'];

            if (isset($requestData['letter_of_incorporation']) && !filter_var($requestData['letter_of_incorporation'], FILTER_VALIDATE_URL)) {
                $user->letter_of_incorporation = $this->uploadFile($user->id,$requestData['letter_of_incorporation'],'letter_of_incorporation',$user->letter_of_incorporation,true);
            }

            if (isset($requestData['identity_card']) && !filter_var($requestData['identity_card'], FILTER_VALIDATE_URL)) {
                $user->identity_card = $this->uploadFile($user->id,$requestData['identity_card'],'identity_card',$user->identity_card,true);
            }

            $user->industry = $requestData['industry'];
            $user->category_id = $requestData['category_id'];
            // Save the changes to the database
            $user->save();
            $responce = ['res' => true, 'msg' => "Successfully updated company profile", 'data' => ''];
        }else{
            $responce = ['res' => false, 'msg' => "Didn't get user!", 'data' => ''];
        }
        return $responce;
    }

    /**
     * Update User
     *
     * @param array $requestData
     * @return array
     */
    public function shippingUpdateOrCreate(array $requestData): array
    {
        $user = auth()->user();
        Shipping::updateOrCreate(['user_id' => $user->id], $requestData);
        return ['res' => true, 'msg' => "Successfully updated your shipping", 'data' => ''];
    }

    /**
     * Update User
     *
     * @param array $requestData
     * @return array
     */
    public function paymentTermsRequest(array $requestData): array
    {
        $user = auth()->user();
        PaymentTerms::updateOrCreate(['user_id' => $user->id], $requestData);
        return ['res' => true, 'msg' => "Successfully Update Payment Terms.", 'data' => ''];
    }

    /**
     * Update User
     *
     * @param array $requestData
     * @return array
     */
    public function minOrder(array $requestData): array
    {
        $user = auth()->user();
        PaymentTerms::updateOrCreate(['user_id' => $user->id], $requestData);
        return ['res' => true, 'msg' => "Successfully Update Minimum Quantity.", 'data' => ''];
    }

    /**
     * Save image from base64 string.
     *
     * @param int $id
     * @param $file
     * @param $field_name
     * @param $previousFile
     * @param $replaceable
     * @return Stringable|string
     */
    private function uploadFile(int $id, $file, $field_name, $previousFile, $replaceable): Stringable|string
    {
        $userAbsPath = $this->userAbsPath.'/'.$id. "/";
        $userRelPath = $this->userRelPath.$id.'/';

        if (!file_exists($userAbsPath)) {
            mkdir($userAbsPath, 0777, true);
        }

        if ($replaceable && $previousFile !== null && file_exists(public_path()."/". $previousFile)) {
            $unlinkUrl = public_path() ."/". $previousFile;
            if (file_exists($unlinkUrl)) {
                unlink($unlinkUrl);
            }
        }
        $fileName = Str::random(10) . '_'.$field_name.'.' . $file->extension();
        $file->move($userAbsPath, $fileName);
        return $userRelPath . $fileName;
    }

}
