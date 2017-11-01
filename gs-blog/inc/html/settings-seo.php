<?php if(!defined('IN_GS')){die('You cannot load this file directly!');} // Security Check
/**
 * @file: settings-main.php
 * @package: GetSimple Blog [plugin]
 * @action: This file contains the HTML for the Settings page
 * @author: John Stray [https://www.johnstray.id.au/]
 */ ?>

<style>
    .leftsec {box-sizing:border-box;padding-right:5px;}
    .rightsec {box-sizing:border-box; padding-left:5px;}
    input, textarea {box-sizing:border-box;}
</style>

<h3 class="floated" style="float:left;">Search Engine Optimisation Settings</h3>
<div class="edit-nav">
    <p class="text 1">
        <a href="load.php?id=<?php echo BLOGFILE; ?>&settings"><?php i18n(BLOGFILE.'/MAIN_SETTINGS_BUTTON'); ?></a>
        <a href="load.php?id=<?php echo BLOGFILE; ?>&settings=rss"><?php i18n(BLOGFILE.'/AUTOIMPORTER_BUTTON'); ?></a>
    </p>
    <div class="clear"></div>
</div>

<p class="text 2"><?php i18n(BLOGFILE.'/SEO_SETTINGS_INTRO'); ?></p>

<form class="largeform settings" action="load.php?id=<?php echo BLOGFILE; ?>&settings=seo" method="post" accept-charset="utf-8">
    <div class="leftsec">
        <p>
            <label for="categoriesdesc"><?php i18n(BLOGFILE.'/SEO_CATEGORIES_DESC'); ?>:</label>
            <span class="hint"><?php i18n(BLOGFILE.'/SEO_CATEGORIES_HINT'); ?></span>
            <textarea class="text" name="categoriesdesc"><?php echo $Blog->getSettingsData("categoriesdesc"); ?></textarea>
            <label for="categoriesdescshow" style="display:inline;padding-left:40px;"><?php i18n(BLOGFILE.'/SEO_SHOW_ON_PAGE'); ?> : </label>
            <span style="float:right;padding-right:10px;">
                <span style="padding-right:40px;">
                    <input type="radio" name="categoriesdescshow" value="true" style="margin-right:5px;" <?php if ($Blog->getSettingsData("categoriesdescshow") == 'true') echo 'checked="checked"'; ?> /><?php i18n(BLOGFILE.'/YES'); ?>
                </span>
                <span style="margin-right:40px;">
                    <input type="radio" name="categoriesdescshow" value="false" style="margin-right:5px;" <?php if ($Blog->getSettingsData("categoriesdescshow") == 'false') echo 'checked="checked"'; ?> /><?php i18n(BLOGFILE.'/NO'); ?>
                </span>
            </span>

        </p>
    </div>
    <div class="rightsec">
        <p>
            <label for="archivesdesc"><?php i18n(BLOGFILE.'/SEO_ARCHIVES_DESC'); ?>:</label>
            <span class="hint"><?php i18n(BLOGFILE.'/SEO_ARCHIVES_HINT'); ?></span>
            <textarea class="text" name="archivesdesc"><?php echo $Blog->getSettingsData("archivesdesc"); ?></textarea>
            <label for="archivesdescshow" style="display:inline;padding-left:40px;"><?php i18n(BLOGFILE.'/SEO_SHOW_ON_PAGE'); ?> : </label>
            <span style="float:right;padding-right:10px;">
                <span style="padding-right:40px;">
                    <input type="radio" name="archivesdescshow" value="true" style="margin-right:5px;" <?php if ($Blog->getSettingsData("archivesdescshow") == 'true') echo 'checked="checked"'; ?> /><?php i18n(BLOGFILE.'/YES'); ?>
                </span>
                <span style="margin-right:40px;">
                    <input type="radio" name="archivesdescshow" value="false" style="margin-right:5px;" <?php if ($Blog->getSettingsData("archivesdescshow") == 'false') echo 'checked="checked"'; ?> /><?php i18n(BLOGFILE.'/NO'); ?>
                </span>
            </span>
        </p>
    </div>

    <div class="clear"></div>

    <div class="leftsec">
        <p>
            <label for="tagsdesc"><?php i18n(BLOGFILE.'/SEO_TAGS_DESC'); ?>:</label>
            <span class="hint"><?php i18n(BLOGFILE.'/SEO_TAGS_HINT'); ?></span>
            <textarea class="text" name="tagsdesc"><?php echo $Blog->getSettingsData("tagsdesc"); ?></textarea>
            <label for="tagsdescshow" style="display:inline;padding-left:40px;"><?php i18n(BLOGFILE.'/SEO_SHOW_ON_PAGE'); ?> : </label>
            <span style="float:right;padding-right:10px;">
                <span style="padding-right:40px;">
                    <input type="radio" name="tagsdescshow" value="true" style="margin-right:5px;" <?php if ($Blog->getSettingsData("tagsdescshow") == 'true') echo 'checked="checked"'; ?> /><?php i18n(BLOGFILE.'/YES'); ?>
                </span>
                <span style="margin-right:40px;">
                    <input type="radio" name="tagsdescshow" value="false" style="margin-right:5px;" <?php if ($Blog->getSettingsData("tagsdescshow") == 'false') echo 'checked="checked"'; ?> /><?php i18n(BLOGFILE.'/NO'); ?>
                </span>
            </span>
        </p>
    </div>
    <div class="rightsec">
        <p>
            <label for="searchdesc"><?php i18n(BLOGFILE.'/SEO_SEARCH_DESC'); ?>:</label>
            <span class="hint"><?php i18n(BLOGFILE.'/SEO_SEARCH_HINT'); ?></span>
            <textarea class="text" name="searchdesc"><?php echo $Blog->getSettingsData("searchdesc"); ?></textarea>
            <label for="searchdescshow" style="display:inline;padding-left:40px;"><?php i18n(BLOGFILE.'/SEO_SHOW_ON_PAGE'); ?> : </label>
            <span style="float:right;padding-right:10px;">
                <span style="padding-right:40px;">
                    <input type="radio" name="searchdescshow" value="true" style="margin-right:5px;" <?php if ($Blog->getSettingsData("searchdescshow") == 'true') echo 'checked="checked"'; ?> /><?php i18n(BLOGFILE.'/YES'); ?>
                </span>
                <span style="margin-right:40px;">
                    <input type="radio" name="searchdescshow" value="false" style="margin-right:5px;" <?php if ($Blog->getSettingsData("searchdescshow") == 'false') echo 'checked="checked"'; ?> /><?php i18n(BLOGFILE.'/NO'); ?>
                </span>
            </span>
        </p>
    </div>

    <div class="clear"></div>

    <div class="saveButtonZone">
        <span><input class="submit" type="submit" name="blog_settings" value="<?php i18n(BLOGFILE.'/SAVE_SETTINGS'); ?>" /></span>
        &nbsp;&nbsp;<?php i18n(BLOGFILE.'/OR'); ?>&nbsp;&nbsp;
        <a href="load.php?id=<?php echo BLOGFILE; ?>&cancel" class="cancel"><?php i18n(BLOGFILE.'/CANCEL'); ?></a>
    </div>
</form>
