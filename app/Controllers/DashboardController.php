<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\GoogleAuth;
use App\Models\UserModel;


class DashboardController extends BaseController
{
    public function index()
    {
        if(!session()->has('google_token') && !session()->has('user_id')){
            return redirect()->to('auth/login');
        }
        try {
            $googleAuth = new GoogleAuth();
            $googleAuth->setAccessToken(session('google_token'));

            $calenderService = $googleAuth->getCalendarService();
            $now = date('c');

            // The upcoming events
            $events = $calenderService->events->listEvents('primary',[
                'timeMin'    => $now,
                'maxResults' =>10,
                'orderBy'    =>'startTime',
                'singleEvents' => true,
            ]);
            return view('dashboard',['events' => $events]);

        } catch (\Exception $e) {
            log_message('error', 'Google Calendar : ' . $e->getMessage());
            session()->remove('google_token');
            return redirect()->to('auth/login');      
        }
    }

    public function savePhone(){

        $rules = [
            'phone' => [
                'rules'  => 'required|regex_match[/^[0-9]{10,15}$/]',
                'errors' => [
                    'required'    => 'Phone number is required.',
                    'regex_match' => 'Please enter a valid phone number.'
                ]
            ]
        ];
        if(!$this->validate($rules)){
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        try{
            $phone = $this->request->getPost('phone');
            $phoneNumber = '+91' . $phone;
            session()->set('user_phone', $phoneNumber);
            $userId = session()->get('user_id');
            
            if($userId){
                $userModel = new UserModel();
                $update = $userModel->update($userId, ['phone' => $phoneNumber]);

                return redirect()->to('/dashboard')->with(
                    $update ? 'success' : 'error',
                    $update ? 'Phone number added successfully.' : 'Failed to add phone number.'
                );
            }
        }catch(\Exception $e){
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}
