<?php

return [


    'available_days'   => [
        'monday'       => 'monday',
        'tuesday'        => 'tuesday',
        'wednesday'        => 'wednesday',
        'thursday'  => 'thursday',
        'friday' => 'friday',
        'saturday' => 'saturday',
        'sunday' => 'sunday',
    ],

    'whatsapp_error' => [
        [
            'code' => 0,
            'description' => 'AuthException: We were unable to authenticate the app user.',
            'possible_solutions' => 'Typically this means the included access token has expired, been invalidated, or the app user has changed a setting to prevent all apps from accessing their data. We recommend that you get a new access token.',
            'http_status_code' => 401,
        ],
        [
            'code' => 3,
            'description' => 'API Method: Capability or permissions issue.',
            'possible_solutions' => 'Use the access token debugger to verify that your app has been granted the permissions required by the endpoint. See Troubleshooting.',
            'http_status_code' => 500,
        ],
        [
            'code' => 10,
            'description' => 'Permission Denied: Permission is either not granted or has been removed.',
            'possible_solutions' => 'Use the access token debugger to verify that your app has been granted the permissions required by the endpoint. See Troubleshooting. Ensure that the phone number used to set the business public key is allowlisted.',
            'http_status_code' => 403,
        ],
        [
            'code' => 190,
            'description' => 'Access token has expired: Your access token has expired.',
            'possible_solutions' => 'Get a new access token.',
            'http_status_code' => 401,
        ],
        [
            'code' => '200-299',
            'description' => 'API Permission: Permission is either not granted or has been removed.',
            'possible_solutions' => 'Use the access token debugger to verify that your app has been granted the permissions required by the endpoint. See Troubleshooting.',
            'http_status_code' => 403,
        ],
        [
            'code' => 4,
            'description' => 'API Too Many Calls: The app has reached its API call rate limit.',
            'possible_solutions' => 'Load the app in the App Dashboard and view the Application Rate Limit section to verify that the app has reached its rate limit. If it has, try again later or reduce the frequency or amount of API queries the app is making.',
            'http_status_code' => 400,
        ],
        [
            'code' => 80007,
            'description' => 'Rate limit issues: The WhatsApp Business Account has reached its rate limit.',
            'possible_solutions' => 'See WhatsApp Business Account Rate Limits. Try again later or reduce the frequency or amount of API queries the app is making.',
            'http_status_code' => 400,
        ],
        [
            'code' => 130429,
            'description' => 'Rate limit hit: Cloud API message throughput has been reached.',
            'possible_solutions' => 'The app has reached the API\'s throughput limit. See Throughput. Try again later or reduce the frequency with which the app sends messages.',
            'http_status_code' => 400,
        ],
        [
            'code' => 131048,
            'description' => 'Spam rate limit hit: Message failed to send because there are restrictions on how many messages can be sent from this phone number.',
            'possible_solutions' => 'Check your quality status in the WhatsApp Manager and see the Quality-Based Rate Limits documentation for more information.',
            'http_status_code' => 400,
        ],
        [
            'code' => 131056,
            'description' => '(Business Account, Consumer Account) pair rate limit hit: Too many messages sent from the sender phone number to the same recipient phone number in a short period of time.',
            'possible_solutions' => 'Wait and retry the operation, if you intend to send messages to the same phone number. You can still send messages to a different phone number without waiting.',
            'http_status_code' => 400,
        ],
        [
            'code' => 133016,
            'description' => 'Account register deregister rate limit exceeded: Registration or Deregistration failed because there were too many attempts for this phone number in a short period of time.',
            'possible_solutions' => 'The business phone number is being blocked because it has reached its registration/deregistration attempt limit. Try again once the number is unblocked. See "Limitations" in the Registration document.',
            'http_status_code' => 400,
        ],
        [
            'code' => 368,
            'description' => 'Temporarily blocked for policies violations: The WhatsApp Business Account associated with the app has been restricted or disabled for violating a platform policy.',
            'possible_solutions' => 'See the Policy Enforcement document to learn about policy violations and how to resolve them.',
            'http_status_code' => 403,
        ],
        [
            'code' => 131031,
            'description' => 'Account has been locked: The WhatsApp Business Account associated with the app has been restricted or disabled for violating a platform policy, or we were unable to verify data included in the request against data set on the WhatsApp Business Account.',
            'possible_solutions' => 'See the Policy Enforcement document to learn about policy violations and how to resolve them. You can also use the Health Status API, which may provide additional insight into the reason or reasons for the account lock.',
            'http_status_code' => 403,
        ],
        [
            'code' => 1,
            'description' => 'API Unknown: Invalid request or possible server error.',
            'possible_solutions' => 'Check the WhatsApp Business Platform Status page to see API status information. If there are no server outages, check the endpoint reference and verify that your request is formatted correctly and meets all endpoint requirements.',
            'http_status_code' => 400,
        ],
        [
            'code' => 2,
            'description' => 'API Service: Temporary due to downtime or due to being overloaded.',
            'possible_solutions' => 'Check the WhatsApp Business Platform Status page to see API status information before trying again.',
            'http_status_code' => 503,
        ],
        [
            'code' => 33,
            'description' => 'Parameter value is not valid: The business phone number has been deleted.',
            'possible_solutions' => 'Verify that the business phone number is correct.',
            'http_status_code' => 400,
        ],
        [
            'code' => 100,
            'description' => 'Invalid parameter: The request included one or more unsupported or misspelled parameters.',
            'possible_solutions' => 'See the endpoint\'s reference to determine which parameters are supported and how they are spelled. Ensure when setting the business public key, it is a valid 2048-bit RSA public key in PEM format. Ensure there is no mismatch between the phone number id you are registering and a previously stored phone number id.',
            'http_status_code' => 400,
        ],
        [
            'code' => 130472,
            'description' => 'User\'s number is part of an experiment: Message was not sent as part of an experiment.',
            'possible_solutions' => 'See Marketing Message Experiment.',
            'http_status_code' => 400,
        ],
        [
            'code' => 131000,
            'description' => 'Something went wrong: Message failed to send due to an unknown error. When setting a business public key, it either failed to calculate the signature, call the GraphQL endpoint, or the GraphQL endpoint returned an error.',
            'possible_solutions' => 'Try again. If the error persists, open a Direct Support ticket.',
            'http_status_code' => 500,
        ],
        [
            'code' => 131005,
            'description' => 'Access denied: Permission is either not granted or has been removed.',
            'possible_solutions' => 'Use the access token debugger to verify that your app has been granted the permissions required by the endpoint. See Troubleshooting.',
            'http_status_code' => 403,
        ],
        [
            'code' => 131008,
            'description' => 'Required parameter is missing: The request is missing a required parameter.',
            'possible_solutions' => 'See the endpoint\'s reference to determine which parameters are required.',
            'http_status_code' => 400,
        ],
        [
            'code' => 131009,
            'description' => 'Parameter value is not valid: One or more parameter values are invalid.',
            'possible_solutions' => 'See the endpoint\'s reference to determine which values are supported for each parameter, and see Phone Numbers to learn how to add a phone number to a WhatsApp Business Account.',
            'http_status_code' => 400,
        ],
        [
            'code' => 131016,
            'description' => 'Service unavailable: A service is temporarily unavailable.',
            'possible_solutions' => 'Check the WhatsApp Business Platform Status page to see API status information before trying again.',
            'http_status_code' => 500,
        ],
        [
            'code' => 131021,
            'description' => 'Recipient cannot be sender: Sender and recipient phone number is the same.',
            'possible_solutions' => 'Send a message to a phone number different from the sender.',
            'http_status_code' => 400,
        ],
        [
            'code' => 131026,
            'description' => 'Message Undeliverable: Unable to deliver message.',
            'possible_solutions' => 'Reasons can include: The recipient phone number is not a WhatsApp phone number. Sending an authentication template to a WhatsApp user who has a +91 country calling code (India). Authentication templates currently cannot be sent to WhatsApp users in India. Recipient has not accepted our new Terms of Service and Privacy Policy. Recipient using an old WhatsApp version. The message was not delivered to create a high quality user experience. Using a non-WhatsApp communication method, ask the WhatsApp user to: Confirm that they can actually send a message to your WhatsApp business phone number. Confirm that your WhatsApp business phone number is not in their list of blocked numbers. Confirm that they have accepted our latest Terms of Service. Update to the latest version of the WhatsApp client.',
            'http_status_code' => 400,
        ],
        [
            'code' => 131042,
            'description' => 'Business eligibility payment issue: There was an error related to your payment method. See About Billing For Your WhatsApp Business Account and verify that you have set up billing correctly. Common problems: Payment account is not attached to a WhatsApp Business Account, Credit line is over the limit, Credit line (Payment Account) not set or active, WhatsApp Business Account is deleted, WhatsApp Business Account is suspended, Timezone not set, Currency not set, MessagingFor request (On Behalf Of) is pending or declined, Exceeded conversation free tier threshold without a valid payment method.',
            'possible_solutions' => '',
            'http_status_code' => 400,
        ],
        [
            'code' => 131045,
            'description' => 'Incorrect certificate: Message failed to send due to a phone number registration error. Register the phone number before trying again.',
            'possible_solutions' => '',
            'http_status_code' => 500,
        ],
        [
            'code' => 131047,
            'description' => 'Re-engagement message: More than 24 hours have passed since the recipient last replied to the sender number. Send the recipient a business-initiated message using a message template instead.',
            'possible_solutions' => '',
            'http_status_code' => 400,
        ],
        [
            'code' => 131051,
            'description' => 'Unsupported message type: Unsupported message type. See Messages for supported message types before trying again with a supported message type.',
            'possible_solutions' => '',
            'http_status_code' => 400,
        ],
        [
            'code' => 131052,
            'description' => 'Media download error: Unable to download the media sent by the user. We were unable to download the media for one or more reasons, such as an unsupported media type. Ask the WhatsApp user to send you the media file using a non-WhatsApp method.',
            'possible_solutions' => '',
            'http_status_code' => 400,
        ],
        [
            'code' => 131053,
            'description' => 'Media upload error: Unable to upload the media used in the message. We were unable to upload the media for one or more reasons, such as an unsupported media type. Refer to the error.error_data.details value for more information about why we were unable to upload the media. We recommend that you inspect any media files that are causing errors and confirm that they are in fact supported.',
            'possible_solutions' => 'For more reliable performance when sending media, refer to Media HTTP Caching and uploading the media.',
            'http_status_code' => 400,
        ],
        [
            'code' => 131057,
            'description' => 'Account in maintenance mode: WhatsApp Business Account is in maintenance mode. One reason for this could be that the account is undergoing a throughput upgrade.',
            'possible_solutions' => '',
            'http_status_code' => 500,
        ],
        [
            'code' => 132000,
            'description' => 'Template Param Count Mismatch: The number of variable parameter values included in the request did not match the number of variable parameters defined in the template.',
            'possible_solutions' => 'See Message Template Guidelines and make sure the request includes all of the variable parameter values that have been defined in the template.',
            'http_status_code' => 400,
        ],
        [
            'code' => 132001,
            'description' => 'Template does not exist: The template does not exist in the specified language or the template has not been approved.',
            'possible_solutions' => 'Make sure your template has been approved and the template name and language locale are correct. Please ensure you follow message template guidelines.',
            'http_status_code' => 404,
        ],
        [
            'code' => 132005,
            'description' => 'Template Hydrated Text Too Long: Translated text is too long. Check the WhatsApp Manager to verify that your template has been translated. See Quality Rating and Template Status.',
            'possible_solutions' => '',
            'http_status_code' => 400,
        ],
        [
            'code' => 132007,
            'description' => 'Template Format Character Policy Violated: Template content violates a WhatsApp policy. See Rejection Reasons to determine possible reasons for violation.',
            'possible_solutions' => '',
            'http_status_code' => 400,
        ],
        [
            'code' => 132012,
            'description' => 'Template Parameter Format Mismatch: Variable parameter values formatted incorrectly. The variable parameter values included in the request are not using the format specified in the template. See Message Template Guidelines.',
            'possible_solutions' => '',
            'http_status_code' => 400,
        ],
        [
            'code' => 132015,
            'description' => 'Template is Paused: Template is paused due to low quality so it cannot be sent in a template message. Edit the template to improve its quality and try again once it is approved.',
            'possible_solutions' => '',
            'http_status_code' => 400,
        ],
        [
            'code' => 132016,
            'description' => 'Template is Disabled: Template has been paused too many times due to low quality and is now permanently disabled. Create a new template with different content.',
            'possible_solutions' => '',
            'http_status_code' => 400,
        ],
        [
            'code' => 132068,
            'description' => 'Flow is blocked: Flow is in blocked state. Correct the Flow.',
            'possible_solutions' => '',
            'http_status_code' => 400,
        ],
        [
            'code' => 132069,
            'description' => 'Flow is throttled: Flow is in throttled state and 10 messages using this flow were already sent in the last hour. Correct the Flow.',
            'possible_solutions' => '',
            'http_status_code' => 400,
        ],
        [
            'code' => 133000,
            'description' => 'Incomplete Deregistration: A previous deregistration attempt failed. Deregister the number again before registering.',
            'possible_solutions' => '',
            'http_status_code' => 500,
        ],
        [
            'code' => 133004,
            'description' => 'Server Temporarily Unavailable: Server is temporarily unavailable. Check the WhatsApp Business Platform Status page to see API status information and check the response details value before trying again.',
            'possible_solutions' => '',
            'http_status_code' => 503,
        ],
        [
            'code' => 133005,
            'description' => 'Two step verification PIN Mismatch: Two-step verification PIN incorrect. Verify that the two-step verification PIN included in the request is correct.',
            'possible_solutions' => 'To reset the two-step verification PIN: Disable two-step verification. Send a POST request that includes the new PIN to the Phone Number endpoint.',
            'http_status_code' => 400,
        ],
        [
            'code' => 133006,
            'description' => 'Phone number re-verification needed: Phone number needs to be verified before registering. Verify the phone number before registering it.',
            'possible_solutions' => '',
            'http_status_code' => 400,
        ],
        [
            'code' => 133008,
            'description' => 'Too Many two step verification PIN Guesses: Too many two-step verification PIN guesses for this phone number. Try again after the amount of time specified in the details response value.',
            'possible_solutions' => '',
            'http_status_code' => 400,
        ],
        [
            'code' => 133009,
            'description' => 'Two step verification PIN Guessed Too Fast: Two-step verification PIN was entered too quickly. Check the details response value before trying again.',
            'possible_solutions' => '',
            'http_status_code' => 400,
        ],
        [
            'code' => 133010,
            'description' => 'Phone number Not Registered: Phone number not registered on the WhatsApp Business Platform. Register the phone number before trying again.',
            'possible_solutions' => '',
            'http_status_code' => 400,
        ],
        [
            'code' => 133015,
            'description' => 'Please wait a few minutes before attempting to register this phone number: The phone number you are attempting to register was recently deleted, and deletion has not yet completed. Wait 5 minutes before re-trying the request.',
            'possible_solutions' => '',
            'http_status_code' => 400,
        ],
        [
            'code' => 135000,
            'description' => 'Generic user error: Message failed to send because of an unknown error with your request parameters. See the endpoint\'s reference to determine if you are querying the endpoint using the correct syntax. Contact customer support if you continue receiving this error code in response.',
            'possible_solutions' => '',
            'http_status_code' => 400,
        ],
    ],



    'whatsapp_required_scopes'   => [
        'whatsapp_business_management',
        'whatsapp_business_messaging',
        'public_profile'

    ],


    'telegram_required_scopes'   => [
        'can_join_groups',
        // 'can_read_all_group_messages',
        // 'supports_inline_queries'

    ],


    'stop_campaign_errors' => [
        0, // AuthException
        3, // Capability or permissions issue
        10, // Permission Denied
        190, // Access token has expired
        4, // API Too Many Calls
        80007, // Rate limit issues
        130429, // Rate limit hit: Cloud API message throughput has been reached
        131048, // Spam rate limit hit
        131056, // (Business Account, Consumer Account) pair rate limit hit
        133016, // Account register deregister rate limit exceeded
        368, // Temporarily blocked for policies violations
        131031, // Account has been locked
        1, // API Unknown
        2, // API Service
        131042, // Business eligibility payment issue
        131045, // Incorrect certificate
        131047, // Re-engagement message
        131051, // Unsupported message type
        131052, // Media download error
        131053, // Media upload error
        131057, // Account in maintenance mode
        132000, // Template Param Count Mismatch
        132001, // Template does not exist
        132005, // Template Hydrated Text Too Long
        132007, // Template Format Character Policy Violated
        132012, // Template Parameter Format Mismatch
        132015, // Template is Paused
        132016, // Template is Disabled
        132068, // Flow is blocked
        132069, // Flow is throttled
        133000, // Incomplete Deregistration
        133004, // Server Temporarily Unavailable
        133005, // Two step verification PIN Mismatch
        133006, // Phone number re-verification needed
        133008, // Too Many two step verification PIN Guesses
        133009, // Two step verification PIN Guessed Too Fast
        133010, // Phone number Not Registered
        133015, // Please wait a few minutes before attempting to register this phone number
        135000, // Generic user error
    ],

    'whatsapp_supported_languages' => [
        "af" => "Afrikaans",
        "sq" => "Albanian",
        "ar" => "Arabic",
        "az" => "Azerbaijani",
        "bn" => "Bengali",
        "bg" => "Bulgarian",
        "ca" => "Catalan",
        "zh_CN" => "Chinese (CHN)",
        "zh_HK" => "Chinese (HKG)",
        "zh_TW" => "Chinese (TAI)",
        "hr" => "Croatian",
        "cs" => "Czech",
        "da" => "Danish",
        "nl" => "Dutch",
        "en" => "English",
        "en_GB" => "English (UK)",
        "en_US" => "English (US)",
        "et" => "Estonian",
        "fil" => "Filipino",
        "fi" => "Finnish",
        "fr" => "French",
        "de" => "German",
        "el" => "Greek",
        "gu" => "Gujarati",
        "ha" => "Hausa",
        "he" => "Hebrew",
        "hi" => "Hindi",
        "hu" => "Hungarian",
        "id" => "Indonesian",
        "ga" => "Irish",
        "it" => "Italian",
        "ja" => "Japanese",
        "kn" => "Kannada",
        "kk" => "Kazakh",
        "ko" => "Korean",
        "lo" => "Lao",
        "lv" => "Latvian",
        "lt" => "Lithuanian",
        "mk" => "Macedonian",
        "ms" => "Malay",
        "ml" => "Malayalam",
        "mr" => "Marathi",
        "nb" => "Norwegian",
        "fa" => "Persian",
        "pl" => "Polish",
        "pt_BR" => "Portuguese (BR)",
        "pt_PT" => "Portuguese (POR)",
        "pa" => "Punjabi",
        "ro" => "Romanian",
        "ru" => "Russian",
        "sr" => "Serbian",
        "sk" => "Slovak",
        "sl" => "Slovenian",
        "es" => "Spanish",
        "es_AR" => "Spanish (ARG)",
        "es_ES" => "Spanish (SPA)",
        "es_MX" => "Spanish (MEX)",
        "sw" => "Swahili",
        "sv" => "Swedish",
        "ta" => "Tamil",
        "te" => "Telugu",
        "th" => "Thai",
        "tr" => "Turkish",
        "uk" => "Ukrainian",
        "ur" => "Urdu",
        "uz" => "Uzbek",
        "vi" => "Vietnamese",
        "zu" => "Zulu"
    ],

    'widget_default_settings'   => [
        'enable_box'          => 1,
        'box_position'        => 'bottom-right',
        'layout'              => 'button',
        'visibility'          => 'readonly',
        'type'                => 'phone',
        'devices'             => 'all',
        'welcome_message'     => 'Welcome to our live chat support! How can we assist you today?',
        'offline_message'     => 'Our support team is currently offline. Please leave a message, and we will get back to you soon.',
        'header_title'        => 'How can I help you?',
        'header_subtitle'     => 'Click one of our contacts below to chat on WhatsApp',
        'header_media'        => '',
        'footer_text'         => 'Powered by <a target="_BLANK" href="">Salebot</a>',
        'font_family'         => 'inherit',
        'animation'           => 'none',
        'auto_open'           => '1',
        'auto_open_delay'     => '1000',
        'animation_delay'     => '1000',
        'font_size'           => '16',
        'rounded_border'      => 1,
        'background_color'    => '#f1f5fd',
        'header_background_color' => '#095e54',
        'background_image'    => '',
        'schedule_from'       => '',
        'schedule_to'         => '',
        'text_color'          => '#404252',
        'icon_size'           => '20',
        'icon_font_size'      => '20',
        'label_color'         => '#6d6d6d',
        'name_color'          => '#6d6d6d',
        'availability_color'  => '#26c281',
        'button_text'         => 'How can I help you?',
        'timezone'            => null,
        'type'                => 'phone',
        'visibility'          => 'readonly',
        'available_days'      => config('static_array.available_days'),
    ],
    'client_permissions'   => [
        'manage_whatsapp'       => "manage_whatsapp",
        'manage_telegram'       => "manage_telegram",
        'manage_ai_writer'      => "manage_ai_writer",
        'manage_team'           => "manage_team",
        'manage_chat'           => "manage_chat",
        'manage_setting'        => "manage_setting",
        'manage_campaigns'      => "manage_campaigns",
        'manage_ticket'         => "manage_ticket",
        'manage_widget'         => "manage_widget",
        'manage_template'       => "manage_template",
        // 'manage_permissions'    => "manage_permissions",
    ],
    'font_family'   => [
        'inherit'       => "inherit",
        'Arial'       => "Arial",
        'Verdana'      => "Verdana",
        'Helvetica'           => "Helvetica",
        'Tahoma'           => "Tahoma",
        'Trebuchet MS'        => "Trebuchet MS",
        'Times New Roman'      => "Times New Roman",
        'Georgia'         => "Georgia",
        'Garamond'         => "Garamond",
        'Courier New'       => "Courier New",
        'Brush Script MT'       => "Brush Script MT",
        'Calibri'       => "Calibri",
    ],

    'lead_rating' => [
        1 => 'Low',
        2 => 'Average',
        3 => 'Potential',
        4 => 'Super Potential',
    ],
    'animation'   => [
        'none'       => "none",
        'bounce'       => "bounce",
        'flash'      => "flash",
        'pulse'           => "pulse",
        'shakeY'           => "shakeY",
        'shakeX'        => "shakeX",

    ],

    'telegram_scopes'   => [
        'can_be_edited',
        'can_manage_chat',
        'can_change_info',
        'can_delete_messages',
        'can_invite_users',
        'can_restrict_members',
        'can_pin_messages',
        'can_manage_topics',
        'can_promote_members',
        'can_manage_video_chats',
        'can_post_stories',
        'can_edit_stories',
        'can_delete_stories',
        'is_anonymous',
        'can_manage_voice_chats',
    ],





];
