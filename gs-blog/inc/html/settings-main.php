<?php if(!defined('IN_GS')){die('You cannot load this file directly!');} // Security Check
/**
 * @file: settings-main.php
 * @package: GetSimple Blog [plugin]
 * @action: This file contains the HTML for the Settings page
 * @author: John Stray [https://www.johnstray.id.au/]
 */ ?>
 
<h3 class="floated" style="float:left;"><?php i18n(BLOGFILE.'/BLOG_SETTINGS'); ?></h3>
<div class="edit-nav">
  <p class="text 1">
    <a href="load.php?id=<?php echo BLOGFILE; ?>&settings=rss"><?php i18n(BLOGFILE.'/AUTOIMPORTER_BUTTON'); ?></a>
  </p>
  <div class="clear"></div>
</div>
<p class="text 2"><?php i18n(BLOGFILE.'/SETTINGS_MAIN_DESC'); ?></p>
<form class="largeform" action="load.php?id=<?php echo BLOGFILE; ?>&settings" method="post" accept-charset="utf-8">
  <div class="leftsec">
    <p>
      <label for="blog-url"><?php i18n(BLOGFILE.'/PAGE_URL'); ?>:</label>
      <select class="text" name="blog_url">
        <?php
        $pages = get_available_pages();
        foreach ($pages as $page) {
          $slug = $page['slug'];
          if ($slug == $Blog->getSettingsData("blogurl")) {
            echo "<option value=\"$slug\" selected=\"selected\">$slug</option>\n";
          } else {
            echo "<option value=\"$slug\">$slug</option>\n";	
          }
        }
        ?>
      </select>
    </p>
  </div>
  <div class="rightsec">
    <p>
      <label for="postperpage"><?php i18n(BLOGFILE.'/POSTS_PER_PAGE'); ?>:</label>
      <input class="text" type="text" name="postperpage" value="<?php echo $Blog->getSettingsData("postperpage"); ?>" />
    </p>
  </div>
  <div class="clear"></div>
  <div class="leftsec">
    <p><label for="postformat"><?php i18n(BLOGFILE.'/EXCERPT_OPTION'); ?>:</label>
    <span style="width:49%;display:inline-block;">
      <input name="postformat" type="radio" value="Y" <?php if ($Blog->getSettingsData("postformat") == 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
      &nbsp;<?php i18n(BLOGFILE.'/FULL_TEXT'); ?>
    </span><span style="width:49%;display:inline-block;">
      <input name="postformat" type="radio" value="N" <?php if ($Blog->getSettingsData("postformat") != 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
      &nbsp;<?php i18n(BLOGFILE.'/EXCERPT'); ?>
    </span></p>
  </div>
  <div class="rightsec">
    <p>
      <label for="excerptlength"><?php i18n(BLOGFILE.'/EXCERPT_LENGTH'); ?>:</label>
      <input class="text" type="text" name="excerptlength" value="<?php echo $Blog->getSettingsData("excerptlength"); ?>" />
    </p>
  </div>
  <div class="clear"></div>
  <div class="leftsec">
    <p><label for="archivepostcount"><?php i18n(BLOGFILE.'/DISPLAY_POST_COUNT_ARCH'); ?>:</label>
    <span style="width:49%;display:inline-block;">
      <input name="archivepostcount" type="radio" value="Y" <?php if ($Blog->getSettingsData("archivepostcount") == 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
      &nbsp;<?php i18n(BLOGFILE.'/YES'); ?>
    </span><span style="width:49%;display:inline-block;">
      <input name="archivepostcount" type="radio" value="N" <?php if ($Blog->getSettingsData("archivepostcount") != 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
      &nbsp;<?php i18n(BLOGFILE.'/NO'); ?>
    </span></p>
  </div>
  <div class="rightsec">
    <p>
      <label for="recentposts"><?php i18n(BLOGFILE.'/RECENT_POSTS'); ?>:</label>
      <input class="text" type="text" name="recentposts" value="<?php echo $Blog->getSettingsData("recentposts"); ?>" />
    </p>
  </div>
  <div class="clear"></div>
  <div class="leftsec">
    <p>
      <label for="rsstitle"><?php i18n(BLOGFILE.'/RSS_TITLE'); ?>:</label>
      <input class="text" type="text" name="rsstitle" value="<?php echo $Blog->getSettingsData("rsstitle"); ?>" />
    </p>
  </div>
  <div class="rightsec">
    <p>
      <label for="rssdescription"><?php i18n(BLOGFILE.'/RSS_DESCRIPTION'); ?>:</label>
      <input class="text" type="text" name="rssdescription" value="<?php echo $Blog->getSettingsData("rssdescription"); ?>" />
    </p>
  </div>
  <div class="leftsec">
    <p>
      <label for="uploaderpath"><?php i18n(BLOGFILE.'/UPLOADER_PATH'); ?>:</label>
      <span class="hint"><?php i18n(BLOGFILE.'/UPLOADER_PATH_HINT'); ?></span>
      <input class="text" type="text" name="uploaderpath" value="<?php echo $Blog->getSettingsData("uploaderpath"); ?>" />
    </p>
  </div>
  <div class="clear"></div>
  <h3 style="font-size:15px;"><?php i18n(BLOGFILE.'/HTACCESS_HEADLINE'); ?></h3>
  <?php global $PRETTYURLS; if ($PRETTYURLS == 1) { ?>
  <p>
    <label for="prettyurls"><?php i18n(BLOGFILE.'/PRETTY_URLS'); ?>:</label>
    <input name="prettyurls" type="radio" value="Y" <?php if ($Blog->getSettingsData("prettyurls") == 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
    &nbsp;<?php i18n(BLOGFILE.'/YES'); ?>
    <span style="margin-left: 30px;">&nbsp;</span>
    <input name="prettyurls" type="radio" value="N" <?php if ($Blog->getSettingsData("prettyurls") != 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
    &nbsp;<?php i18n(BLOGFILE.'/NO'); ?>
  </p>
  <p>
    <span style="color:red;font-weight:bold;"><a id="see_htaccess" href="#htaccess"><?php i18n(BLOGFILE.'/VIEW_HTACCESS'); ?></a></span> - 
    <span class="hint"><?php i18n(BLOGFILE.'/PRETTY_URLS_PARA'); ?></span>
  </p>
  <div style="display:none;">
    <div id="htaccess">
      <pre>
AddDefaultCharset UTF-8
Options -Indexes

# <?php i18n(BLOGFILE.'/HTACCESS_1'); ?>
&lt;Files ~ "\.xml$"&gt;
Order allow,deny
Deny from all
Satisfy All
&lt;/Files&gt;
&lt;Files sitemap.xml&gt;
Order allow,deny
Allow from all
Satisfy All
&lt;/Files&gt;

RewriteEngine on

# <?php i18n(BLOGFILE.'/HTACCESS_2'); ?>
# <?php i18n(BLOGFILE.'/HTACCESS_3'); ?>
RewriteBase /

RewriteRule ^<?php if($Blog->getSettingsData("blogurl") != 'index') { echo $Blog->getSettingsData("blogurl").'/'; } ?>post/([^/.]+)/?$ index.php?id=<?php echo $Blog->getSettingsData("blogurl"); ?>&post=$1 [L]
RewriteRule ^<?php if($Blog->getSettingsData("blogurl") != 'index') { echo $Blog->getSettingsData("blogurl").'/'; } ?>tag/([^/.]+)/?$ index.php?id=<?php echo $Blog->getSettingsData("blogurl"); ?>&tag=$1 [L]
RewriteRule ^<?php if($Blog->getSettingsData("blogurl") != 'index') { echo $Blog->getSettingsData("blogurl").'/'; } ?>page/([^/.]+)/?$ index.php?id=<?php echo $Blog->getSettingsData("blogurl"); ?>&page=$1 [L]
RewriteRule ^<?php if($Blog->getSettingsData("blogurl") != 'index') { echo $Blog->getSettingsData("blogurl").'/'; } ?>archive/([^/.]+)/?$ index.php?id=<?php echo $Blog->getSettingsData("blogurl"); ?>&archive=$1 [L]
RewriteRule ^<?php if($Blog->getSettingsData("blogurl") != 'index') { echo $Blog->getSettingsData("blogurl").'/'; } ?>category/([^/.]+)/?$ index.php?id=<?php echo $Blog->getSettingsData("blogurl"); ?>&category=$1 [L]

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule /?([A-Za-z0-9_-]+)/?$ index.php?id=$1 [QSA,L]
      </pre>
    </div>
  </div>
  <?php 
 } 
 else
 {
  echo '<p>'.i18n_r(BLOGFILE.'/BLOG_PRETTY_NOTICE').'.</p>';
 }
 ?>
  <div style="margin-top:20px;">
    <span><input class="submit" type="submit" name="blog_settings" value="<?php i18n(BLOGFILE.'/SAVE_SETTINGS'); ?>" /></span>
    &nbsp;&nbsp;<?php i18n(BLOGFILE.'/OR'); ?>&nbsp;&nbsp;
    <a href="load.php?id=<?php echo BLOGFILE; ?>&cancel" class="cancel"><?php i18n(BLOGFILE.'/CANCEL'); ?></a>
  </div>
</form>
<script type="text/javascript">
  $("a#css_help").fancybox({
    'hideOnContentClick': true
  });
  $("a#blog_page_help").fancybox({
    'hideOnContentClick': true
  });
  $("a#see_htaccess").fancybox({
    'hideOnContentClick': true
  });
</script>
