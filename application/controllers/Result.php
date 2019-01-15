<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Result extends CI_Controller {

	 function __construct()
	 {
	   parent::__construct();
	   $this->load->database();
	   $this->load->helper('url');
	   $this->load->model("result_model");
		 $this->load->model("user_model");
	   $this->lang->load('basic', $this->config->item('language'));
		redirect if not loggedin
		$logged_in=$this->session->userdata('logged_in');
		if($logged_in['token']!="")
		{
			$user_id=$this->user_model->check_token($logged_in['token']);
			if($user_id!=$logged_in['uid'])
			{
				$this->session->unset_userdata('logged_in');
				redirect('login');
			}
		}
		else {
			$this->session->unset_userdata('logged_in');
			redirect('login');
		}

	 }

	public function index($limit='0',$status='0')
	{

	 	if(!$this->session->userdata('logged_in')){
			redirect('login');

		}
		$logged_in=$this->session->userdata('logged_in');
		if($logged_in['base_url'] != base_url()){
		$this->session->unset_userdata('logged_in');
		redirect('login');
		}


		$data['limit']=$limit;
		$data['status']=$status;
		$data['title']=$this->lang->line('resultlist');
		// fetching result list
		$data['result']=$this->result_model->result_list($limit,$status);
		// fetching quiz list
		$data['quiz_list']=$this->result_model->quiz_list();
		// group list
		 $this->load->model("user_model");
		$data['group_list']=$this->user_model->group_list();

		$this->load->view('header',$data);
		$this->load->view('result_list',$data);
		$this->load->view('footer',$data);
	}




	public function remove_result($rid){
		if(!$this->session->userdata('logged_in')){
			redirect('login');

		}
		$logged_in=$this->session->userdata('logged_in');
		if($logged_in['base_url'] != base_url()){
		$this->session->unset_userdata('logged_in');
		redirect('login');
		}
			$logged_in=$this->session->userdata('logged_in');
			if($logged_in['su']!='1'){
				exit($this->lang->line('permission_denied'));
			}

			if($this->result_model->remove_result($rid)){
                        $this->session->set_flashdata('message', "<div class='alert alert-success'>".$this->lang->line('removed_successfully')." </div>");
					}else{
						    $this->session->set_flashdata('message', "<div class='alert alert-danger'>".$this->lang->line('error_to_remove')." </div>");

					}
					redirect('result');


		}



	function generate_report(){
		if(!$this->session->userdata('logged_in')){
			redirect('login');

		}
		$logged_in=$this->session->userdata('logged_in');
		if($logged_in['base_url'] != base_url()){
		$this->session->unset_userdata('logged_in');
		redirect('login');
		}

		$logged_in=$this->session->userdata('logged_in');
			if($logged_in['su']!='1'){
				exit($this->lang->line('permission_denied'));
			}

		$this->load->helper('download');

		$quid=$this->input->post('quid');
		$gid=$this->input->post('gid');
		$result=$this->result_model->generate_report($quid,$gid);
		$csvdata=$this->lang->line('result_id').",".$this->lang->line('email').",".$this->lang->line('first_name').",".$this->lang->line('last_name').",".$this->lang->line('group_name').",".$this->lang->line('quiz_name').",".$this->lang->line('score_obtained').",".$this->lang->line('percentage_obtained').",".$this->lang->line('status')."\r\n";
		foreach($result as $rk => $val){
		$csvdata.=$val['rid'].",".$val['email'].",".$val['first_name'].",".$val['last_name'].",".$val['group_name'].",".$val['quiz_name'].",".$val['score_obtained'].",".$val['percentage_obtained'].",".$val['result_status']."\r\n";
		}
		$filename=time().'.csv';
		force_download($filename, $csvdata);

	}


	function view_result($rid){

		if(!$this->session->userdata('logged_in')){
		if(!$this->session->userdata('logged_in_raw')){
			redirect('login');
		}
		}
		if(!$this->session->userdata('logged_in')){
		$logged_in=$this->session->userdata('logged_in_raw');
		}else{
		$logged_in=$this->session->userdata('logged_in');
		}
		if($logged_in['base_url'] != base_url()){
		$this->session->unset_userdata('logged_in');
		redirect('login');
		}



		$data['result']=$this->result_model->get_result($rid);
		$data['attempt']=$this->result_model->no_attempt($data['result']['quid'],$data['result']['uid']);
		$data['title']=$this->lang->line('result_id').' '.$data['result']['rid'];
		if($data['result']['view_answer']=='1' || $logged_in['su']=='1'){
		 $this->load->model("quiz_model");
		$data['saved_answers']=$this->quiz_model->saved_answers($rid);
		$data['questions']=$this->quiz_model->get_questions($data['result']['r_qids']);
		$data['options']=$this->quiz_model->get_options($data['result']['r_qids']);

		}
		// top 10 results of selected quiz
	$last_ten_result = $this->result_model->last_ten_result($data['result']['quid']);
	$value=array();
     $value[]=array('Quiz Name','Percentage (%)');
     foreach($last_ten_result as $val){
     $value[]=array($val['email'].' ('.$val['first_name']." ".$val['last_name'].')',intval($val['percentage_obtained']));
     }
     $data['value']=json_encode($value);

	// time spent on individual questions
	$correct_incorrect=explode(',',$data['result']['score_individual']);
	 $qtime[]=array($this->lang->line('question_no'),$this->lang->line('time_in_sec'));
    foreach(explode(",",$data['result']['individual_time']) as $key => $val){
	if($val=='0'){
		$val=1;
	}
	 if($correct_incorrect[$key]=="1"){
	 $qtime[]=array($this->lang->line('q')." ".($key+1).") - ".$this->lang->line('correct')." ",intval($val));
	 }else if($correct_incorrect[$key]=='2' ){
	  $qtime[]=array($this->lang->line('q')." ".($key+1).") - ".$this->lang->line('incorrect')."",intval($val));
	 }else if($correct_incorrect[$key]=='0' ){
	  $qtime[]=array($this->lang->line('q')." ".($key+1).") -".$this->lang->line('unattempted')." ",intval($val));
	 }else if($correct_incorrect[$key]=='3' ){
	  $qtime[]=array($this->lang->line('q')." ".($key+1).") - ".$this->lang->line('pending_evaluation')." ",intval($val));
	 }
	}
	 $data['qtime']=json_encode($qtime);
	 $data['percentile'] = $this->result_model->get_percentile($data['result']['quid'], $data['result']['uid'], $data['result']['score_obtained']);


	  $uid=$data['result']['uid'];
	  $quid=$data['result']['quid'];
	  $score=$data['result']['score_obtained'];
	  $query=$this->db->query(" select * from savsoft_result where score_obtained > '$score' and quid ='$quid' group by score_obtained ");
	  $data['rank']=$query->num_rows() + 1;
	  $query=$this->db->query(" select * from savsoft_result where quid ='$quid'  group by score_obtained  ");
	  $data['last_rank']=$query->num_rows();
	  $query=$this->db->query(" select * from savsoft_result where quid ='$quid'  group by score_obtained  order by score_obtained desc limit 3 ");
	  $data['toppers']=$query->result_array();
	  $query=$this->db->query(" select * from savsoft_result where quid ='$quid'  group by score_obtained  order by score_obtained asc limit 1 ");
	  $data['looser']=$query->row_array();

		$this->load->view('header',$data);
		if($this->session->userdata('logged_in')){
		$this->load->view('view_result',$data);
		}else{
		$this->load->view('view_result_without_login',$data);

		}
		$this->load->view('footer',$data);


	}



	function generate_certificate($rid){
				if(!$this->session->userdata('logged_in')){
			redirect('login');

		}
		$logged_in=$this->session->userdata('logged_in');
		if($logged_in['base_url'] != base_url()){
		$this->session->unset_userdata('logged_in');
		redirect('login');
		}
		if(!$this->config->item('dompdf')){
		exit('DOMPDF library disabled in config.php file');

		}
	$data['result']=$this->result_model->get_result($rid);
	if($data['result']['gen_certificate']=='0'){
		exit();
	}
		// save qr
	$enu=urlencode(site_url('login/verify_result/'.$rid));

	$qrname="./upload/".time().'.jpg';
	$durl="https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl=".$enu."&choe=UTF-8";
	copy($durl,$qrname);


	$certificate_text=$data['result']['certificate_text'];
	$certificate_text=str_replace('{qr_code}',"<img src='".$qrname."'>",$certificate_text);
	$certificate_text=str_replace('{email}',$data['result']['email'],$certificate_text);
	$certificate_text=str_replace('{first_name}',$data['result']['first_name'],$certificate_text);
	$certificate_text=str_replace('{last_name}',$data['result']['last_name'],$certificate_text);
	$certificate_text=str_replace('{percentage_obtained}',$data['result']['percentage_obtained'],$certificate_text);
	$certificate_text=str_replace('{score_obtained}',$data['result']['score_obtained'],$certificate_text);
	$certificate_text=str_replace('{quiz_name}',$data['result']['quiz_name'],$certificate_text);
	$certificate_text=str_replace('{status}',$data['result']['result_status'],$certificate_text);
	$certificate_text=str_replace('{result_id}',$data['result']['rid'],$certificate_text);
	$certificate_text=str_replace('{generated_date}',date('Y-m-d H:i:s',$data['result']['end_time']),$certificate_text);

	$data['certificate_text']=$certificate_text;
	// $this->load->view('view_certificate',$data);
	$this->load->library('pdf');
	$this->pdf->load_view('view_certificate',$data);
	$this->pdf->render();
	$filename=date('Y-M-d_H:i:s',time()).".pdf";
	$this->pdf->stream($filename);


	}


	function preview_certificate($quid){
		if(!$this->session->userdata('logged_in')){
			redirect('login');

		}
		$logged_in=$this->session->userdata('logged_in');
		if($logged_in['base_url'] != base_url()){
		$this->session->unset_userdata('logged_in');
		redirect('login');
		}

		$this->load->model("quiz_model");

	$data['result']=$this->quiz_model->get_quiz($quid);
	if($data['result']['gen_certificate']=='0'){
		exit();
	}
		// save qr
	$enu=urlencode(site_url('login/verify_result/0'));
$tm=time();
	$qrname="./upload/".$tm.'.jpg';
	$durl="https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl=".$enu."&choe=UTF-8";
	copy($durl,$qrname);
	 $qrname2=base_url('/upload/'.$tm.'.jpg');


	$certificate_text=$data['result']['certificate_text'];
	$certificate_text=str_replace('{qr_code}',"<img src='".$qrname2."'>",$certificate_text);
	$certificate_text=str_replace('{result_id}','1023',$certificate_text);
	$certificate_text=str_replace('{generated_date}',date('Y-m-d H:i:s',time()),$certificate_text);

	$data['certificate_text']=$certificate_text;
	  $this->load->view('view_certificate_2',$data);


	}


	public function reportees_result_list($uid,$limit='0',$status='0')
	{

		if(!$this->session->userdata('logged_in')){
			redirect('login');

		}
		$logged_in=$this->session->userdata('logged_in');
		if($logged_in['base_url'] != base_url()){
		$this->session->unset_userdata('logged_in');
		redirect('login');
		}


		$data['limit']=$limit;
		$data['status']=$status;
		$data['title']=$this->lang->line('resultlist');
		// fetching result list
		$reportee_id = $uid;
		$manager_id = $logged_in['uid'];
		$data['result']=$this->result_model->result_list($limit,$status,$manager_id,$reportee_id);
		// fetching quiz list
		$data['quiz_list']=$this->result_model->quiz_list();
		$data['reportee_id'] =$reportee_id;
		// group list
		 $this->load->model("user_model");
		$data['group_list']=$this->user_model->group_list();

		$this->load->view('header',$data);
		$this->load->view('reportee_result_list',$data);
		$this->load->view('footer',$data);
	}




		function view_result_reportee($rid){

			if(!$this->session->userdata('logged_in')){
			if(!$this->session->userdata('logged_in_raw')){
				redirect('login');
			}
			}
			if(!$this->session->userdata('logged_in')){
			$logged_in=$this->session->userdata('logged_in_raw');
			}else{
			$logged_in=$this->session->userdata('logged_in');
			}
			if($logged_in['base_url'] != base_url()){
			$this->session->unset_userdata('logged_in');
			redirect('login');
			}


			$manager_id = $logged_in['uid'];
			$data['result']=$this->result_model->get_result($rid,$manager_id);
			$data['attempt']=$this->result_model->no_attempt($data['result']['quid'],$data['result']['uid']);
			$data['title']=$this->lang->line('result_id').' '.$data['result']['rid'];
			if($data['result']['view_answer']=='1' || $logged_in['su']=='1'){
			 $this->load->model("quiz_model");
			$data['saved_answers']=$this->quiz_model->saved_answers($rid);
			$data['questions']=$this->quiz_model->get_questions($data['result']['r_qids']);
			$data['options']=$this->quiz_model->get_options($data['result']['r_qids']);

			}
			// top 10 results of selected quiz
		$last_ten_result = $this->result_model->last_ten_result($data['result']['quid']);
		$value=array();
	     $value[]=array('Quiz Name','Percentage (%)');
	     foreach($last_ten_result as $val){
	     $value[]=array($val['email'].' ('.$val['first_name']." ".$val['last_name'].')',intval($val['percentage_obtained']));
	     }
	     $data['value']=json_encode($value);

		// time spent on individual questions
		$correct_incorrect=explode(',',$data['result']['score_individual']);
		 $qtime[]=array($this->lang->line('question_no'),$this->lang->line('time_in_sec'));
	    foreach(explode(",",$data['result']['individual_time']) as $key => $val){
		if($val=='0'){
			$val=1;
		}
		 if($correct_incorrect[$key]=="1"){
		 $qtime[]=array($this->lang->line('q')." ".($key+1).") - ".$this->lang->line('correct')." ",intval($val));
		 }else if($correct_incorrect[$key]=='2' ){
		  $qtime[]=array($this->lang->line('q')." ".($key+1).") - ".$this->lang->line('incorrect')."",intval($val));
		 }else if($correct_incorrect[$key]=='0' ){
		  $qtime[]=array($this->lang->line('q')." ".($key+1).") -".$this->lang->line('unattempted')." ",intval($val));
		 }else if($correct_incorrect[$key]=='3' ){
		  $qtime[]=array($this->lang->line('q')." ".($key+1).") - ".$this->lang->line('pending_evaluation')." ",intval($val));
		 }
		}
		 $data['qtime']=json_encode($qtime);
		 $data['percentile'] = $this->result_model->get_percentile($data['result']['quid'], $data['result']['uid'], $data['result']['score_obtained']);


		  $uid=$data['result']['uid'];
		  $quid=$data['result']['quid'];
		  $score=$data['result']['score_obtained'];
		  $query=$this->db->query(" select * from savsoft_result where score_obtained > '$score' and quid ='$quid' group by score_obtained ");
		  $data['rank']=$query->num_rows() + 1;
		  $query=$this->db->query(" select * from savsoft_result where quid ='$quid'  group by score_obtained  ");
		  $data['last_rank']=$query->num_rows();
		  $query=$this->db->query(" select * from savsoft_result where quid ='$quid'  group by score_obtained  order by score_obtained desc limit 3 ");
		  $data['toppers']=$query->result_array();
		  $query=$this->db->query(" select * from savsoft_result where quid ='$quid'  group by score_obtained  order by score_obtained asc limit 1 ");
		  $data['looser']=$query->row_array();

			$this->load->view('header',$data);
			if($this->session->userdata('logged_in')){
			$this->load->view('view_result',$data);
			}else{
			$this->load->view('view_result_without_login',$data);

			}
			$this->load->view('footer',$data);



		}


		public function view_certificate($rid)
		{
			
		if(!$this->session->userdata('logged_in')){
			if(!$this->session->userdata('logged_in_raw')){
				redirect('login');
			}
			}
			if(!$this->session->userdata('logged_in')){
			$logged_in=$this->session->userdata('logged_in_raw');
			}else{
			$logged_in=$this->session->userdata('logged_in');
			}
			if($logged_in['base_url'] != base_url()){
			$this->session->unset_userdata('logged_in');
			redirect('login');
			}
	
	
	
			$data['result']=$this->result_model->get_result($rid);
			$data['attempt']=$this->result_model->no_attempt($data['result']['quid'],$data['result']['uid']);
			$data['title']=$this->lang->line('result_id').' '.$data['result']['rid'];
			if($data['result']['view_answer']=='1' || $logged_in['su']=='1'){
			 $this->load->model("quiz_model");
			$data['saved_answers']=$this->quiz_model->saved_answers($rid);
			$data['questions']=$this->quiz_model->get_questions($data['result']['r_qids']);
			$data['options']=$this->quiz_model->get_options($data['result']['r_qids']);
	
			}
			// top 10 results of selected quiz
		$last_ten_result = $this->result_model->last_ten_result($data['result']['quid']);
		$value=array();
		 $value[]=array('Quiz Name','Percentage (%)');
		 foreach($last_ten_result as $val){
		 $value[]=array($val['email'].' ('.$val['first_name']." ".$val['last_name'].')',intval($val['percentage_obtained']));
		 }
		 $data['value']=json_encode($value);
	
		// time spent on individual questions
		$correct_incorrect=explode(',',$data['result']['score_individual']);
		 $qtime[]=array($this->lang->line('question_no'),$this->lang->line('time_in_sec'));
		foreach(explode(",",$data['result']['individual_time']) as $key => $val){
		if($val=='0'){
			$val=1;
		}
		 if($correct_incorrect[$key]=="1"){
		 $qtime[]=array($this->lang->line('q')." ".($key+1).") - ".$this->lang->line('correct')." ",intval($val));
		 }else if($correct_incorrect[$key]=='2' ){
		  $qtime[]=array($this->lang->line('q')." ".($key+1).") - ".$this->lang->line('incorrect')."",intval($val));
		 }else if($correct_incorrect[$key]=='0' ){
		  $qtime[]=array($this->lang->line('q')." ".($key+1).") -".$this->lang->line('unattempted')." ",intval($val));
		 }else if($correct_incorrect[$key]=='3' ){
		  $qtime[]=array($this->lang->line('q')." ".($key+1).") - ".$this->lang->line('pending_evaluation')." ",intval($val));
		 }
		}
		 $data['qtime']=json_encode($qtime);
		 $data['percentile'] = $this->result_model->get_percentile($data['result']['quid'], $data['result']['uid'], $data['result']['score_obtained']);
	
	
		  $uid=$data['result']['uid'];
		  $quid=$data['result']['quid'];
		  $score=$data['result']['score_obtained'];
		  $query=$this->db->query(" select * from savsoft_result where score_obtained > '$score' and quid ='$quid' group by score_obtained ");
		  $data['rank']=$query->num_rows() + 1;
		  $query=$this->db->query(" select * from savsoft_result where quid ='$quid'  group by score_obtained  ");
		  $data['last_rank']=$query->num_rows();
		  $query=$this->db->query(" select * from savsoft_result where quid ='$quid'  group by score_obtained  order by score_obtained desc limit 3 ");
		  $data['toppers']=$query->result_array();
		  $query=$this->db->query(" select * from savsoft_result where quid ='$quid'  group by score_obtained  order by score_obtained asc limit 1 ");
		  $data['looser']=$query->row_array();


			$this->load->library('Pdf');
			$this->load->view('view_certificateash',$data);
		}

}
