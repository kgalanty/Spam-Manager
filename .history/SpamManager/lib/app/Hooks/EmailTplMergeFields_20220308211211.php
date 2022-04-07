<?php

use WHMCS\Database\Capsule as DB;
return function ($vars) {
  $merge_fields = [];
  $merge_fields['server'] = "Server address email is to be sent";
  $merge_fields['service_domain'] = "Service domain";
  return $merge_fields;
};
