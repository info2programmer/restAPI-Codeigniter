<?php

defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/RestController.php';

class Example extends RestController
{

  // $table,$return_type='array',$list=NULL,$conditions='',$select='*',$join='',$group_by='',$order_by='',$limit=0,$offset=0,$or_where_in='',$or_like=''

  public function user_get($id = 0)
  {
    // $users = array(
    //   'userName' => 'Saikat Bhadury',
    //   'id' => 1
    // );

    // $this->response($users, RestController::HTTP_OK);
    if($id > 0){
      // If userid exist then  this block will execute and send specific user
      $userData = $this->common_model->find_data(['name' => 'users'],'row','',['id'=>$id]);
      if($userData){
        $this->response($userData, RestController::HTTP_OK);
      }
      else{
        $this->response(['status' => false, 'msg' => "UserId not exist"], RestController::HTTP_NOT_FOUND);
      }
    }
    else{
      // If userid not exist then this block will execute and send all users
      $userData = $this->common_model->find_data(['name' => 'users'],'array');
      $this->response($userData, RestController::HTTP_OK);
    }
  }


  public function user_delete($id)
  {
    if($id){
      $userData = $this->common_model->find_data(['name' => 'users'],'row','',['id'=>$id]);
      if($userData){
        if($this->common_model->delete_record('id',$id,'users')){
          $this->response(['status' => true,'msg' => 'User deleted successfully'], RestController::HTTP_OK);
        }
        else{
          $this->response(['status' => false, 'msg' => "Some technical problem occured"], RestController::HTTP_NOT_MODIFIED);
        }
      }
      else{
        $this->response(['status' => false, 'msg' => "UserId not exist"], RestController::HTTP_NOT_FOUND);
      }
    }
    else{
      $this->response(['status' => false, 'msg' => "You are not allowed to delete the user"], RestController::HTTP_NOT_FOUND);
    }
  }
}

/* End of file Example.php */
