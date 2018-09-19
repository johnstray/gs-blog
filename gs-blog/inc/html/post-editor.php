<?php GLOBAL $SITEURL, $GSADMIN, $SESSIONHASH, $EDHEIGHT, $EDLANG, $EDTOOL, $EDOPTIONS, $HTMLEDITOR;
if ( isset( $_GET['edit_post'] )
    && $post_id != null
    && file_exists( BLOGPOSTSFOLDER . $blog_data->slug .'.xml' )
    && !empty( $blog_data->thumbnail ) )
{
    $thumbnail = (string) $blog_data->thumbnail;
    $preview_image = $SITEURL . 'data/uploads/' . $thumbnail;
} else {
    $thumbnail = '';
    $preview_image = $SITEURL . 'plugins/' . BLOGFILE . '/images/missing.png';
} ?>

<h3 class="floated">
    <?php if ( $post_id == null ) {
        i18n( BLOGFILE . '/ADD_NEW_POST' );
    } else {
        i18n( BLOGFILE . '/EDIT_EXISTING_POST' );
    } ?>
</h3>

<div class="edit-nav">
    <?php if ( $post_id !=null && file_exists( BLOGPOSTSFOLDER . $blog_data->slug . '.xml' ) ) {
        $url = $Blog->get_blog_url('post') . $blog_data->slug;
        echo '<a href="' . $url . '" target="_blank">' . i18n_r( BLOGFILE . '/VIEW_POST' ) . '</a>';
    } ?>
    <a href="#" id="metadata_toggle"><?php i18n( BLOGFILE . '/POST_OPTIONS' ); ?></a>
    <div class="clear"></div>
</div>

