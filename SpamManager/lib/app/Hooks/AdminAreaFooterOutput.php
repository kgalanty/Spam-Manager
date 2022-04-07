<?php

use WHMCS\Database\Capsule as DB;
return function ($vars) {
  if($vars['filename'] == 'configemailtemplates')
  {
    $script = <<<EOT
 <script>$(function() {
  $('a:contains(spammanager_)').parents('tr').remove();
 });</script>
EOT;
    
      return $script;
  }

};
