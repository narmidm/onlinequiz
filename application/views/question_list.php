 <div class="container" style="text-align:center;">

<div class="col-md-12 row-clr" style="background-color:#ffffff;margin-top:-25px;">
 <h3 class="ft_wt"><?php echo $title;?></h3>
    <div class="row">

  <div class="col-lg-6 col-lg-offset-3">
    <form method="post" action="<?php echo site_url('qbank/index/');?>">
	<div class="input-group sha_div">
    <input type="text" class="form-control" name="search" placeholder="<?php echo $this->lang->line('search');?>...">
      <span class="input-group-btn">
        <button class="btn btn-default" style="background-image: linear-gradient(to bottom, rgb(255, 0, 140),rgb(255, 0, 140),rgb(226, 15, 68));color:#ffffff;" type="submit"><?php echo $this->lang->line('search');?></button>
      </span>


    </div><!-- /input-group -->
	 </form>
  </div><!-- /.col-lg-6 -->
</div><!-- /.row -->


  <div class="row">

<div class="col-md-12">
<br>
<div class="login-panel panel panel-default sha_div" style="overflow-x:auto;">
		<div class="panel-body">
			<?php
		if($this->session->flashdata('message')){
			echo $this->session->flashdata('message');
		}
		?>
						<div class="form-group">
					<form method="post" action="<?php echo site_url('qbank/pre_question_list/'.$limit.'/'.$cid.'/'.$lid);?>">
					<select   name="cid" class="in_sel">
					<option value="0"><?php echo $this->lang->line('all_category');?></option>
					<?php
					foreach($category_list as $key => $val){
						?>

						<option value="<?php echo $val['cid'];?>" <?php if($val['cid']==$cid){ echo 'selected';} ?> ><?php echo $val['category_name'];?></option>
						<?php
					}
					?>
					</select>
			 	<select  name="lid" class="in_sel">
				<option value="0"><?php echo $this->lang->line('all_level');?></option>
					<?php
					foreach($level_list as $key => $val){
						?>

						<option value="<?php echo $val['lid'];?>"  <?php if($val['lid']==$lid){ echo 'selected';} ?> ><?php echo $val['level_name'];?></option>
						<?php
					}
					?>
					</select>
					 <button class="btn btn-default" style="background-image: linear-gradient(to bottom, rgb(255, 0, 140),rgb(255, 0, 140),rgb(226, 15, 68));color:#ffffff;" type="submit"><?php echo $this->lang->line('filter');?></button>
					 </form>
			</div>

<table class="table table-bordered">
<tr>
 <th>#</th>
 <th><?php echo $this->lang->line('question');?></th>
<th><?php echo $this->lang->line('question_type');?></th>
<th><?php echo $this->lang->line('category_name');?> / <?php echo $this->lang->line('level_name');?></th>

<th><?php echo $this->lang->line('percent_corrected');?></th>
<th><?php echo $this->lang->line('action');?> </th>
</tr>
<?php
if(count($result)==0){
	?>
<tr>
 <td colspan="3"><?php echo $this->lang->line('no_record_found');?></td>
</tr>


	<?php
}
foreach($result as $key => $val){
?>
<tr>
 <td>  <a href="javascript:show_question_stat('<?php echo $val['qid'];?>');">+</a>  <?php echo $val['qid'];?></td>
 <td><?php echo substr(strip_tags($val['question']),0,40);?>


 <span style="display:none;" id="stat-<?php echo $val['qid'];?>">


 <table class="table table-bordered">
<tr><td><?php echo $this->lang->line('no_times_corrected');?></td><td><?php echo $val['no_time_corrected'];?></td></tr>
<tr><td><?php echo $this->lang->line('no_times_incorrected');?></td><td><?php echo $val['no_time_incorrected'];?></td></tr>
<tr><td><?php echo $this->lang->line('no_times_unattempted');?></td><td><?php echo $val['no_time_unattempted'];?></td></tr>

</table>




 </span>



 </td>
<td><?php echo $val['question_type'];?></td>
<td><?php echo $val['category_name'];?> / <span style="font-size:12px;"><?php echo $val['level_name'];?></span></td>

<td><?php if($val['no_time_served']!='0'){ $perc=($val['no_time_corrected']/$val['no_time_served'])*100;
?>

<div style="background:#eeeeee;width:100%;height:10px;"><div style="background:#449d44;width:<?php echo intval($perc);?>%;height:10px;"></div>
<span style="font-size:10px;"><?php echo intval($perc);?>%</span>

<?php
}else{ echo $this->lang->line('not_used');} ?></td>

<td>
<?php
$qn=1;
if($val['question_type']==$this->lang->line('multiple_choice_single_answer')){
	$qn=1;
}
if($val['question_type']==$this->lang->line('multiple_choice_multiple_answer')){
	$qn=2;
}
if($val['question_type']==$this->lang->line('match_the_column')){
	$qn=3;
}
if($val['question_type']==$this->lang->line('short_answer')){
	$qn=4;
}
if($val['question_type']==$this->lang->line('long_answer')){
	$qn=5;
}


?>
<a href="<?php echo site_url('qbank/edit_question_'.$qn.'/'.$val['qid']);?>"> <i style="color:rgb(255,0,140)" class="fa fa-pencil" aria-hidden="true"></i></a>
<a href="javascript:remove_entry('qbank/remove_question/<?php echo $val['qid'];?>');"><i style="color:rgb(255,0,140)" class="fa fa-times" aria-hidden="true"></i></a>

</td>
</tr>

<?php
}
?>
</table>
</div>

