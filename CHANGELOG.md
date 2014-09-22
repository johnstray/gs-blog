### Version 3.3.1
**Bug Fixes:**
- [#039] Fixed runaway string on Help page
- [#038] Fixed hard coded English string in Custom Fields management.
- [#043] Fixed syntax error in rss.php
- [#044] Fixed only 1 blog post showing on main frontend page
- [#045] Fixed RSSBody generation method. Now uses create_excerpt function instead. HTML characters shouldn't show up in there now.

### Version 3.3.0
**Back End (Admin) UI Improvements:**
- [#003] Implemented selectable layout templates system
  - Re-wrote `show_settings_admin()` function from scratch.
  - Removed settings that are no longer needed. Most tasks can now be completed by creating/modifying template file.
  - Created function `blog_theme_layouts()` which gets the list of available theme files.
- [#022] Added `pluginManagementFA.js` script : Adds icon to plugin's listing on the Plugin Management admin tab.
- [#021] Updated FontAwesome version to 4.2.0 and changed `$media` from 'screen' to 'print'.
- [#023] Updated website address in plugin registration

**Font End UI Improvements:**
- [#003] Implemented selectable layout templates system
  - Re-wrote `show_blog_post()` function from scratch. Old: 131 lines, New: 31 lines.
  - Created 1 layout: Original. More will be available in future bugfix releases.
- [#003] Modified original layout to add scoped CSS functionality (New in HTML 5.1) : Backwards compatible with most current browsers.
- [#008] Fixed "Go Back" links in posts (sort of). The link is now in the template file. You can change it as needed, or remove it if you like.

**Bug Fixes:**
- [#014] Fixed undefined variable in `manage_custom_fields.php`

**Code Changes:**
- [#018] Removed social media functions. If you need this, add your own code to a template file.
- [#017] Cleaned up functions and comments in `adminFunctions.php`
- [#020] Cleaned up functions and comments in `frontEndFunctions.php`
- [#019] Moved some HTML sections of the code to separate files in 'html' folder to make for much easier reading and modification of code.
- [#028] Cleaned up functions and comments in common.php
- [#027] Removed redundant CodeMirror files.
- [#020] Moved RSS Auto-Importer functions into it's own file.

**Updated Languages:**
- [#016] Added new strings to English en_US language file.
- [#026] Added new strings to Russian ru_RU language file.
- [#025] Added new strings to Italian it_IT language file.
- [#024] Added new strings to Spanish es_ES language file.
- [#041] New Polish pl_PL language file added.

### Version 3.2.4
**Bug Fixes:**
- Removed blank line at start of English en_US language file that caused headers to be sent early.
- Fixed `undefined variable $data in frontEndFunctions.php on line 71`. Should have been $post.
- Fixed `undefined variable $data_edit in manage_custom_fields.php on line 28`3. The defining location has been moved outside of an 'if' condition and should be set all the time now. This should also fix Trying to get property of non-object.
- Fixed `undefined variable $blog_data in adminFunctions.php on line 1049`. This variable was not needed there and has been removed.
- Fixed link for "Display CSS: Click here to view available classes and ids". FancyBox JavaScript had not been made availble on that page when Settings was split into multiple pages.

**Front End UI Improvements:**
- Separator pipe ( | ) has been removed from the info line. If you really want it back, create a custom layout.

### Version 3.2.3
**Bug Fixes:**
- Posts with dates in the future are now hidden in all frontend areas (inc. Archives) but not in admin.
- Date format in RSS feeds should now be standards compliant.
- Language will now be selected based on GetSimple's language or will properly default to English (US) if the language is not available in GS Blog.

**Updated Languages:**
- Russian ru_RU language file, thanks to 'Oleg06'.
- Fixed missing strings in Italian it_IT language file.
- Fixed missing strings in Spanish es_ES language file.
- Fixed missing strings in English en_US language file.

### Version 3.2.2
**Bug Fixes:**
- Updated version number in `gs-blog.php`. Forgot to do this in the previous version. Updater will now show the correct results.
- Fix PrettyURL setting on Settings admin page. Converted to radio buttons to handle the page separation in settings. 
- Fixed link to show .htaccess example for PrettyURLs. FancyBox script was in the wrong place. It has been moved accordingly.
- Fixed thumbnail as link to post. Link A tag was missing but has now been added again.


### Version 3.2.1
**Security Fixes:**
- Added check if defined 'IN_GS' to all php files. This prevents files being loaded directly, rather they need to be called from within GS.
- Updated MagpieRSS from 0.7a to 0.72

**Code Changes:**
- MagpieRSS will now use the GS cache instead of trying to create its own. This would fail because of permissions in the plugin dir.
- Added Italian (it_IT) language file, thanks to Nicola Laviola (nikynik)
- Fixed typos and a missing string in `en_US` and `it_IT` language files.
- Fixed issue with RSS Auto-Importer creating blank posts when using content. 
- Fixed author not showing in post.
- Changed strings in en_US language file for descriptions on settings pages.
- CKeditor Bug? Mentioned in the forums, but I couldn't reproduce it. Used given fix anyway.
- Removed outdated language files. As translators come on board, languages will be added.

### Version 3.2.0
**Back End (Admin) UI Improvements:**
- Split 'Blog Settings' page into 4 sections : Main, Customisation, Advertisement, Social.
- Moved 'Create Post' link from side-bar to 'Manage Posts' page.
- Moved 'Custom Fields' link from side-bar to 'Manage Posts' page.
- Renamed 'RSS Feeds' to 'RSS Auto-Importer'.
- Added 'Settings' button to 'RSS Auto-Importer' page.
- 'RSS Auto-Importer' related settings are now on their own page.
- HTML Layouts changed to match the GS Admin default layout.
- Plugin is now compatible with the awesome 'Modern Admin' backend theme. Seriously, try it!
- Added setting to RSS Auto-Importer to choose between getting content if available or description with link back to original article.
- Added setting to Settings page to show/hide Post Author.
- Added setting to Settings page for Default Post Author if none is defined. Enter 'hidden' to hide if not defined.
- Added setting to Settings page to show/hide Category.

**Front End UI Improvements:**
- Added ability to show Author's name. A default will be shown / or hidden if author not defined. These can be setup in settings.
- Added ability to show the category the post has been saved in. Hidden if not defined.

**Code Changes:**
- VersionCheck now gets it's "What's New" messages encoded and will decode them for display.
- Download link updated in VersionCheck.
- `adminController.php` has been merged into `adminFunctions.php`
- `displayPosts.php` merged into `frontEndFunctions.php`
- `pageTitle.php` merged into `frontEndFunctions.php`
- Original Settings button idea ditched along with `settingsButton.php`
- Fixed yet another URL problem in `ckeditor.php`
- Added new strings for Settings and Sidebar buttons to en_US language file. Really need some translators here, please help?
- RSS Auto-Importer now has the ability to search for a `<content:encoded>` tag in rss files, and if it exists, will fill the post with that rather than the description.
- Fixed date localization on Windows based systems.

### Version 3.1.3
**Back End (Admin) UI Improvements:**
- VersionCheck updated to include the ability to show What's New information on update page.

**Bug Fixes:**
- Fixed constant `'BLOGFILE'` not defined when calling rss.php.
- Added better handling of having no posts on systems where `glob()` returns `'false'`.
- Fixed cache generation : Cache will not generate if there are no posts.
- Fixed 'Delete RSS Feed' link in admin panel.
- Update MagpieRSS to use `explode()` instead of the depreciated `split()`.
- Fixed stupid syntax mistakes. I really shouldn't code whilst tired...

### Version 3.1.2
**Bug Fixes:**
- Fixed a URL in `manage_custom_feilds.php`
- Fixed 2 URLs in the Primary Class File `blog.php`
- String `'Directory Successfully Created'` changed to `'File Successfully Created'` for categories XML file in EN_us language file.

### Version 3.1.1
**Back End (Admin) UI Improvements:**
- Updated VersionCheck system to use PHP's version_compare() function instead of a simple < or >.
- Added strings to EN_us language file for VersionCheck's admin page status table.

**Bug Fixes**
- Fixed missing semi-colon in `gs-blog.php` causing a syntax error.
- Fixed missing forward-slash for the variable `BLOGPLUGINFOLDER` in `common.php` that caused issues with missing include files and a file copy failure on initial setup.
- Fixed URLs for buttons and links across the admin area.

### Version 3.1
**Back End (Admin) UI Improvements:**
- Added Version Checking system
  - Created Function
  - Function moved to external include file.
  - Added strings to en_US language file. Further translations required.
  - Added link to version string on admin panel. Points to new Update page.
  - Added Update Check page to admin panel under settings.
- Added slug transliteration support - Thanks to Carlos for spotting that I'd missed that while scouring the forums for bugs.

**Bug Fixes / Code Cleanup:**
- Core plugin file now somewhat smaller and easier to read.
  - Moved Admin Controller function(s) to external include file. Code still needs cleaning.
  - Moved Front End Controller function(s) to external include file. Code still needs cleaning.
  - Page Title fix function moved to external include file.
- Require_once paths now reference `$thisfile` to make future updating easier.
- Fixed some spelling errors in en_US language file.
 
### Version 3.0 (Where I took over)
**Front End UI Improvements:**
- Page Title is now changed to either the Post's Title, Category Name, or Archive Date
- `$post->title` now only shows on the listing page, not on the individual posts page. This prevents double titles with the above UI fix.
- When `show_blog_categories()` is called and no categories exist, a message is displayed saying "No Categories Exist!"
- When `show_blog_archives()` is called and the archive contains no posts, a message is displayed saying "Nothing in the Archives!"

**Back End (Admin) UI Improvements:**
- The blog now has its own tab in the Admin area, which also includes its own sidebar links for each area of configuration.
- Each admin page now has it's own title and description at the top of the page and will also show the version of the plugin you are running.

**Bug Fixes (Front and Back End):**
- Clicking on Cancel in the post editor will now take you back to the Posts Management page. Previously it would take you to a different plugin, or throw an error if that plugin wasn't installed.
- Fixed Read More links on post excerpts. Previously the link was empty.
- Fixed default values not showing in custom fields when creating or editing a post.
- Removed `<ul>` tags from Archive functions to bring it in line with the rest of the plugin and the standard list format used in GetSimple. You now need to provide your own `<ul>` tags and apply attributes as needed. 
- Fixed improperly closed `<strong>` tags on Help admin page.
- Fixed error `Trying to get property of non-object in frontEndFunctions.php on line 671`.
