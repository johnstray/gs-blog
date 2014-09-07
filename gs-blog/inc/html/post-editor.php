	<link href="../plugins/<?php echo BLOGFILE; ?>/inc/uploader/client/fileuploader.css" rel="stylesheet" type="text/css">
	<script src="../plugins/<?php echo BLOGFILE; ?>/inc/uploader/client/fileuploader.js" type="text/javascript"></script>
	<noscript><style>#metadata_window {display:block !important} </style></noscript>
	<h3 class="floated">
	  <?php
	  if ($post_id == null)
	  {
	  	i18n(BLOGFILE.'/ADD_P');
	  }
	  else
	  {
	  	i18n(BLOGFILE.'/EDIT');
	  }
	  ?>
	</h3>
	<div class="edit-nav" >
		<?php
		if ($post_id != null && file_exists(BLOGPOSTSFOLDER.$blog_data->slug.'.xml')) 
		{
			$url = $Blog->get_blog_url('post');
			?>
			<a href="<?php echo $url.$blog_data->slug; ?>" target="_blank">
				<?php i18n(BLOGFILE.'/VIEW'); echo ' '; i18n(BLOGFILE.'/POST'); ?>
			</a>
			<?php
		}
		?>
		<a href="#" id="metadata_toggle">
			<?php i18n(BLOGFILE.'/POST_OPTIONS'); ?>
		</a>
		<div class="clear"></div>
	</div>
	<form class="largeform" action="load.php?id=<?php echo BLOGFILE; ?>&save_post" method="post" accept-charset="utf-8">
	<?php if($post_id != null) { echo "<p><input name=\"post-current_slug\" type=\"hidden\" value=\"$blog_data->slug\" /></p>"; } ?>
	<div id="metadata_window" style="display:none;text-align:left;">
		<?php displayCustomFields(); ?>
		<div class="leftopt">
				<label><?php i18n(BLOGFILE.'/UPLOAD_THUMBNAIL'); ?></label>
			<div class="uploader_container"> 
			    <div id="file-uploader-thumbnail"> 
			        <noscript> 
			            <p><?php i18n(BLOGFILE.'/UPLOAD_ENABLE_JAVASCRIPT'); ?></p>
			        </noscript> 
			    </div> 
			    <script> 
			   		 var uploader = new qq.FileUploader({
				        element: document.getElementById('file-uploader-thumbnail'),
				        // path to server-side upload script
				        action: '../plugins/<?php echo BLOGFILE; ?>/inc/uploader/server/php.php',
			        	onComplete: function(id, fileName, responseJSON){
				        	$('#post-thumbnail').attr('value', responseJSON.newFilename);
				    	}

			    }, '<?php i18n(BLOGFILE.'/POST_THUMBNAIL_LABEL'); ?>');
			    </script>
			</div>
			<input type="text" id="post-thumbnail" name="post-thumbnail" value="<?php echo $blog_data->thumbnail; ?>" style="width:130px;float:right;margin-top:12px !important;" />
		</div>
		<div class="clear"></div>
		</div>

		<?php displayCustomFields('main'); ?>
			<input name="post" type="submit" class="submit" value="<?php i18n(BLOGFILE.'/SAVE_POST'); ?>" />
			&nbsp;&nbsp;<?php i18n(BLOGFILE.'/OR'); ?>&nbsp;&nbsp;
			<a href="load.php?id=<?php echo BLOGFILE; ?>&cancel" class="cancel"><?php i18n(BLOGFILE.'/CANCEL'); ?></a>
			<?php
			if ($post_id != null) 
			{
				?>
				/
				<a href="load.php?id=<?php echo BLOGFILE; ?>" class="cancel">
					<?php i18n(BLOGFILE.'/DELETE'); ?>
				</a>
				<?php
			}
			?>
		</p>
	</form>
	<script>
	  $(document).ready(function(){
	    $("#post-title").focus();
	  });
	</script>