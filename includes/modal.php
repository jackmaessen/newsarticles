<!-- Modal -->
<div id="<?php echo $file_id; ?>" class="modal fade" role="dialog">
  <div class="modal-dialog">

	<!-- Modal content-->
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h3 class="modal-title">Edit Newsmessage</h3>
	  </div>
	  <div class="modal-body">

		<form action="admin.php" method="POST" role="form">	
		
			<input type="hidden" name="edit_file" value="edit" />	
			<input type="hidden" name="file_id" value="<?php echo $file_id; ?>" />
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

<!-- make modal draggable -->
<script src="https://code.jquery.com/ui/1.11.3/jquery-ui.min.js"></script>
<script type="text/javascript">
$('.modal-content').resizable({
  //alsoResize: ".modal-dialog",
  minHeight: 300,
  minWidth: 300
});
$('.modal-dialog').draggable();

$('<?php //echo $file_id; ?>').on('show.bs.modal', function() {
  $(this).find('.modal-body').css({
	'max-height': '100%'
  });
});
</script>