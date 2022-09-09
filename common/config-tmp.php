<?php
define('PMS_SITE_TITLE', '');
define('PMS_TIME_ZONE', 'Europe/London');
define('PMS_DATE_FORMAT', '%F');
define('PMS_TIME_FORMAT', '%I:%M %P');
define('PMS_CURRENCY_ENABLED', '1');
define('PMS_CURRENCY_POS', 'before'); // before or after
define('PMS_LANG_ENABLED', '0');
define('PMS_ADMIN_LANG_FILE', 'en.ini');
define('PMS_ENABLE_COOKIES_NOTICE', '1');
define('PMS_MAINTENANCE_MODE', '0');
define('PMS_MAINTENANCE_MSG', '<h1><i class=\"fa fa-rocket\"></i> Coming soon...</h1><p>We are sorry, our website is down for maintenance.</p>');
define('PMS_TEMPLATE', 'default');
define('PMS_OWNER', '');
define('PMS_EMAIL', '');
define('PMS_ADDRESS', '');
define('PMS_PHONE', '');
define('PMS_MOBILE', '');
define('PMS_FAX', '');
define('PMS_DB_NAME', 'your_database_name');
define('PMS_DB_HOST', 'localhost');
define('PMS_DB_PORT', '3306');
define('PMS_DB_USER', 'your_database_user');
define('PMS_DB_PASS', 'your_database_password');
define('PMS_SENDER_EMAIL', '');
define('PMS_SENDER_NAME', '');
define('PMS_USE_SMTP', '0');
define('PMS_SMTP_SECURITY', '');
define('PMS_SMTP_AUTH', '0');
define('PMS_SMTP_HOST', '');
define('PMS_SMTP_USER', '');
define('PMS_SMTP_PASS', '');
define('PMS_SMTP_PORT', '25');
define('PMS_GMAPS_API_KEY', '');
define('PMS_ANALYTICS_CODE', '');
define('PMS_ADMIN_FOLDER', 'admin');
define('PMS_CAPTCHA_PKEY', ''); // ReCAPTCHA public key
define('PMS_CAPTCHA_SKEY', ''); // ReCAPTCHA secret key
define('PMS_AUTOGEOLOCATE', '0'); // Change the currency according to the country (https required)
define('PMS_PAYMENT_TYPE', 'arrival'); // 2checkout,paypal,check,arrival
define('PMS_PAYPAL_EMAIL', '');
define('PMS_VENDOR_ID', ''); // 2Checkout.com account number
define('PMS_SECRET_WORD', ''); // 2Checkout.com secret word
define('PMS_PAYMENT_TEST_MODE', '1');
define('PMS_ENABLE_DOWN_PAYMENT', '1');
define('PMS_DOWN_PAYMENT_RATE', '30'); // %
define('PMS_DOWN_PAYMENT_AMOUNT', '50'); // amount required to activate the down payment
define('PMS_ALLOW_COMMENTS', '1');
define('PMS_ALLOW_RATINGS', '1'); // If comments enabled
define('PMS_ENABLE_BOOKING_REQUESTS', '0'); // Possibility to make a reservation request if no availability
define('PMS_BRAINTREE_MERCHANT_ID', '');
define('PMS_BRAINTREE_PUBLIC_KEY', '');
define('PMS_BRAINTREE_PRIVATE_KEY', '');
define('PMS_CURRENCY_CONVERTER_KEY', ''); // currencyconverterapi.com API key
define('PMS_RAZORPAY_KEY_ID', '');
define('PMS_RAZORPAY_KEY_SECRET', '');
define('PMS_ENABLE_ICAL', '1');
define('PMS_ENABLE_AUTO_ICAL_SYNC', '1');
define('PMS_ICAL_SYNC_INTERVAL', 'daily'); // daily | hourly
define('PMS_ICAL_SYNC_CLOCK', '3'); // 0-23h mode, required if PMS_ICAL_SYNC_INTERVAL = daily
