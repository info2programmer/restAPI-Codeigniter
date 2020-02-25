<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class UserApi extends CI_Controller {

    public function index()
    {
        echo "helloWorld";
    }

    public function user_post()
    {
      // header("Access-Control-Allow-Origin:  http://localhost:3000");
      header('Access-Control-Allow-Origin: *');
      header('Content-type: application/json');
      $json = file_get_contents('php://input');
      $obj = json_decode($json, true);
    //   $username = $obj['username'];
    //   $password = $obj['password'];
    
    // echo "<pre>";
    // print_r ($json);
    // echo "</pre>";
    // die;
        
      $fullName = $obj['fullName'];
      $email = $obj['email'];
      $phoneNumber = $obj['phoneNumber'];
      $dateOfBirth = $obj['dateOfBirth'];
  
     
  
      if (!empty($fullName) && !empty($email) && !empty($phoneNumber) && !empty($dateOfBirth)) {
        $data = array(
          'fullName' => $fullName,
          'email' => $email,
          'phoneNumber' => $phoneNumber,
          'dateOfBirth' => $dateOfBirth
        );
        $userId = $this->common_model->insert_record('users', $data);
        $userData = $this->common_model->find_data(['name' => 'users'], 'array');
        echo json_encode($userData);
      } else {
        echo json_encode(['status' => false, 'msg' => "All Fields Mandetory"]);
      }
    }

}

/* End of file UserApi.php */
