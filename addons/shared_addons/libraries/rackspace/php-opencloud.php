<?php

require_once SHARED_ADDONPATH . 'libraries/rackspace/vendor/autoload.php';

require_once 'SplClassLoader.php';
$classLoader = new SplClassLoader('OpenCloud', SHARED_ADDONPATH . 'libraries/rackspace/');
$classLoader->register();