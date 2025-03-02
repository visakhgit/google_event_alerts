<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\GoogleAuth;
use App\Models\UserModel;
use Google_Service_Oauth2;

class AuthController extends BaseController
{
    public function login()
    {
        $googleAuth = new GoogleAuth();
        return redirect()->to($googleAuth->generateAuthUrl());
    }

    public function callback(){

        $googleAuth = new GoogleAuth();
        $code = $this->request->getGet('code');

        if ($code) {
            $token = $googleAuth->authenticate($code);
            session()->set('google_token', $token);

            $client = new \Google_Client();
            $client->setAccessToken($token);

             //get userinfo
            $oauth2 = new Google_Service_Oauth2($client);
            $userInfo = $oauth2->userinfo->get();

            //save user details
            $userModel = new UserModel();
            $existUser = $userModel->where('email', $userInfo->email)->first();
            $userData = [
                'name'         => $userInfo->name,
                'email'        => $userInfo->email,
                'google_token' => json_encode($token)
            ];
            if($existUser){
                $userModel->update($existUser['id'], $userData);
                $userId = $existUser['id'];
            }else{
                $userId = $userModel->insert($userData);
            }
            session()->set('user_id', $userId);
            return redirect()->to('/dashboard');
            
        }else{
            log_message('error', 'Google authentication failed');
            return redirect()->to('/')->with('error', 'Authentication failed. Please try again.');
        }
    }

    public function logout(){
        session()->destroy('');
        return redirect()->to('/');
    }

}
