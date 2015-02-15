<?php if(!defined('IN_GS')){die('You cannot load this file directly!');} // Security Check
/**-------------------------------------------------------------------------------------------------
 * English (American) Language File for GetSimple Blog
 * 
 * Last Modified: 15 February 2015
 * Compiled By: johnstray (John Stray)
 */
 
$i18n = array(
  
  # Language Configuration
  'LANGUAGE_CODE'             =>  'en_US',
  'DATE_FORMAT'               =>  'm/d/Y h:i:s a',
  'DATE_DISPLAY'              =>  'F jS, Y',
  'DATE_ARCHIVE'              =>  'F Y',
  
  # Plugin Information
  'PLUGIN_TITLE'              =>  ($plugin = 'GetSimple Blog'),
  'PLUGIN_DESC'               =>  'A simple yet powerful Blog system for GetSimple CMS',
  
  # Tab/Sidebar Actions (Administration)
  'BLOG_TAB_BUTTON'           =>  'B<em>l</em>og',
  'MANAGE_POSTS_BUTTON'       =>  'Manage Posts',
  'CATEGORIES_BUTTON'         =>  'Categories',
  'AUTOIMPORTER_BUTTON'       =>  'RSS Auto-Importer',
  'SETTINGS_BUTTON'           =>  'Settings',
  'UPDATE_BUTTON'             =>  'Update Check',
  'HELP_BUTTON'               =>  'Help'
  
  # Generic Strings
  'WRITE_OK'                  =>  'File successfully written!',
  'EDIT_OK'                   =>  'File successfully modified!',
  'DATA_FILE_ERROR'           =>  'File could not be written!',
  'CANCEL'                    =>  'Cancel',
  'DELETE'                    =>  'Delete',
  'SAVE'                      =>  'Save',
  'OR'                        =>  'Or',
  'YES'                       =>  'Yes',
  'NO'                        =>  'No',
  'ADDED'                     =>  'Added',
  'MANAGE'                    =>  'Manage',
  'LANGUAGE'                  =>  'Language',
  'DATE'                      =>  'Date',
  
  # Class Constructor
  'DATA_BLOG_DIR'             =>  '<em>data/blog</em> directory successfully created.',
  'DATA_BLOG_DIR_ERR'         =>  'The <em>data/blog</em> folder could not be created!',
  'DATA_BLOG_DIR_ERR_HINT'    =>  'You are going to have to create this directory yourself for the plugin to work properly',
  'DATA_BLOG_CATEGORIES'      =>  '<em>blog_categories.xml</em> file successfully created!',
  'DATA_BLOG_CATEGORIES_ERR'  =>  '<em>blog_categories.xml</em> file could not be created!',
  'DATA_BLOG_RSS'             =>  '<em>blog_rss.xml</em> file successfully created!',
  'DATA_BLOG_RSS_ERR'         =>  '<em>blog_rss.xml</em> file could not be created!',
  'BLOG_SETTINGS'             =>  'Blog Settings',
  
  # 'Post Management' Strings
  'POST_ADDED'                =>  'Successfully saved post!',
  'POST_ERROR'                =>  'Post could not be saved!',
  'POST_DELETED'              =>  'Post successfully deleted!',
  'POST_DELETE_ERROR'         =>  'Post could not be deleted!',
  'BLOG_CREATE_EDIT_NO_TITLE' =>  'A title is required for the post before it can be saved!',
  'BLOG_RETURN_TO_PREV_PAGE'  =>  'Return to post',
  'ADD_NEW_POST'              =>  'Add New Post',
  'EDIT_EXISTING_POST'        =>  'Edit Post',
  'VIEW_POST'                 =>  'View Post',
  'POST_OPTIONS'              =>  'Post Options',
  'UPLOAD_THUMBNAIL'          =>  'Upload Thumbnail',
  'UPLOAD_ENABLE_JAVASCRIPT'  =>  'Please enable JavaScript to use the file uploader!',
  'SAVE_POST'                 =>  'Save Post',
  'MANAGE_POSTS'              =>  'Posts',
  'CUSTOM_FIELDS_BUTTON'      =>  'Custom Fields',
  'NEW_POST_BUTTON'           =>  'New Post',
  'MANAGE_POSTS_DESC'         =>  'Edit existing posts or create new posts. The table below shows posts that currently exist.',
  'NO_POSTS'                  =>  'There are no posts to show!',
  'CLICK_TO_CREATE'           =>  'Click here to create one',
  'PAGE_TITLE'                =>  'Page Title',
  
  # 'Category Management' Strings
  'CATEGORY_ADDED'            =>  'Successfully added Category!',
  'CATEGORY_ERROR'            =>  'Category could not be saved!',
  'MANAGE_CATEGORIES'         =>  'Manage Categories',
  'ADD_CATEGORY'              =>  'Add Category',
  'SETTINGS_CATEGORY_DESC'    =>  'Add or Edit categories to assign your posts to. This will enable you to sort your '.
                                  'posts by displaying only those in a given category.',
  'CATEGORY_NAME'             =>  'Category Name',
  'CATEGORY_RSS_FEED'         =>  'Category RSS Feed',
  
  # 'RSS Auto-Importer' Strings
  'FEED_ADDED'                =>  'Successfully added RSS Feed!',
  'FEED_ERROR'                =>  'RSS Feed could not be saved!',
  'FEED_DELETED'              =>  'Successfully deleted RSS Feed!',
  'FEED_DELETE_ERROR'         =>  'RSS Feed could not be deleted!',
  'READ_FULL_ARTICLE'         =>  'Read The Full Article',
  'RSS_FEED_NO_POSTS_DESC'    =>  'There are no posts available for this RSS feed. Please contact the website '.
                                  'administrator for more information.',
  'RSS_FILE_OPEN_FAIL'        =>  'Could not open the &apos;rss.rss&apos; file.',
  'RSS_FILE_WRITE_FAIL'       =>  'Could not write to the &apos;rss.rss&apos; file.',
  'RSS_HEADER'                =>  'RSS Auto-Importer',
  'ADD_FEED'                  =>  'Add RSS Feed',
  'SETTINGS_FEED_DESC'        =>  'The RSS Auto-Importer will import and create posts from RSS feeds on other websites. '.
                                  'This is useful if you want to manage this blog with content from another blog.',
  'ADD_NEW_FEED'              =>  'Add new RSS Feed',
  'BLOG_CATEGORY'             =>  'Blog Category',
  'RSS_FEED'                  =>  'RSS Feed',
  'FEED_CATEGORY'             =>  'RSS Feed Category',
  'DELETE_FEED'               =>  'Delete Feed',
  
  
  # 'Settings' Strings
  'SETTINGS_SAVE_OK'          =>  'Successfully saved settings!',
  'SETTINGS_SAVE_ERROR'       =>  'Could not save your settings!',
  'BLOG_SETTINGS'             =>  '',
  'SETTINGS_MAIN_DESC'        =>  '',
  'PAGE_URL'                  =>  '',
  'EXCERPT_OPTION'            =>  '',
  'FULL_TEXT'                 =>  '',
  'EXCERPT'                   =>  '',
  'EXCERPT_LENGTH'            =>  '',
  'POSTS_PER_PAGE'            =>  '',
  'RECENT_POSTS'              =>  '',
  'DISPLAY_POSTS_COUNT_ARCH'  =>  '',
  'HTACCESS_HEADLINE'         =>  '',
  'PRETTY_URLS'               =>  '',
  'VIEW_HTACCESS'             =>  '',
  'PRETTY_URLS_PARA'          =>  '',
  'HTACCESS_1'                =>  '',
  'HTACCESS_2'                =>  '',
  'HTACCESS_3'                =>  '',
  'BLOG_PRETTY_NOTICE'        =>  '',
  'SAVE_SETTINGS'             =>  '',
  
  # 'Help' Strings
  
  
  # 'Front-End' Strings
  'BY'                        =>  'By',
  'ON'                        =>  'On',
  'IN'                        =>  'In',
  'TAGS'                      =>  'Tags',
  'NO_CATEGORIES'             =>  'There are no categories to display!',
  'NO_POSTS'                  =>  'There are no posts to display!',
  'NO_ARCHIVES'               =>  'There are no Archives to display!',
  'SEARCH'                    =>  'Search',
  'FOUND'                     =>  'The following posts were found:',
  'NOT_FOUND'                 =>  'Sorry, No posts were found!',
  'NEXT_PAGE'                 =>  '&larr; Next Page',
  'PREV_PAGE'                 =>  'Previous Page &rarr;',
  'ARCHIVE_PRETITLE'          =>  'Blog Archive: ',
  'CATEGORY_PRETITLE'         =>  'Blog Category: ',
  
  # Custom Fields Manager
  'CUSTOM_FIELDS'             =>  'Custom Fields',
  'CUSOMFIELDS_DESCR'         =>  'This plugin allows you to specify custom fields which are displayed when you edit a page.',
  'CUSTOM_FIELDS_OPTIONS_AREA'=>  'Options Area',
  'OPTIONS_AREA_DESCRP'       =>  '(Options: Custom fields will be displayed in the "Post Options" section).',
  'NAME'                      =>  'Name',
  'LABEL'                     =>  'Label',
  'TYPE'                      =>  'Type',
  'DEFAULT_VALUE'             =>  'Default Value',
  'ADD'                       =>  'Add new field',
  'CUSTOM_FIELDS_MAIN_AREA'   =>  'Main Area',
  'MAIN_AREA_DESCRP'          =>  '(Main: Custom fields will be <em>under</em> the "Post Options" section).',
  'TEXT_FIELD'                =>  'Text Field',
  'LONG_TEXT_FIELD'           =>  'Long Text Field',
  'DROPDOWN_BOX'              =>  'Drop-down Box',
  'CHECKBOX'                  =>  'Check Box',
  'WYSIWYG_EDITOR'            =>  'WYSIWYG Editor',
  'TITLE'                     =>  'Title',
  'HIDDEN_FIELD'              =>  'Hidden Field',
  
  # 'VersionCheck' Strings
  'VERSION_NOMESSAGE'         =>  'No error message has been set! This is a problem.',
  'VERSION_NORESPONSE'        =>  'Could not get a response from the Extend API server.',
  'VERSION_NOFUNCTION'        =>  'Your PHP environment is not configured correctly.',
  'VERSION_UPDATEAVAILABLE'   =>  'An update is available!',
  'VERSION_UPTODATE'          =>  $plugin.' is up to date!',
  'VERSION_BETA'              =>  'You are currently using a Beta version of '.$plugin.'.',
  'VERSION_FAILEDCOMPARE'     =>  'Failed to compare versions during update check.',
  'VERSION_APIFAIL'           =>  'The check with the Extend API was not successful.',
  'VERSION_INTERNALERROR'     =>  'An internal error has occurred with VersionCheck.',
  
  'VERSION_STATUS'            =>  'Plugin Updates',
  'VERSION_STATUS_DESC'       =>  'Ensure you&apos;re running the latest version of the '.$plugin.' '.
                                  'plugin so that you can benefit from the latest fixes and features',
  'VERSION_UPDATESTATUS'      =>  'Update Status',
  'VERSION_CURRENTVER'        =>  'Current Version',
  'VERSION_LATESTVER'         =>  'Latest Version',
  
);