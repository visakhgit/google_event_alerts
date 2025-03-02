# Google Calendar Event Alert

This CodeIgniter 4 application allows users to log in with Google, view their Google Calendar events, update their phone number for notifications, and receive automated voice call reminders via Twilio.

## Requirements

- PHP 7.3 or later
- Composer
- MySQL/MariaDB (or another supported database)
- A Google Cloud Project with Calendar API enabled
- A Twilio account

## Installation

1. **Download/Clone the Project:**

   Share the project via Google Drive. The recipient should download and extract the project files.

2. **Install Dependencies:**
   Open a terminal in the project root  
   -> run:composer install --prefer-dist
   note :it may take a while, google/apiclient is relatively heavy.(handles authentication + Google Calendar API)
   
3 Environment Setup:

	[]Rename envexample to .env and update it with your own database and Twilio credentials. Also,
	rename credential.json to credentials.json and 

	[]Replace the contents of credentials.json inside writable with your Google API credentials."

	database.default.hostname   = localhost
	database.default.database   = your_database_name
	database.default.username   = your_db_username
	database.default.password   = your_db_password
	database.default.DBDriver   = MySQLi

	[]Also add your Twilio credentials:

	TWILIO_SID=your_twilio_sid
	TWILIO_AUTH_TOKEN=your_twilio_auth_token
	TWILIO_PHONE_NUMBER=your_twilio_phone_number

5.Database Migration:

Run the migrations to set up the database tables:
php spark migrate

6.Google API Setup

	Google Cloud Console:

	[]Create a project and enable the Calendar API.
	[]Set up the OAuth consent screen.
	[]Create OAuth 2.0 Client ID credentials.
	[]Set the redirect URI to: http://your-domain/auth/callback
	[]Download the credentials.json file and place it in the writable/ directory.

    info : Library Setup: The project includes a library at app/Libraries/GoogleAuth.php
    ( google/apiclient)

    Twilio Setup
    []Twilio Account:Sign up for a Twilio account and obtain your Account SID, Auth Token, and a verified Twilio phone number.
    []Library Setup:The project includes a Twilio library (app/Libraries/TwilioService.php) that uses the Twilio PHP SDK.
    ( twilio/sdk)

8.Run the built-in server
	php spark serve

9. Add the phone number for twilio voice notification 

10.You can run the cron job manually with:
	php spark cron:checkEvents