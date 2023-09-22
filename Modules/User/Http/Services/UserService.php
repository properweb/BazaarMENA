<?php

namespace Modules\User\Http\Services;

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


class UserService
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
     * Save a new User
     *
     * @param array $requestData
     * @return array
     */
    public function signUp(array $requestData): array
    {
        try{
            if($requestData["role"] == ""){
                $requestData["role"] = User :: ROLE_RETAILER;
            }

            //$requestData["verified"] = 1;
            $user = $this->createUser($requestData);

            $userAbsPath = $this->userAbsPath.'/'.$user->id. "/";
            $userRelPath = $this->userRelPath.$user->id.'/';

            if (!File::exists($userAbsPath)) {
                mkdir($userAbsPath, 0777, true);
            }
            if ($requestData['letter_of_incorporation']) {
                $userData = array();
                $file = $requestData['letter_of_incorporation'];
                $fileName = Str::random(10) . '_letter_of_incorporation.' . $file->extension();
                $file->move($userAbsPath, $fileName);
                $userData['letter_of_incorporation'] = $userRelPath . $fileName;
                $userData['id'] = $user->id;
                $data = $this->updateUserDoc($userData, 'letter_of_incorporation');
            }
            if ($requestData['logo']) {
                $userData = array();
                $file = $requestData['logo'];
                $fileName = Str::random(10) . '_logo.' . $file->extension();
                $file->move($userAbsPath, $fileName);
                $userData['logo'] = $userRelPath . $fileName;
                $userData['id'] = $user->id;
                $data = $this->updateUserDoc($userData, 'logo');
            }

            $user = User::where('id', $user->id)->first();
            $response = ['res' => true,'msg' => '','data' => $user];
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

    /**
     * Create a new User
     *
     * @param array $userData
     * @return User
     */
    public function createUser(array $userData): User
    {
        $userData["password"] = Hash::make($userData['password']);
        //create User
        $user = new User();
        $user->fill($userData);
        $user->save();
        return $user;
    }

    /**
     * Update User
     *
     * @param array $userData
     * @param $columnName
     * @return User
     */
    public function updateUserDoc(array $userData, $columnName): User
    {
        return User::updateOrCreate(['id' => $userData['id']], [$columnName => $userData[$columnName]]);
    }

    /**
     * Get User Details
     * @param $id
     * @return array
     */
    public function getUserDetails($id): array
    {
        return User::where('id', $id)->get()->toArray();
    }

    /**
     * Sign In a user
     *
     * @param array $requestData
     * @return array
     */
    public function login(array $requestData): array
    {
        if (Auth::attempt(["email" => $requestData['email'], "password" => $requestData['password']])) {
            $stepCount = 0;
            $brandData = [];
            $user = Auth::user();
            $token = $user->createToken('bazarMenaAuth')->accessToken;
            $data = array(
                "id" => $user->id,
                "first_name" => $user->first_name,
                "last_name" => $user->last_name,
                "role" => $user->role,
                "verified" => $user->email_verified_at,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            );
            return [
                'res' => true,
                'msg' => '',
                'data' => $data
            ];
        } else {
            return [
                'res' => false,
                'msg' => 'Your password is wrong!',
                'data' => ""
            ];
        }
    }

}
