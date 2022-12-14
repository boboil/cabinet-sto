<?php

return [

    /**
     * Path to a json file containing the credentials of a Google Service account.
     */
    'client_secret_json' => storage_path('app/laravel-google-calendar/client_secret.json'),

    /**
     *  The id of the Google Calendar that will be used by default.
     */
    'calendar_id' => 'goldmihailichenko@gmail.com',

    /**
     *  This is where google will save the credentials
     */
    'credentials_path' => storage_path('app/laravel-google-calendar/calendar.json'),
];
