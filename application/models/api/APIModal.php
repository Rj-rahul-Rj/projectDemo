<?php
/**
Created by Sukhdev Pawar (sukh.panwar18@gmail.com) for all type of comman operation  in CI. You can extend it in your any modal than can be call his methods.
///*** Ping me on mail if you want something else ////***
 * 
Thanks
 */

class APIModal extends CI_Model
 {
  
    // if you want to add single row data in a table 
    public function AddData($table,$data){
        $this->db->insert($table, $data);
        $q = $this->db->insert_id();
        return $q;
    }

    // if you want to add multiple row with single query so pass multi-D array in it
    public function AddMultiple($table,$data){
        $this->db->insert_batch($table, $data);
        $q = $this->db->insert_id();
        return $q;
    }


    // update a table data using this function
    public function updateData($table,$set,$where){
        return $this->db->update($table,$set,$where);
    }
    
    // call this function if yout want to delete data from a table
    public function DeleteData($table,$where){
        return $this->db->delete($table,$where);
    }

    // call this function if yout want to get multiple data from a table
    // public function GetData($table,$coloum,$where,$order_by='id',$order='DESC',$joins=array()){
    public function GetData($table,$coloum,$where,$joins=array()){

        $this->db->select($coloum);
        $this->db->from($table);
        $this->db->where($where);
        // get row data with join if need
        if(count($joins)>0){
            foreach($joins as $join){
                $join['type'] = (array_key_exists('type', $join))?$join['type']:'inner';
                $this->db->join($join['table'],$join['condition'],$join['type']);
            }
        }
        $q = $this->db->get();
        if($q->num_rows()){
          return $q->row();
        }
        return false;
    }

    public function GetGroupData($table,$coloum,$where,$joins=array(),$group_by='',$order_by='',$order='DESC'){
        
        $this->db->select($coloum);
        $this->db->from($table);
        $this->db->where($where);
        if($group_by!=''){
        $this->db->group_by($group_by);
        }
        if($order_by !=''){
            $this->db->order_by($order_by,$order);
        }
        if(count($joins)>0){
            foreach($joins as $join){
                $join['type'] = (array_key_exists('type', $join))?$join['type']:'inner';
                $this->db->join($join['table'],$join['condition'],$join['type']);
            }
        }


        $q = $this->db->get();
        if($q->num_rows()){
          return $q->result_array();
        }
        return false;
    }
    // call this function if yout want to get only single row data
    public function RowData($table,$coloum='*',$where='',$joins=array()){
        $this->db->select($coloum);
        $this->db->from($table);
        ($where)?$this->db->where($where):"";
        
        // get row data with join if need
        if(count($joins)>0){
            foreach($joins as $join){
                $join['type'] = (array_key_exists('type', $join))?$join['type']:'inner';
                $this->db->join($join['table'],$join['condition'],$join['type']);
            }
        }

        $q = $this->db->get();
        if($q->num_rows()){
          return $q->row();
        }
        return false;
    }

    // call this function if yout want to get only number of rows in a table
    public function NumRows($table,$where){
        $this->db->select("id");
        $this->db->from($table);
        $this->db->where($where);
        $q = $this->db->get();
        if($q->num_rows()){
          return $q->num_rows();
        }
        return false;
    }

    // call  this function if you want to pagination data  
    public function Pagination($table, $coloum='*',$where,$page_no='1',$order_by='id',$order='DESC',$joins=array())
    {
        $perpage = 20;
        $offset    = (intval($page_no)*intval($perpage))-intval($perpage);
        if($offset<=0){
            $offset = 0;
        }
        $this->db->select($coloum);
        $this->db->from($table);
        $this->db->where($where);
        if($order_by !=''){
            $this->db->order_by($order_by,$order);
        }
        if(count($joins)>0){
            foreach($joins as $join){
                $join['join'] = (array_key_exists('join', $join))?$join['join']:'inner';
                $this->db->join($join['table'],$join['condition'],$join['join']);
            }
        }

        $this->db->limit($perpage,$offset);
        $query  = $this->db->get();         
        $result =  $query->result_array();
        
        $query      = explode('LIMIT', $this->db->last_query())[0];
        $pagination = $this->GenratePagination($query,$page_no,$perpage);

        $data['data']       = $result;
        $data['pagination'] = $pagination;
        return $data;
    }
    
    // genrate pagination using this function it's accosiated with Pagination() function 
    public function GenratePagination($query=0,$page_no=1,$perpage=20)
    {
        $pagination = array();
        $q          = $this->db->query($query);  // fetch all row count 
        $num_row    =  $q->num_rows();
        $pageCount  = $num_row/$perpage; // get page no

        if(strpos($pageCount,'.')) // check if decimal 
        {
          $pageCount = floor($pageCount)+1;
        }
        // prepare responce for pagination 
        $pagination['pageCount']   = intval($pageCount); 
        $pagination['currentPage'] = intval($page_no);
        $pagination['per_page']    = intval($perpage);
        return $pagination;
       
    }

    // call this function if yout want to apply join and  simple query like getdata()
    public function Join($table, $coloum='*',$where,$joins=array(),$order_by='id',$order='DESC')
    {
        $this->db->select($coloum);
        $this->db->from($table);
        ($where)?$this->db->where($where):"";
        ($order_by)?$this->db->order_by($order_by,$order):"";

        if(count($joins)>0){
            foreach($joins as $join){
                $join['type'] = (array_key_exists('type', $join))?$join['type']:'inner';
                $this->db->join($join['table'],$join['condition'],$join['type']);
            }
        }

        $query  = $this->db->get();
        if($query->num_rows()>0)
        {
          return $query->result_array();
        }   
        return false;
    }

    // call this function if you want to limited data without paginations
    function GetLimitedData($table, $coloum='*',$where,$limit=5,$joins=array(),$order_by='id',$order='DESC')
    {
        $this->db->select($coloum);
        $this->db->from($table);
        ($where)?$this->db->where($where):"";
        ($order_by)?$this->db->order_by($order_by,$order):"";
        if(count($joins)>0){
            foreach($joins as $join){
                $join['type'] = (array_key_exists('type', $join))?$join['type']:'inner';
                $this->db->join($join['table'],$join['condition'],$join['type']);
            }
        }
        $this->db->limit($limit);
        $query  = $this->db->get();
        if($query->num_rows()>0)
        {
          return $query->result_array();
        }   
        return false;
    }
	
	    public function paginationRecent($table, $coloum='*',$where,$group_by='',$page_no='1',$order_by='id',$order='DESC',$joins=array())
    {
        $perpage = 20;
        $offset    = (intval($page_no)*intval($perpage))-intval($perpage);
        if($offset<=0){
            $offset = 0;
        }
        $this->db->select($coloum);
        $this->db->from($table);
        $this->db->where($where);
		if($group_by !=''){
			$this->db->group_by($group_by);
		}
        if($order_by !=''){
            $this->db->order_by($order_by,$order);
        }
        if(count($joins)>0){
            foreach($joins as $join){
                $join['join'] = (array_key_exists('join', $join))?$join['join']:'inner';
                $this->db->join($join['table'],$join['condition'],$join['join']);
            }
        }

        $this->db->limit($perpage,$offset);
        $query  = $this->db->get();         
        $result =  $query->result_array();
        
        $query      = explode('LIMIT', $this->db->last_query())[0];
        $pagination = $this->GenratePagination($query,$page_no,$perpage);

        $data['data']       = $result;
        $data['pagination'] = $pagination;
        return $data;
    }
	
	function GetLimitedRecentData($table, $coloum='*',$where,$group_by='',$limit=5,$joins=array(),$order_by='id',$order='DESC')
    {
        $this->db->select($coloum);
        $this->db->from($table);
        ($where)?$this->db->where($where):"";
        ($group_by)?$this->db->group_by($group_by):"";
        ($order_by)?$this->db->order_by($order_by,$order):"";
        if(count($joins)>0){
            foreach($joins as $join){
                $join['type'] = (array_key_exists('type', $join))?$join['type']:'inner';
                $this->db->join($join['table'],$join['condition'],$join['type']);
            }
        }
        $this->db->limit($limit);
        $query  = $this->db->get();
        if($query->num_rows()>0)
        {
          return $query->result_array();
        }   
        return false;
    }

}
?>