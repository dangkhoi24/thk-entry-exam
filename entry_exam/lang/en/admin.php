<?php

// Texts for the admin screens

return [

    'menu' => [
        'title' => 'Site management',
        'hotel_search' => 'Hotel search',
        'hotel_create' => 'Add hotel',
        'booking_search' => 'Booking search',
    ],

    'common' => [
        'search' => 'Search',
        'select_placeholder' => 'Please select',
        'date_placeholder' => 'Select a date',
        'no_data' => 'No data',
        'no_result' => 'No results found',
        'count' => '{1} :count result|[0,*] :count results',
        'changed' => 'Changed',
        'before_change' => 'Before: :value',
    ],

    'top' => [
        'title' => 'THK Holdings Vietnam Hanoi\'s entrance examination',
        'description' => 'This is a management screen for THK Holdings Vietnam Hanoi\'s entry examination.<br>Although it will be a management screen, a login feature has not been implemented this time to simplify testing. note that.',
    ],

    'hotel' => [
        'search_title' => 'Hotel search',
        'search_hint' => 'Enter a hotel name or select a prefecture to search',
        'create_title' => 'Add hotel',
        'edit_title' => 'Edit hotel',

        'field' => [
            'hotel_name' => 'Hotel name',
            'prefecture' => 'Prefecture',
            'hotel_image' => 'Hotel image',
            'created_at' => 'Created',
            'updated_at' => 'Updated',
        ],

        'placeholder' => [
            'hotel_name' => 'e.g. Business Hotel Tokyo A',
            'hotel_name_search' => 'Hotel name (partial match)',
        ],

        'image_hint' => 'jpeg, png or webp, up to 2MB',
        'image_keep_hint' => 'jpeg, png or webp, up to 2MB. The current image is kept if no file is selected',
        'current_image' => 'Current image',

        'action' => [
            'edit' => 'Edit',
            'delete' => 'Delete',
            'register' => 'Register',
            'save' => 'Save',
            'cancel' => 'Cancel',
            'update' => 'Update',
            'view_detail' => 'View hotel page',
            'back_to_search' => 'Back to hotel search',
        ],

        'confirm_title' => 'Confirm changes',
        'confirm_message' => 'The hotel will be updated as follows. Are you sure?',
        'no_changes' => 'Nothing has been changed',
        'delete_confirm' => "Delete hotel \":name\".\nAre you sure?",

        'message' => [
            'created' => 'Hotel ":name" has been registered',
            'updated' => 'Hotel ":name" has been updated',
            'deleted' => 'Hotel ":name" has been deleted',
            'not_found' => 'The hotel was not found',
            'has_bookings' => 'Hotel ":name" cannot be deleted because it has bookings',
            'edit_expired' => 'The edit could not be confirmed. Please try again',
        ],
    ],

    'booking' => [
        'search_title' => 'Booking search',
        'search_hint' => 'Enter search conditions to search',

        'field' => [
            'customer_name' => 'Customer name',
            'customer_contact' => 'Customer contact',
            'checkin_date' => 'Check-in date',
            'checkout_date' => 'Check-out date',
            'checkin_time' => 'Check-in',
            'checkout_time' => 'Check-out',
            'created_at' => 'Booked at',
            'updated_at' => 'Updated at',
        ],

        'placeholder' => [
            'customer_name' => 'Customer name (partial match)',
            'customer_contact' => 'Phone or email (partial match)',
        ],
    ],

    'pagination' => [
        'summary' => 'Showing :from–:to of :total',
        'previous' => 'Previous',
        'next' => 'Next',
    ],

    'locale' => [
        'ja' => '日本語',
        'en' => 'English',
    ],

];
