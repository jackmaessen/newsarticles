<!-- Modal -->
<div id="<?php echo $file_id; ?>" class="modal fade" role="dialog">
  <div class="modal-dialog">

	<!-- Modal content-->
	<div class="modal-content">
	  <div class="modal-header">		
		<h3 class="modal-title">Edit:&nbsp;<?php echo $news_title; ?></h3>
		<button type="button" class="close" data-dismiss="modal">&times;</button>
	  </div>
	  <div class="modal-body">

		<form action="<?php $_SERVER["PHP_SELF"]; ?>" method="POST" role="form">	
		
			<input type="hidden" name="edit_file" value="edit" />	
			<input type="hidden" name="file_id" value="<?php echo $file_id; ?>" />
			<div class="control-group form-group">
				<div class="controls">
					<select class="category_selectbox form-control" name="news_category">
						<option value="<?php echo $news_category; ?>"><?php echo $news_category; ?></option>
						<?php 
							$arr_length = count($all_categories); // count the number of categories
							for ($x = 0; $x < $arr_length; $x++) {
						?>
						<option value="<?php echo $all_categories[$x]; ?>"><?php echo $all_categories[$x]; ?></option>
						<?php 
						} 
						?>									 								
					</select>
				</div>
			</div>
			<div class="control-group form-group">
				<div class="controls">
					<input class="form-control" type="text" name="news_title" value="<?php echo $news_title; ?>" />
				</div>
			</div>
			<div class="control-group form-group">
				<div class="controls">
					<input class="form-control"type="text" name="news_author" value="<?php echo $news_author; ?>" />
				</div>
			</div>
			<div class="control-group form-group">
				<div class="controls">
					<input class="form-control"type="text" name="news_counter" value="<?php echo $news_hit_counter; ?>" />
				</div>
			</div>
			<div class="control-group form-group">
				<div class="controls">
					<input class="form-control" type="text" name="news_date" value="<?php echo $news_date; ?>" />
				</div>
			</div>
			<div class="control-group form-group">
				<div class="controls">				
					<textarea class="tinymce form-control custom-control" style="border:none" name="news_message"><?php echo $news_message; ?></textarea>
				</div>
			</div>
			<button class="btn btn-primary pull-right" type="submit" name="submit">Update</button>	
										
		</form>
					
	  </div>
	  <div class="modal-footer">
		
	  </div>
	</div>

  </div>
</div>

<div class="clearfix"></div>

