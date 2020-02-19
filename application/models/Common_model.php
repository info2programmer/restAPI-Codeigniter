<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Common_model extends CI_Model
{
	var $table;
	
    function  __construct(){
        parent::__construct();
    }
    ################################ FETCH DATA ########################################
    function getData($tableName,$searchByValue=null,$searchFields=null,$limit=null,$offset=null)
    {
        if($searchByValue!="" && $searchFields!=""){
            $this->db->where($searchFields,$searchByValue);
            $query = $this->db->get($tableName, $limit, $offset);
            return $query->result_array();
        }
        else{
            $query = $this->db->get($tableName, $limit, $offset);
            return $query->result();
        }
        
    }
    ################################ UPDATE DATA ########################################
	function updateData($searchByValue=null,$searchFields=null,$object,$tableName){
        $this->db->where($searchFields, $searchByValue);
        $this->db->update($tableName, $object);
    }
    
    	// Delete Record
	public function delete_record($where_feald,$delete_id,$table_name)
	{
		$this->db->where($where_feald, $delete_id);
		$this->db->delete($table_name);
		return true;
	}
	// Active Or Deactive Fealds
	public function chage_status($where_feald,$update_id,$table_name,$object)
	{
		$this->db->where($where_feald, $update_id);
		$this->db->update($table_name, $object);
		
	}
	// Insert Function
	public function insert_record($table_name,$object)
	{
		$this->db->insert($table_name, $object);
		$insert_id = $this->db->insert_id();
		return  $insert_id;
	}
	// This function for image upload
	public function upload_image($file,$path)
	{
		
		$config['upload_path'] = $path;
		$config['allowed_types'] = 'gif|jpg|png|jpeg';
		
		
		$this->load->library('upload', $config);
		
		if ( ! $this->upload->do_upload($file)){
			$error = array('error' => $this->upload->display_errors());
			var_dump($error);
			die;
		}
		else{
			$fileData=$this->upload->data();
		}
		return $fileData['file_name'];
	}
	// This Function For Send SMTP mail
	public function send_smtpmail($email,$subject,$mailBody)
	{
		$config = Array(
            'protocol' => 'smtp',
            'smtp_host' => 'smtp.zoho.com',
            'smtp_port' => 465,
            'smtp_user' => 'admin@izifiso.com',
            'smtp_pass' => '@BabaKiLand',
            'mailtype'  => 'html', 
            'charset'   => 'iso-8859-1'
        );
		$this->load->library('email', $config);
		
		$this->email->from('admin@izifiso.com', 'IZIFISO ADMIN');
        $this->email->to($email); 
        $this->email->subject($subject);
        $this->email->message($mailBody);  
        $this->email->set_mailtype("html");
        $this->email->send();
    }
    
    public function checkcredential($username,$password){
		//encrypt password
		$password=md5($password);
		$this->db->where('userName',$username);
		$this->db->where('password',$password);
        $result=$this->db->get('tbl_login');        
		if($result->num_rows()==1){
			return $result->row(0)->id;
		}
		else{
			return false;
		}
	}
	
	
	// Find Data
	public function find_data($table,$return_type='array',$list=NULL,$conditions='',$select='*',$join='',$group_by='',$order_by='',$limit=0,$offset=0,$or_where_in='',$or_like='')
	{
		
		$result = array();		
		
		$this->db->select($select);		
		
		if(!empty($table['alias_name']))
		{
			$table_name = $table['name'].' as '.$table['alias_name'];
		}
		else
		{
			$table_name = $table['name'];
		}
		$this->db->from($table_name);
		
		if(is_array($join))
		{
			for($j=0;$j<count($join);$j++)
			{
				if($join[$j]['table'])
				{
					/*$table_join = $join[$j]['table'].' as '.$join[$j]['table_alias']*/;
					//$table_join_name = $join[$j]['table_alias'];
					$table_join = $join[$j]['table'];
					$table_join_name = $join[$j]['table'];
				}
				else
				{
					/*$table_join = $join[$j]['table'];
					$table_join_name = $join[$j]['table'];*/
				}
				if(!empty($join[$j]['table_master_alias']))
				{
					$table_master_join = $join[$j]['table_master_alias'];
				}
				else
				{
					$table_master_join = $join[$j]['table_master'];
				}
				$this->db->join($table_join,$table_join_name.'.'.$join[$j]['field'].'='.$table_master_join.'.'.$join[$j]['field_table_master']/*.$join[$j]['and']*/,$join[$j]['type']);
			}
		}
		
		if($conditions != '')$this->db->where($conditions);	
		if($or_where_in != '')$this->db->or_where_in($or_where_in);
		
		if($or_like != '')$this->db->or_like($or_like);		
		
		if(is_array($group_by))
		{
			for($g=0;$g<count($group_by);$g++)
			{
				$this->db->group_by($group_by[$g]);
			}
		}
		
		if(is_array($order_by))
		{
			for($o=0;$o<count($order_by);$o++)
			{
				$this->db->order_by($order_by[$o]['field'],$order_by[$o]['type']);
			}
		}
		
		if($limit != 0)$this->db->limit($limit,$offset);
		
		
		$query = $this->db->get();
		
		//echo $this->db->last_query();
		switch ($return_type) 
		{
			case 'array':
			case '':
				if($query->num_rows() > 0){$result = $query->result();}
				break;
				
			case 'row':
				if($query->num_rows() > 0){$result = $query->row();}
				break;
			
			case 'row-array':
				if($query->num_rows() > 0){$result = $query->row_array();}
				break;
				
			case 'list':
			
				$list_arr[''] = 'Choose'. $list['empty_name'];
				if($query->num_rows() > 0)
				{
					foreach ($query->result() as $row)
					{
						$list_arr[$row->$list['key']] = $row->$list['value'];
					}
				} else {
					$list_arr[] = 'No City Found';
				}
				$result = $list_arr;
				break;
				
			case 'count':
				$result = $query->num_rows();
				break;
		}
		//echo $this->db->last_query();die;
        return $result;
	}

	// This Fuction For Get Recent Blog Data 
	// public function recentblogdata($limit)
	// {
	// 	$this->db->limit($limit);
	// 	$this->db->group_by('blog_id');
	// 	$this->db->select('blog_image.image, izifiso_blog.*');
	// 	$this->db->from('izifiso_blog');
	// 	$this->db->join('blog_image', 'izifiso_blog.blog_id  = blog_image.blog_id', 'inner');
	// 	$recentBlogQuery = $this->db->get();
	// 	return 	$recentBlogQuery->result();		
	// }


	// This Section For Get Blog By Blog Category
	public function blogsByCategory($category)
	{
		$this->db->group_by('blog_id');
		$this->db->where('blog_category.id', $category);
		$this->db->select('blog_image.image, izifiso_blog.*,blog_category.category_name');
		$this->db->from('izifiso_blog');
		$this->db->join('blog_image', 'izifiso_blog.blog_id  = blog_image.blog_id', 'inner');
		$this->db->join('blog_category', 'blog_category.id  = izifiso_blog.category_id', 'inner');
		$recentBlogQuery = $this->db->get();
		echo $this->db->last_query();
		die;
		return 	$recentBlogQuery->result();	
	}


	// This Function For Remove All Special Char
	public function cleanSpecialChar($string) {
		$string = trim($string);
		$string = strtolower($string);
		$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
	 
		return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
	}


	// This Section For Similar Product
	public function count($where,$tableName)
	{
		$this->db->where($where);
		$query = $this->db->get($tableName);
		// echo $this->db->last_query();
		// die;
		return $query->num_rows();
	}


	// This Section For Get Seat Accomodation
	public function getSeatAccomodatiob($stay,$toDate,$fromDate,$productId,$productType)
	{	
		$this->db->where('seat_id', $stay);
		$this->db->where('date >=', $toDate);
		$this->db->where('date <=', $fromDate);
		$this->db->group_by('date');
		$this->db->select('SUM(seat) as totalSeat,max_head,date');
		$this->db->from('tbl_book');
		$this->db->join('seat_accomodation', 'seat_accomodation.id = tbl_book.seat_id', 'INNER');
		$query=$this->db->get();
		return $query->result();
		
	}

	

	
    
	
}