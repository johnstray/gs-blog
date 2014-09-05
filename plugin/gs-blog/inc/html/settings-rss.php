<?php if(!defined('IN_GS')){die('You cannot load this file directly!');} // Security Check
/**
 * @file: settings-main.php
 * @package: GetSimple Blog [plugin]
 * @action: This file contains the HTML for the Settings page
 * @author: John Stray [https://www.johnstray.id.au/]
 */ ?>

<h3 class="floated" style="float:left;"><?php i18n(BLOGFILE.'/RSS_SETTINGS_HEADER'); ?></h3>
<div class="edit-nav">
  <p class="text 1">
    <a href="load.php?id=<?php echo BLOGFILE; ?>&settings"><?php i18n(BLOGFILE.'/MAIN_SETTINGS_BUTTON'); ?></a>
  </p>
  <div class="clear"></div>
</div>
<p class="text 2"><?php i18n(BLOGFILE.'/SETTINGS_RSS_DESC'); ?></p>
<form class="largeform" action="load.php?id=<?php echo BLOGFILE; ?>&settings" method="post" accept-charset="utf-8">
  <div class="leftsec">
    <p>
      <label for="auto_importer"><?php i18n(BLOGFILE.'/RSS_IMPORTER'); ?>:</label>
      <input name="auto_importer" type="radio" value="Y" <?php if ($Blog->getSettingsData("autoimporter") == 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
      &nbsp;<?php i18n(BLOGFILE.'/YES'); ?>
      <span style="margin-left: 30px;">&nbsp;</span>
      <input name="auto_importer" type="radio" value="N" <?php if ($Blog->getSettingsData("autoimporter") != 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
      &nbsp;<?php i18n(BLOGFILE.'/NO'); ?>
    </p>
  </div>
  <div class="rightsec">
    <p>
      <label for="rss_include"><?php i18n(BLOGFILE.'/RSS_CONTENT_DESCRIPTION'); ?>:</label>
      <input name="rss_include" type="radio" value="Y" <?php if ($Blog->getSettingsData("rssinclude") == 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
      &nbsp;<?php i18n(BLOGFILE.'/RSS_CONTENT'); ?>
      <span style="margin-left: 30px;">&nbsp;</span>
      <input name="rss_include" type="radio" value="N" <?php if ($Blog->getSettingsData("rssinclude") != 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
      &nbsp;<?php i18n(BLOGFILE.'/RSS_DESCRIPTION'); ?>
    </p>
  </div>
  <div class="clear"></div>
  <div class="leftsec">
    <p>
      <label for="rss_title"><?php i18n(BLOGFILE.'/RSS_TITLE'); ?>:</label>
      <input class="text" type="text" name="rss_title" value="<?php echo $Blog->getSettingsData("rsstitle"); ?>" />
    </p>
  </div>
  <div class="rightsec">
    <p>
      <label for="rss_description"><?php i18n(BLOGFILE.'/RSS_DESCRIPTION'); ?>:</label>
      <input class="text" type="text" name="rss_description" value="<?php echo $Blog->getSettingsData("rssdescription"); ?>" />
    </p>
  </div>
  <div class="clear"></div>
  <div class="leftsec">
    <p>
      <label for="recent_posts"><?php i18n(BLOGFILE.'/RSS_IMPORTER_PASS'); ?>:</label>
      <input class="text" type="text" name="auto_importer_pass" value="<?php echo $Blog->getSettingsData("autoimporterpass"); ?>" />
    </p>
  </div>
  <div class="rightsec">
    <p>
      <label for="rss_feed_num_posts"><?php i18n(BLOGFILE.'/RSS_FEED_NUM_POSTS'); ?>:</label>
      <input class="text" type="text" name="rss_feed_num_posts" value="<?php echo $Blog->getSettingsData("rssfeedposts"); ?>" />
    </p>
  </div>
  <div class="clear"></div>
  <p><?php i18n(BLOGFILE.'/AUTO_IMPORTER_DESC'); ?></p>
  <code><strong>lynx -dump <?php echo $SITEURL; ?>index.php?id=<?php echo $Blog->getSettingsData("blogurl"); ?>&import=<?php echo $Blog->getSettingsData("autoimporterpass"); ?> > /dev/null</strong></code>
  <div style="margin-top:20px;">
    <span><input class="submit" type="submit" name="blog_settings" value="<?php i18n(BLOGFILE.'/SAVE_SETTINGS'); ?>" /></span>
    &nbsp;&nbsp;<?php i18n(BLOGFILE.'/OR'); ?>&nbsp;&nbsp;
    <a href="load.php?id=<?php echo BLOGFILE; ?>&cancel" class="cancel"><?php i18n(BLOGFILE.'/CANCEL'); ?></a>
  </div>
</form>
