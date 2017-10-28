<?php
if ( isset( $_GET[ 'edit_post' ] )
     && $post_id != null
     && file_exists( BLOGPOSTSFOLDER . $blog_data->slug . '.xml' )
     && !empty( $blog_data->thumbnail ) )
{
    $thumbnail = (string) $blog_data->thumbnail;
    $preview_image = $SITEURL . 'data/uploads/' . $thumbnail; // <!-- Editing Post: Get current image url
}
else
{
    $thumbnail = '';
    $preview_image = $SITEURL . 'plugins/' . BLOGFILE . '/images/missing.png';
}
?>

	<noscript><style>#metadata_window {display:block !important} </style></noscript>
	<h3 class="floated">
	  <?php
	  if ($post_id == null)
	  {
	  	i18n(BLOGFILE.'/ADD_NEW_POST');
	  }
	  else
	  {
	  	i18n(BLOGFILE.'/EDIT_EXISTING_POST');
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
        <?php echo i18n_r(BLOGFILE.'/VIEW_POST'); ?>
			</a>
			<?php
		}
		?>
		<a href="#" id="metadata_toggle">
			<?php i18n(BLOGFILE.'/POST_OPTIONS'); ?>
		</a>
		<div class="clear"></div>
	</div>
	<form class="largeform" action="load.php?id=<?php echo BLOGFILE; ?>&save_post" method="post" accept-charset="utf-8" id="post-editor-form">
	<?php if($post_id != null) { echo "<p><input name=\"post-current_slug\" type=\"hidden\" value=\"$blog_data->slug\" /></p>"; } ?>
	<div id="metadata_window" style="display:none;text-align:left;">
		<?php displayCustomFields(); ?>

        <?php // Image Selector ?>
        <div class="wideopt">
            <div id="uploader">

                <div class="uploaderImageBox">
                    <img src="<?php echo $preview_image; ?>" id="uploaderPreviewImage" />
                    <div><?php i18n(BLOGFILE.'/CLICK_TO_SELECT_IMAGE'); ?></div>
                    <div class="uploaderStatusBar"><div style="width: 0%;"></div></div>
                </div>

                <div class="uploaderFileInfo">
                    <div>
                        <label for="post-thumbnail"><?php i18n(BLOGFILE.'/SELECTED_IMAGE_FILE'); ?>: <small><?php i18n(BLOGFILE.'/SELECTED_IMAGE_FILE_HINT'); ?></small></label>
                        <input type="text" class="text uploaderFileName" id="post-thumbnail" name="post-thumbnail" value="<?php echo $thumbnail; ?>" />
                        <button type="button" class="button" id="uploaderSelectButton"><?php i18n(BLOGFILE.'/SELECT_FILE_BUTTON'); ?></button>
                        <button type="button" class="button singleupload_input" id="uploaderUploadButton" onClick="singleupload_input.click();"><?php i18n(BLOGFILE.'/UPLOAD_FILE_BUTTON'); ?></button>
                        <input type="file" class="uploaderHiddenFileBox" id="singleupload_input" name="post-image" value="" />
                    </div>
                    <div>
                        <label for="post-thumbalt"><?php i18n(BLOGFILE.'/IMAGE_ALTERNATE_TEXT'); ?></label>
                        <input type="text" class="text" id="post-thumbalt" name="post-thumbalt" value="<?php echo $blog_data->thumbalt; ?>" />
                    </div>
                </div>
                <div class="clear"></div>

                <script><?php GLOBAL $SITEURL, $GSADMIN, $SESSIONHASH; ?>
                    $(function() {$("#uploaderUploadButton").singleupload({
                        action: '<?php echo tsl($SITEURL); ?>plugins/<?php echo BLOGFILE; ?>/uploader.php',
                        sessionHash: '<?php echo $SESSIONHASH; ?>',
                        filePath: '<?php echo $Blog->getSettingsData("uploaderpath"); ?>', // <-- Insert filepath here - get from settings
                        inputId: 'singleupload_input',
                        progressBar: '.uploaderStatusBar',
                        onError: function(code) {
                            $('.uploaderStatusBar div').css('background-color', 'red');
                            $('.uploaderStatusBar div').css('width', '100%');
                            alert('Uploader Error: ' + res.code); },
                        onSuccess: function(url, data) {
                            if (url.indexOf('/') === 0) {url = url.substring(1);}
                            $('input[name="post-thumbnail"]').prop('value', url);
                            $('#uploaderPreviewImage').attr('src', '<?php echo tsl($SITEURL); ?>data/uploads/' + url);
                            $('.uploaderStatusBar').css('display', 'none');
                        },
                        onProgress: function(event) {
                            if (event.lengthComputable) {
                                var percent = Math.round( 100 * (event.loaded / event.total) );
                                $('.uploaderStatusBar div').css('width', percent+'%');
                            } else {
                                console.warn('File size is not computable. Upload progress bar will be disabled.');
                            }
                        } // <!-- Implement this at a later stage
                    });});
                    $('.uploaderImageBox, #uploaderSelectButton').on('click', function() {
                        window.open('<?php echo tsl($SITEURL.$GSADMIN); ?>filebrowser.php?path=&returnid=post-thumbnail&func=triggerUploaderChange&type=image&CKEditorFuncNum=0','_blank','scrollbars=1,resizable=1,height=300,width=450');
                    });
                    $('.uploaderFileName').on('change', function() {
                        $('#uploaderPreviewImage').attr('src', $(this).val());
                        $(this).val($(this).val().replace('<?php echo tsl($SITEURL); ?>data/uploads/', ''));
                    });
                    function triggerUploaderChange(id) {$('#'+id).trigger('change');}
                    $('#uploaderPreviewImage').error(function(){$(this).attr('src', '<?php echo $SITEURL; ?>plugins/<?php echo BLOGFILE; ?>/images/missing.png');});
                </script>

            </div>
        </div>
        <?php // END Image Selector ?>

		<div class="clear"></div>
        <?php exec_action('edit-extras'); // Added to allow for compatibility with other plugins ?>
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
	</form>
    <script>
        $(document).ready(function(){
            $("#post-title").focus();
            /* Add 'Save Post' button to sidebar */
            $("#sidebar .snav").after('<p id="js_submit_line"><input id="page-submit" name="post" type="submit" class="submit" value="<?php i18n(BLOGFILE.'/SAVE_POST'); ?>" /></p>');
            $("#page-submit").on('click',function(){
                $("#post-editor-form").submit();
            });
	    });
	</script>
