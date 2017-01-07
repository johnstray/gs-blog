<?php if (!defined('IN_GS')) {die('You cannot load this file directly!');}

/** blogSetup
 * Filesystem requirements management
 * - data/blog_posts directory if it is not already created.
 * - category file if it is not yet created
 * - settings file if it is not yet created
 * - rss feed auto importer file if it is not yet created
 * @param array $settingsData
 */

function blogSetup(array $settingsData = null)
{

    $default_settings = array(
        'blogurl'          => "index",
        'prettyurls'       => "N",
        'lang'             => "en_US",
        'rsstitle'         => "My RSS Feed",
        'rssdescription'   => "Welcome to my blog!!",
        'rssfeedposts'     => "10",
        'rssinclude'       => "N",
        'excerptlength'    => "350",
        'postformat'       => "N",
        'postperpage'      => "10",
        'recentposts'      => "4",
        'achivespostcount' => "Y",
        'autoimporter'     => "N",
        'autoimporterpass' => "passphrase",
    );

    # Blog posts folder
    if (!file_exists(BLOGPOSTSFOLDER)) {
        if (mkdir(BLOGPOSTSFOLDER)) {
            getSuccessHtml(i18n_r(BLOGFILE . '/DATA_BLOG_DIR'));
        } else {
            getErrorHtml(i18n_r(BLOGFILE . '/DATA_BLOG_DIR_ERR') . '</strong><br/>' . i18n_r(BLOGFILE . '/DATA_BLOG_DIR_ERR_HINT'));
        }
    }

    # Categories File
    if (!file_exists(BLOGCATEGORYFILE)) {
        $xml = new SimpleXMLExtended('<?xml version="1.0"?><item></item>');
        if (XMLsave($xml, BLOGCATEGORYFILE)) {
            getSuccessHtml(i18n_r(BLOGFILE . '/DATA_BLOG_CATEGORIES'));
        } else {
            getErrorHtml(i18n_r(BLOGFILE . '/DATA_BLOG_CATEGORIES_ERR'));
        }
    }

    # RSS Feed File
    if (!file_exists(BLOGRSSFILE)) {
        $xml = new SimpleXMLExtended('<?xml version="1.0"?><item></item>');
        if (XMLsave($xml, BLOGRSSFILE)) {
            getSuccessHtml(i18n_r(BLOGFILE . '/DATA_BLOG_RSS'));
        } else {
            getErrorHtml(i18n_r(BLOGFILE . '/DATA_BLOG_RSS_ERR'));
        }
    }

    if (!file_exists(BLOGSETTINGS)) {
        if ($this->saveSettings($default_settings))
        {
            getSuccessHtml(i18n_r(BLOGFILE . '/BLOG_SETTINGS') . '&nbsp' . i18n_r(BLOGFILE . '/WRITE_OK'));
        } else {
            getErrorHtml(i18n_r(BLOGFILE . '/BLOG_SETTINGS') . '&nbsp' . i18n_r(BLOGFILE . '/DATA_FILE_ERROR'));

        }
    } else {
        $saved_settings  = $settingsData;
        $update_settings = false;

        # Check for missing settings
        $missing_settings = array_diff_key($default_settings, $saved_settings);
        if (count($missing_settings) > 0) {
            foreach ($missing_settings as $key => $value) {
                $saved_settings[$key] = $value;
            }
            $update_settings = true;
        }

        # Check for redundant settings
        foreach ($saved_settings as $key => $value) {
            if (!array_key_exists($key, $default_settings)) {
                unset($saved_settings[$key]);
                $update_settings = true;
            }
        }

        # Write the settings to file after update
        if ($update_settings === true) {
            if ($this->saveSettings($default_settings))
            {
                getSuccessHtml(i18n_r(BLOGFILE . '/BLOG_SETTINGS') . '&nbsp' . i18n_r(BLOGFILE . '/WRITE_OK'));
            } else {
                getErrorHtml(i18n_r(BLOGFILE . '/BLOG_SETTINGS') . '&nbsp' . i18n_r(BLOGFILE . '/DATA_FILE_ERROR'));
            }
        }
    }

    # Reserved Custom Fields
    if (!file_exists(BLOGCUSTOMFIELDS)) {
        $custom_fields_file = BLOGPLUGINFOLDER . 'inc/reserved_blog_custom_fields.xml';
        if (!copy($custom_fields_file, BLOGCUSTOMFIELDS)) {
            getErrorHtml('Aie caramba error ! </strong> - To resolve it try to copy the contents of the below file, save it as a new document named "blog_custom_fields.xml" and then move it to the "' . GSDATAOTHERPATH . '" folder.<br/><strong> XML File To Copy : ' . BLOGCUSTOMFIELDS );
        }
    }
}

function getSuccessHtml($body)
{
    echo '<div class="updated">'.$body.'</div>';
}

function getErrorHtml($body)
{
    echo '<div class="error"><strong>'.$body.'</strong></div>';
}
