<?php

defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/RestController.php';

class Example extends RestController
{

  public function user_get($id = 0)
  {
    $users = array(
      'userName' => 'Saikat Bhadury',
      'id' => 1
    );

    $this->response($users, RestController::HTTP_OK);
  }
}

/* End of file Example.php */
