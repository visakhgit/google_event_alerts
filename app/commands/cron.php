<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Libraries\GoogleAuth;
use App\Libraries\TwilioService;
use App\Models\UserModel;

class Cron extends BaseCommand
{
    protected $group       = 'cron';
    protected $name        = 'cron:checkEvents';
    protected $description = 'Checks Google Calendar events and sends Twilio reminders.';

    public function run(array $params)
    {
        CLI::write('Starting cron job...', 'yellow');
        $userModel = new UserModel();

        //fetch all the authenticated users 
        $users =  $userModel->where('google_token !=', null)
                    ->where('phone!=',  null)
                    ->findAll();

        if(empty($users)){
            CLI::write('No users found', 'red');
            return;
        }

        foreach($users as $user){
            $googleAuth = new GoogleAuth();
            // Decode the stored Google access token
            $token = json_decode($user['google_token'], true);
            // CLI::write("token: " . print_r($token,true), 'red');
            $googleAuth->setAccessToken($token);

            $calendarService = $googleAuth->getCalendarService();
            $now = date('c');
            $future = date('c', strtotime('+5 minutes'));
            try{
                $events = $calendarService->events->listEvents('primary', [
                    'timeMin' => $now,
                    'timeMax' => $future,
                    'singleEvents' => true,
                    'orderBy' => 'startTime'
                ]);
            }catch(\Exception $e){
                CLI::write("Error fetching events for user {$user['email']}: " . $e->getMessage(), 'red');
                continue;
            }
            // Initialize Twilio service
            $twilio = new TwilioService();
            $phoneNumber = $user['phone'];

            // Loop through each event and send a reminder
            foreach ($events->getItems() as $event) {
                $message = "Reminder: You have an event '{$event->getSummary()}' at {$event->getStart()->dateTime}";
                try{
                    $twilio->makeCall($phoneNumber, $message);
                    CLI::write("Sent reminder: $message", 'green');
                }catch(\Exception $e){
                    CLI::write("Error sending Twilio call to {$user['email']}: " . $e->getMessage(), 'red');
                }
            }
            CLI::write('Cron job completed!', 'green');

        }

    }
}
