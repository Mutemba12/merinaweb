<?php
require_once('lib/Braintree.php');

Braintree_Configuration::environment((PMS_PAYMENT_TEST_MODE == 1 ? 'sandbox' : 'production'));
Braintree_Configuration::merchantId(PMS_BRAINTREE_MERCHANT_ID);
Braintree_Configuration::publicKey(PMS_BRAINTREE_PUBLIC_KEY);
Braintree_Configuration::privateKey(PMS_BRAINTREE_PRIVATE_KEY);
