<?php

date_default_timezone_set('Europe/London');

// require_once 'config.inc.php';
require_once 'DatabaseHandler.php';
require_once 'MailManager.php';

try
{
  $mm = new MailManager("dbhost.cs.man.ac.uk", "y66466tl", "SpagetiC0de", "2021_comp10120_x18");
  // $mm = new MailManager($database_host, $database_user, $database_pass, $database_name);

  $mm->set_subject('Test Email');
  $mm->add_recipient('ziggy.hughes@student.manchester.ac.uk');
  $mm->set_body('Test Body');

  $mm->send();

  print "test";
}
catch (Exception $e)
{
  print $e->getMessage() . "\n";
}
?>