</div>
</div>

</div>


<?php
if(($limit-($this->config->item('number_of_rows')))>=0){ $back=$limit-($this->config->item('number_of_rows')); }else{ $back='0'; } ?>

<a href="<?php echo site_url('qbank/index/'.$back.'/'.$cid.'/'.$lid);?>"  class="btn loginbtn-hollow"><?php echo $this->lang->line('back');?></a>
&nbsp;&nbsp;
<?php
 $next=$limit+($this->config->item('number_of_rows'));  ?>

<a href="<?php echo site_url('qbank/index/'.$next.'/'.$cid.'/'.$lid);?>"  class="btn loginbtn-hollow"><?php echo $this->lang->line('next');?></a>







<br><br><br><br>
<div class="login-panel panel panel-default sha_div">
	<div class="panel-heading">
<h4><?php echo $this->lang->line('import_question');?></h4>
</div>
	<div class="panel-body">

<?php echo form_open('qbank/import',array('enctype'=>'multipart/form-data')); ?>

 <select name="cid" class="in_sel"  required >
 <option value=""><?php echo $this->lang->line('select_category');?></option>
<?php
					foreach($category_list as $key => $val){
						?>

						<option value="<?php echo $val['cid'];?>" <?php if($val['cid']==$cid){ echo 'selected';} ?> ><?php echo $val['category_name'];?></option>
						<?php
					}
					?></select>
 <select name="did" class="in_sel" required >
 <option value=""><?php echo $this->lang->line('select_level');?></option>
<?php
					foreach($level_list as $key => $val){
						?>

						<option value="<?php echo $val['lid'];?>"  <?php if($val['lid']==$lid){ echo 'selected';} ?> ><?php echo $val['level_name'];?></option>
						<?php
					}
					?>
					</select>

<?php echo $this->lang->line('upload_excel');?>
	<input type="hidden" name="size" value="3500000">
	<input type="file" name="xlsfile" style="width:300px;float:left;margin-left:10px;">
	<div style="clear:both;"></div>
	<input type="submit" value="Import" style="margin-top:5px;" class="btn btn-default loginbtn">

<a style="color:rgb(255,0,140);" href="<?php echo base_url();?>sample/sample.xls" target="new">Click here</a> <?php echo $this->lang->line('upload_excel_info');?>
</form>

</div>





</div>



<div class="login-panel panel panel-default sha_div">
<div class="panel-heading">
<h4><?php echo $this->lang->line('import_question2');?></h4>
</div>
		<div class="panel-body">

<?php echo form_open('word_import',array('enctype'=>'multipart/form-data')); ?>

<div class="alert alert-danger"> <?php echo $this->lang->line('wordimportinfo');?></div>

 <select name="cid" class="in_sel" required >
 <option value=""><?php echo $this->lang->line('select_category');?></option>
<?php
					foreach($category_list as $key => $val){
						?>

						<option value="<?php echo $val['cid'];?>" <?php if($val['cid']==$cid){ echo 'selected';} ?> ><?php echo $val['category_name'];?></option>
						<?php
					}
					?></select>
 <select name="lid" class="in_sel" required >
 <option value=""><?php echo $this->lang->line('select_level');?></option>
<?php
					foreach($level_list as $key => $val){
						?>

						<option value="<?php echo $val['lid'];?>"  <?php if($val['lid']==$lid){ echo 'selected';} ?> ><?php echo $val['level_name'];?></option>
						<?php
					}
					?>
					</select>

<?php echo $this->lang->line('upload_doc');?>
	<input type="hidden" name="size" value="3500000">
	<input type="file" name="word_file" style="width:150px;float:left;margin-left:10px;">
	<div style="clear:both;"></div>
	<p style="padding:10px;"><a style="color:rgb(255,0,140);" href="javascript:advanceconfig();">Advance</a></p>
	<div id="advanceconfig" style="padding:10px;display:none">
	<table>
	<tr><td>Question Splitter:</td><td> <input type="text" name="question_split" value="/Q:[0-9]+\)/"></td></tr>
	<tr><td>Description Splitter: </td><td><input type="text" name="description_split" value="/Desc:/"></td></tr>
	<tr><td>Options Splitter: </td><td><input type="text" name="option_split" value="/[A-Z]:\)/"></td></tr>
	<tr><td>Correct Option Splitter: </td><td><input type="text" name="correct_split" value="/Correct:/"></td></tr>
	</table>
	</div>

	<input type="submit" value="Import" style="margin-top:5px;" class="btn btn-default loginbtn">

<a style="color:rgb(255,0,140);" href="<?php echo base_url();?>sample/sample.docx" target="new">Click here</a> <?php echo $this->lang->line('upload_doc_info');?>
</form>

</div>
				</div>
<script>

function advanceconfig(){

	$('#advanceconfig').toggle();

}



</script>
<br><br><br><br>