<form class="largeform" action="load.php?id=<?php echo BLOGFILE; ?>&save_post" method="post" id="post-editor-form">
    <?php if ( $post_id != null ) { echo '<input type="hidden" name="post-current_slug" value="' . $blog_data->slug . '" />'; } ?>

    <div id="titlezone" style="margin-bottom: 20px;">
        <label for="post-title" style="display: none;"><?php i18n( BLOGFILE . '/POST_TITLE' ); ?>:</label>
        <input type="text" class="text title" name="post-title" id="post-title" value="<?php echo $blog_data->title; ?>" placeholder="<?php i18n( BLOGFILE . '/POST_TITLE' ); ?>" />
    </div>

    <noscript><style>#metadata_window { display: block !important; }</style></noscript>
    <div id="metadata_window" style="display: none;">
        <div class="leftopt">
            <label for="post-slug"><?php i18n( BLOGFILE . '/SLUG_URL' ); ?>:</label>
            <span class="hint"><?php i18n( BLOGFILE . '/SLUG_URL_HINT' ); ?></span>
            <input type="text" class="text" id="post-slug" name="post-slug" value="<?php echo $blog_data->slug; ?>" />
        </div>
        <div class="rightopt"><!-- Blank space --></div>
        <div class="leftopt">
            <label for="post-date"><?php i18n( BLOGFILE . '/PUBLICATION_DATE' ); ?>:</label>
            <span class="hint"><?php i18n( BLOGFILE . '/PUBLICATION_DATE_HINT' ); ?></span>
            <input type="text" class="text" id="post-date" name="post-date" value="<?php echo $blog_data->date; ?>" />
        </div>
        <div class="rightopt">
            <label for="post-tags"><?php i18n( BLOGFILE . '/POST_TAGS' ); ?>:</label>
            <span class="hint"><?php i18n( BLOGFILE . '/POST_TAGS_HINT' ); ?></span>
            <input type="text" class="text" id="post-tags" name="post-tags" value="<?php echo $blog_data->tags; ?>" />
        </div>
        <div class="leftopt">
            <label for="post-author"><?php i18n( BLOGFILE . '/POST_AUTHOR' ); ?>:</label>
            <span class="hint"><?php i18n( BLOGFILE . '/AUTHOR_NAME_HINT' ); ?></span>
            <input type="text" class="text" id="post-author" name="post-author" value="<?php echo $blog_data->author; ?>" />
        </div>
        <div class="rightopt">
            <label for="post-category"><?php i18n( BLOGFILE . '/ASSIGNED_CATEGORY' ); ?>:</label>
            <span class="hint"><?php i18n( BLOGFILE . '/ASSIGNED_CATEGORY_HINT' ); ?></span>
            <select class="text" id="post-category" name="post-category">
                <option value="none">----- <?php i18n( BLOGFILE . '/NONE' ); ?> -----</option>
                <?php category_dropdown( $blog_data->category ); ?>
            </select>
        </div>
        <div class="wideopt">
            <div id="uploader">
                <div class="uploaderImageBox">
                    <img src="<?php echo $preview_image; ?>" id="uploaderPreviewImage" alt="<?php i18n( BLOGFILE . '/CLICK_TO_SELECT_IMAGE' ); ?>" />
                    <div><?php i18n( BLOGFILE . '/CLICK_TO_SELECT_IMAGE' ); ?></div>
                    <div class="uploaderStatusBar"><div style="width: 0%;"></div></div>
                </div>
                <div class="uploaderFileInfo">
                    <div>
                        <label for="post-thumbnail">
                            <?php i18n( BLOGFILE . '/SELECTED_IMAGE_FILE' ); ?>:
                            <small><?php i18n( BLOGFILE . '/SELECTED_IMAGE_FILE_HINT' ); ?></small>
                        </label>
                        <input type="text" class="text uploaderFileName" id="post-thumbnail" name="post-thumbnail" value="<?php echo $thumbnail; ?>" />
                        <button type="button" class="button" id="uploaderSelectButton">
                            <?php i18n( BLOGFILE . '/SELECT_FILE_BUTTON' ); ?>
                        </button>
                        <button type="button" class="button singleupload_input" id="uploaderUploadButton" onclick="singleupload_input.click();">
                            <?php i18n( BLOGFILE . '/UPLOAD_FILE_BUTTON' ); ?>
                        </button>
                        <input type="file" class="uploaderHiddenFileBox" id="singleupload_input" name="post-image" value="" />
                    </div>
                    <div>
                        <label for="post-thumbalt"><?php i18n( BLOGFILE . '/IMAGE_ALTERNATE_TEXT' ); ?></label>
                        <input type="text" class="text" id="post-thumbalt" name="post-thumbalt" value="<?php echo $blog_data->thumbalt; ?>" />
                    </div>
                </div>
                <div class="clear"></div>
                <script src="../plugins/<?php echo BLOGFILE; ?>/js/image_upload.js"></script>
                <script>
                    $(function () {
                        $("#uploaderUploadButton").singleupload({
                            action: '<?php echo tsl($SITEURL); ?>plugins/<?php echo BLOGFILE; ?>/uploader.php',
                            sessionHash: '<?php echo $SESSIONHASH; ?>',
                            filePath: '<?php echo $Blog->getSettingsData('uploaderpath'); ?>',
                            inputId: 'singleupload_input',
                            progressBar: '.uploadStatusBar',
                            onError: function (code) {
                                $(".uploaderStatusBar div").css({'background-color': "red", 'width': "100%"});
                                console.error( "Image Uploader: " + code );
                                alert( "Image Uploader encountered an error: " + code );
                            }, onSuccess: function ( url, data ) {
                                if ( url.indexOf('/') == 0 ) { url = url.substring(1); }
                                $('input[name="post-thumbnail"]').prop('value', url);
                                $('#uploaderPreviewImage').attr('src', '<?php echo tsl($SITEURL); ?>data/uploads/' + url);
                                $('.uploaderStatusBar').css('display', 'none');
                            }, onProgress: function ( event ) {
                                if ( event.lengthComputable ) {
                                    var percent = Math.round( 100 * (event.loaded / event.total) );
                                    $('.uploaderStatusBar div').css('width', percent + '%');
                                } else {
                                    console.warn('Image Uploader: File size is not computable. Upload progress bar will be disabled.');
                                }
                            }
                        });
                    });
                    $('.uploaderImageBox, #uploaderSelectButton').on('click', function () {
                        window.open('<?php echo tsl($SITEURL.$GSADMIN); ?>filebrowser.php?path=&returnid=post-thumbnail&func=triggerUploaderChange&type=image&CKEditorFuncNum=0',
                            '_blank', 'scrollbars=1,resizable=1,width=730,height=500');
                    });
                    $('.uploaderFileName').on('change', function() {
                        $('#uploaderPreviewImage').attr('src', $(this).val());
                        $(this).val($(this).val().replace('<?php echo tsl($SITEURL); ?>data/uploads/', ''));
                    });
                    $('#uploaderPreviewImage').on('error', function(){
                        $(this).attr('src', '<?php echo $SITEURL; ?>plugins/<?php echo BLOGFILE; ?>/images/missing.png');
                        console.warn('Image Uploader: Selected image not found on server. Using placeholder image.');
                    });
                    function triggerUploaderChange(id) {$('#'+id).trigger('change');}
                </script>
            </div>
        </div>
        <div class="clear"></div>
        <?php exec_action( 'edit-extras' ); ?>
    </div>

    <div id="posteditor">
        <label for="post-content" style="display: none;"><?php i18n( BLOGFILE . '/POST_CONTENT' ); ?>:</label>
        <textarea id="post-content" name="post-content"><?php echo $blog_data->content; ?></textarea>
        <?php if ( $HTMLEDITOR == "TRUE" ) {
            if(isset($EDTOOL)) $EDTOOL = returnJsArray($EDTOOL);
            if(isset($toolbar)) $toolbar = returnJsArray($toolbar); // handle plugins that corrupt this
            else if(strpos(trim($EDTOOL),'[[')!==0 && strpos(trim($EDTOOL),'[')===0){ $EDTOOL = "[$EDTOOL]"; }
            if(isset($toolbar) && strpos(trim($toolbar),'[[')!==0 && strpos($toolbar,'[')===0){ $toolbar = "[$toolbar]"; }
            $toolbar = isset($EDTOOL) ? ",toolbar: ".trim($EDTOOL,",") : '';
            $options = isset($EDOPTIONS) ? ','.trim($EDOPTIONS,",") : '';
            ?>
            <script src="template/js/ckeditor/ckeditor.js"></script>
            <script>
                CKEDITOR.replace('post-content', {
                    skin: 'getsimple',
                    forcePasteAsPlainText: false,
                    language: '<?php echo $EDLANG; ?>',
                    defaultLanguage: '<?php echo $EDLANG; ?>',
                    entities: false,
                    //uiColor: '#eeeeee',
                    height: '<?php echo $EDHEIGHT; ?>',
                    baseHref: '<?php echo $SITEURL; ?>',
                    filebrowserBrowseUrl: 'filebrowser.php?type=all',
                    filebrowserImageBrowseUrl: 'filebrowser.php?type=images',
                    filebrowserWindowWidth: '730',
                    filebrowserWindowHeight: '500'
                    <?php echo $toolbar; ?>
                    <?php echo $options; ?>
                });
                CKEDITOR.instances["post-content"].on("instanceReady", InstanceReadyEvent);
                function InstanceReadyEvent( ev ) {
                    _this = this;
                    this.document.on( "keyup", function () {
                        $("#posteditor #post-content").trigger('change');
                        _this.resetDirty();
                    });
                    this.timer = setInterval( function () { trackChanges(_this) }, 500 );
                }
                function trackChanges ( editor ) {
                    if ( editor.checkDirty() ) {
                        $("#posteditor #post-content").trigger('change');
                        editor.resetDirty();
                    }
                }
                <?php exec_action('html-editor-init'); ?>
                var warnme = false; var pageisdirty = false;
                $("#pagechangednotify").hide();
                window.onbeforeunload = function () {
                    if ( warnme == true || pageisdirty == true ) {
                        return "<?php i18n( 'UNSAVED_INFORMATION' ); ?>";
                    }
                };
                $('#posteditor #post-content, #metadata_window input, #metadata_window select, #otheroptions select')
                    .bind('change keypress paste textInput input',function(){
                        warnme = true;
                        pageisdirty = false;
                        autoSaveInd();
                    });
                function autoSaveInd () {
                    $("#pagechangednotify").show();
                    $("#pagechangednotify").text("<?php i18n( 'PAGE_UNSAVED' ); ?>");
                    $("input[type=submit]").css('border-color', '#CC0000');
                }
            </script>
        <?php } ?>
    </div>

    <div id="otheroptions">
        <div class="leftopt">
            <label for="post-visibilitiy"><?php i18n( BLOGFILE . '/POST_VISIBILITY' ); ?>:</label>
            <span class="hint"><?php i18n( BLOGFILE . '/POST_VISIBILITY_HINT' ); ?></span>
            <select class="text" id="post-visibilitiy" name="post-visibility">
                <option value="normal"><?php i18n( BLOGFILE . '/NORMAL' ); ?></option>
                <option value="private"><?php i18n( BLOGFILE . '/PRIVATE' ); ?></option>
            </select>
        </div>
        <div class="rightopt">
            <label for="post-template"><?php i18n( BLOGFILE . '/POST_TEMPLATE' ); ?>:</label>
            <span class="hint"><?php i18n( BLOGFILE . '/POST_TEMPLATE_HINT' ); ?></span>
            <select class="text" id="post-template" name="post-template">
                <?php generateTemplateList( $blog_data->template ); ?>
            </select>
        </div>
        <div class="clear"></div>
    </div>

    <div id="edit">
        <div id="submit_line" class="saveButtonZone">
            <input type="submit" class="submit" id="page_submit" value="<?php i18n(BLOGFILE . '/SAVE_POST' ); ?>" />
            <div id="dropdown">
                <h6 class="dropdownaction"><?php i18n( 'ADDITIONAL_ACTIONS' ); ?></h6>
                <ul class="dropdownmenu">
                    <li id="cancel-updates" class="alertme">
                        <a href="load.php?id=<?php echo BLOGFILE; ?>&manage"><?php i18n( 'CANCEL' ); ?></a>
                    </li>
                    <?php if ( $post_id !== null ) { ?><li class="alertme">
                        <a href="load.php?id=<?php echo BLOGFILE; ?>$delete_post=<?php echo $blog_data->slug; ?>"><?php echo strip_tags(i18n_r( 'ASK_DELETE' )); ?></a>
                    </li><?php } ?>
                </ul>
            </div>
        </div>
    </div>

</form>

<script>
    $(document).ready(function() {
        //$("#sidebar .snav").after('<p id="js_submit_line"><input type="submit" class="submit" id="page-submit" value="<?php i18n( BLOGFILE . '/SAVE_POST' ); ?>" /></p>');
        $("#sidebar .snav").after( $("#edit").clone() );
        $("#sidebar #edit").after( '<div id="pagechangednotify"></div>' );
        $('#sidebar #edit #page_submit').on('click', function() {
            $("#post-editor-form").submit();
        });
    });
</script>
