 <div class="container" style="text-align:center;">

   <div class="col-md-12 row-clr" style="background-color:#ffffff;height:100%;margin-top:-25px;"> 
 <h3 class="ft_wt"><?php echo $title;?></h3>
   
 

  <div class="row">
     <form method="post" action="<?php echo site_url('user/edit_group/'.$gid);?>">
	
<div class="col-md-8 col-md-offset-2" >
<br> 
 <div class="login-panel panel panel-default sha_div">
		<div class="panel-body"> 
	
	
	
			<?php 
		if($this->session->flashdata('message')){
			echo $this->session->flashdata('message');	
		}
		?>	
		
		
		
				 

			
			 
			
			
			 
			
			

			<div class="form-group">	 
					<label for="inputEmail"  ><?php echo $this->lang->line('group_name');?></label> 
					<input type="text" required  name="group_name"  class="form-control"  value="<?php echo $group['group_name'];?>" > 
			</div>
			 
		 
<div class="form-group">	 
					<label for="inputEmail"  ><?php echo $this->lang->line('description');?></label> 
					<textarea  name="description"  class="form-control"   >   <?php echo $group['description'];?></textarea>
			</div>
			
		 

 
	<button class="btn loginbtn" type="submit"><?php echo $this->lang->line('submit');?></button>
 
		</div>
</div>
 
 
 
 
</div>
      </form>
</div>

 



</div>
</div>
