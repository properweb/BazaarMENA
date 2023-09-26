<?php

namespace Modules\User\Http\Controllers;

use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\User\Http\Requests\RegistrationRequest;
use Modules\User\Http\Requests\LoginUserRequest;
use Modules\User\Http\Requests\ResetPasswordRequest;
use Modules\User\Http\Services\UserService;
use App\Mail\VerificationMail;
use Illuminate\Support\Facades\Mail;


class UserController extends Controller
{
    private $userAbsPath = "";

    private $userRelPath = "";

    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        $this->userAbsPath = public_path('uploads/users');
        $this->userRelPath = 'uploads/users/';
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {

        return view('user::index');
    }
    /**
     * Store a newly created brand in storage
     *
     */
    public function sendEmail()
    {
        $content = [
            'subject' => 'This is the mail subject',
            'body' => 'This is the email body of how to send email from laravel 10 with mailtrap.'
        ];

        Mail::to('saha.atanu1984@gmail.com')->send(new VerificationMail($content));

        return "Email has been sent.";

//        $to = 'jahangir@properbounce.com';
//
//        // Subject of the email
//        $subject = 'Test Email';
//
//        // Message body
//        $message = 'This is a test email sent from Laravel using custom headers.';
//
//        // Additional headers
//        $headers = [
//            'From' => 'info@bazarcenter.ca',
//            'Reply-To' => 'sender@example.com',
//            'CC' => 'cc@example.com',
//            'BCC' => 'bcc@example.com',
//            'MIME-Version' => '1.0',
//            'Content-type' => 'text/html; charset=iso-8859-1',
//        ];
//
//        // Construct headers as a string
//        $headersString = '';
//        foreach ($headers as $key => $value) {
//            $headersString .= $key . ': ' . $value . "\r\n";
//        }
//
//        // Send the email using the mail() function
//        $mailSent = mail($to, $subject, $message, $headersString);
//
//        if ($mailSent) {
//            return 'Email sent successfully using custom function!';
//        } else {
//            return 'Email delivery failed.';
//        }


    }
    /**
     * Store a newly created brand in storage
     *
     * @param RegistrationRequest $request
     * @return JsonResponse
     */
    public function signUp(RegistrationRequest $request): JsonResponse
    {
        $response = $this->userService->signUp($request->validated());

        return response()->json($response);
    }

    /**
     * Sign in request by user.
     *
     * @param LoginUserRequest $request
     * @return JsonResponse
     */
    public function login(LoginUserRequest $request): JsonResponse
    {
        $response = $this->userService->login($request->validated());

        return response()->json($response);
    }

    /**
     * Get User Details
     *
     * @return JsonResponse
     */
    public function getUserDetails(): JsonResponse
    {
        $user = auth()->user();
        $response = $this->userService->getUserDetails($user->id);
        return response()->json($response);
    }

}
