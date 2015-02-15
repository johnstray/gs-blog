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
  #'CANCEL'                    =>  'Cancel',
  #'DELETE'                    =>  'Delete',
  #'OR'                        =>  'Or',
  #'LANGUAGE'                  =>  'Language',
  
  # 'Post Management' Strings
  'POST_ADDED'                =>  '',
  'POST_ERROR'                =>  '',
  'POST_DELETED'              =>  'Post successfully deleted!',
  'POST_DELETE_ERROR'         =>  'Post could not be deleted!',
  'BLOG_CREATE_EDIT_NO_TITLE' =>  '',
  'BLOG_RETURN_TO_PREV_PAGE'  =>  '',
  
  # 'Category Management' Strings
  'CATEGORY_ADDED'            =>  'Successfully added Category!',
  'CATEGORY_ERROR'            =>  'Category could not be saved!',
  
  
  # 'RSS Auto-Importer' Strings
  'FEED_ADDED'                =>  '',
  'FEED_ERROR'                =>  '',
  'FEED_DELETED'              =>  '',
  'FEED_DELETE_ERROR'         =>  '',
  
  
  # 'Settings' Strings
  'SETTINGS_SAVE_OK'          =>  '',
  'SETTINGS_SAVE_ERROR'       =>  '',
  
  
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