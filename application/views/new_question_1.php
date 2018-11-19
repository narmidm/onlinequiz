 <div class="container con_cen">

<div class="col-md-12 row-clr" style="background-color:#ffffff;margin-top:-25px;">   
 <h3 class="ft_wt"><?php echo $title;?></h3>
   
 

  <div class="row align-self-center" > 
     <form method="post" action="<?php echo site_url('qbank/new_question_1/'.$nop);?>">
	
<div class="col-md-8 col-md-offset-2">
<br> 
 <div class="login-panel panel panel-default sha_div" style="overflow-x:auto;">
		<div class="panel-body" > 
	
	
	
			<?php 
		if($this->session->flashdata('message')){
			echo $this->session->flashdata('message');	
		}
		?>	
		
		
		
				<div class="form-group">	 
					
<?php echo $this->lang->line('multiple_choice_single_answer');?>
			</div>

			
			<div class="form-group">	 
					<label   ><?php echo $this->lang->line('select_category');?></label> 
					<select class="form-control" name="cid">
					<?php 
					foreach($category_list as $key => $val){
						?>
						
						<option value="<?php echo $val['cid'];?>"><?php echo $val['category_name'];?></option>
						<?php 
					}
					?>
					</select>
			</div>
			
			
			<div class="form-group">	 
					<label   ><?php echo $this->lang->line('select_level');?></label> 
					<select class="form-control" name="lid">
					<?php 
					foreach($level_list as $key => $val){
						?>
						
						<option value="<?php echo $val['lid'];?>"><?php echo $val['level_name'];?></option>
						<?php 
					}
					?>
					</select>
			</div>

			
			

			<div class="form-group" >	 
					<label for="inputEmail"  ><?php echo $this->lang->line('question');?></label> 
					<textarea  name="question"  class="form-control"></textarea>
			</div>
			<div class="form-group">	 
					<label for="inputEmail"  ><?php echo $this->lang->line('description');?></label> 
					<textarea  name="description"  class="form-control"></textarea>
			</div>
		<?php 
		for($i=1; $i<=$nop; $i++){
			?>
			<div class="form-group">	 
					<label for="inputEmail"  ><?php echo $this->lang->line('options');?> <?php echo $i;?>)</label> <br>
					<input type="radio" name="score" value="<?php echo $i-1;?>" <?php if($i==1){ echo 'checked'; } ?> > Select Correct Option 
					<br><textarea  name="option[]"  class="form-control"   ></textarea>
			</div>
		<?php 
		}
		?>

 
	<button class="btn btn-primary btn-block loginbtn" type="submit"><?php echo $this->lang->line('submit');?></button>
 
		</div>
</div>
 
 
 
 
</div>
      </form>
</div>

 



</div>
	</div>