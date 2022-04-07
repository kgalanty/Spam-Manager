<?php

use WHMCS\Database\Capsule as DB;
return function ($vars) {
  $merge_fields = [];
  $merge_fields['server'] = "Server address email is to be sent";
  $merge_fields['my_custom_var2'] = "My Custom Var2";
  return $merge_fields;
};
