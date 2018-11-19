 <div class="container" style="text-align:center;">
<?php
$logged_in=$this->session->userdata('logged_in');
?>



<?php
if($logged_in['su']=='1'){
	?>
   <div class="row">

  <div class="col-lg-12">

  </div><!-- /.col-lg-6 -->
</div><!-- /.row -->

<?php
}
?>

<div class="col-md-12 row-clr" style="background-color:#ffffff;height:100%;margin-top:-25px;">
<h3 class="ft_wt"><?php echo $title;?></h3>

  <div class="row">

  <div class="col-lg-6">
    <form method="post" action="<?php echo site_url('studymaterial/index/');?>">
	<div class="input-group">
    <!-- <input type="text" class="form-control" name="search" placeholder="<?php echo $this->lang->line('search');?>...">
      <span class="input-group-btn">
        <button class="btn btn-default" type="submit"><?php echo $this->lang->line('search');?></button>
      </span> -->


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
    <br>
		<?php
		if($logged_in['su']=='1'){
			?>
      <button class="btn loginbtn-hollow" style="background-color:#ffffff;" onclick="window.location='<?php echo site_url('studymaterial/add'); ?>';">Add New</button><br><br>
		<?php
		}
		?>
<table class="table table-bordered">
<tr>
 <th width='5%'><?php echo $this->lang->line('studymaterial_id');?></th>
<th><?php echo $this->lang->line('studymaterial_title');?></th>
 <th><?php echo $this->lang->line('studymaterial_desc');?></th>
 <th><?php echo $this->lang->line('studymaterial_cat');?>

<th><?php echo $this->lang->line('action');?> </th>
</tr>
<?php
if(count($material_list)==0){
	?>
<tr>
 <td colspan="6"><?php echo $this->lang->line('no_record_found');?></td>
</tr>


	<?php
}
$i=0;
foreach($material_list as $key => $val){
  $i++;
?>
<tr>
 <td><?php echo $i;?></td>
<td><?php echo $val['title'];?> </td>
 <td><?php echo $val['description'];?></td>
 <td><?php echo $val['category_name']; ?></td>

<td>
<a href="<?php echo ($val['link_type']=='file')?$val['file']:$val['link'];?>" class="btn loginbtn-hollow" target="_blank"><?php echo $this->lang->line('view');?> </a>
<?php
if($logged_in['su']=='1'){
	?>
	<a href="javascript:remove_material('<?php echo $val['material_id'];?>','<?php echo md5($val['material_id']);?>');" ><i style="color:rgb(255,0,140)" class="fa fa-times" aria-hidden="true"></i></a>
<?php
}
?>
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

<!-- <a href="<?php echo site_url('result/index/'.$back.'/'.$status);?>"  class="btn btn-primary"><?php echo $this->lang->line('back');?></a> -->
&nbsp;&nbsp;
<?php
 $next=$limit+($this->config->item('number_of_rows'));  ?>

<!-- <a href="<?php echo site_url('result/index/'.$next.'/'.$status);?>"  class="btn btn-primary"><?php echo $this->lang->line('next');?></a> -->





</div>
</div>

<script>
function remove_material(id,code)
{
  var check = confirm('Are you want to delete it ?');
  if(check)
  {
    window.location = base_url+'index.php/studymaterial/delete/'+id+'/'+code;
  }
}
</script>
