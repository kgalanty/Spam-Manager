<?php

use WHMCS\Database\Capsule as DB;
return function ($vars) {
  $merge_fields = [];
  $merge_fields['my_custom_var'] = "My Custom Var";
  $merge_fields['my_custom_var2'] = "My Custom Var2";
  return $merge_fields;
};
