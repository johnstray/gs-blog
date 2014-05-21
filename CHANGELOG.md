## Changelog

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
- adminController.php has been merged into adminFunctions.php
- displayPosts.php merged into frontEndFunctions.php
- pageTitle.php merged into frontEndFunctions.php
- Original 'Settings' button idea ditched along with settingsButton.php
- Fixed yet another URL problem in ckeditor.php
- Added new strings for 'Settings' and Sidebar buttons to en_US language file. Really need some translators here, please help?
- RSS Auto-Importer now has the ability to search for a <content:encoded> tag in rss files, and if it exists, will fill the post with that rather than the description.
- Fixed date localization on Windows based systems.

### Version 3.1.3
**Back End (Admin) UI Improvements:**
- VersionCheck updated to include the ability to show What's New information on update page.

**Bug Fixes:**
- Fixed constant 'BLOGFILE' not defined when calling rss.php.
- Added better handling of having no posts on systems where glob() returns 'false'.
- Fixed cache generation : Cache will not generate if there are no posts.
- Fixed 'Delete RSS Feed' link in admin panel.
- Update MagpieRSS to use explode() instead of the depreciated split().
- Fixed stupid syntax mistakes. I really shouldn't code whilst tired...

### Version 3.1.2
**Bug Fixes:**
- Fixed a URL in manage_custom_feilds.php
- Fixed 2 URLs in the Primary Class File blog.php
- String 'Directory Successfully Created' changed to 'File Successfully Created' for categories XML file in EN_us language file.

### Version 3.1.1
**Back End (Admin) UI Improvements:**
- Updated VersionCheck system to use PHP's version_compare() function instead of a simple < or >.
- Added strings to EN_us language file for VersionCheck's admin page status table.

**Bug Fixes**
- Fixed missing semi-colon in gs-blog.php causing a syntax error.
- Fixed missing forward-slash for the variable BLOGPLUGINFOLDER in common.php that caused issues with missing include files and a file copy failure on initial setup.
- Fixed URLs for buttons and links across the admin area.

### Version 3.1
**Back End (Admin) UI Improvements:**
- Added Version Checking system
  * Created Function
  * Function moved to external include file.
  * Added strings to en_US language file. Further translations required.
  * Added link to version string on admin panel. Points to new Update page.
  * Added Update Check page to admin panel under settings.
- Added slug transliteration support - Thanks to Carlos for spotting that I'd missed that while scouring the forums for bugs.

**Bug Fixes / Code Cleanup:**
- Core plugin file now somewhat smaller and easier to read.
  * Moved Admin Controller function(s) to external include file. Code still needs cleaning.
  * Moved Front End Controller function(s) to external include file. Code still needs cleaning.
  * Page Title fix function moved to external include file.
- Require_once paths now reference '$thisfile' to make future updating easier.
- Fixed some spelling errors in en_US language file.
 
### Version 3.0 (Where I took over)
**Front End UI Improvements:**
- Page Title is now changed to either the Post's Title, Category Name, or Archive Date
- $post->title now only shows on the listing page, not on the individual posts page. This prevents double titles with the above UI fix.
- When show_blog_categories() is called and no categories exist, a message is displayed saying "No Categories Exist!"
- When show_blog_archives() is called and the archive contains no posts, a message is displayed saying "Nothing in the Archives!"

**Back End (Admin) UI Improvements:**
- The blog now has its on tab in the Admin area, which also includes its own sidebar links for each area of configuration.
- Each admin page now has it's own title and description at the top of the page and will also show the version of the plugin you are running.

**Bug Fixes (Front and Back End):**
- Clicking on Cancel in the post editor will now take you back to the Posts Management page. Previously it would take you to a different plugin, or throw an error if that plugin wasn''t installed.
- Fixed Read More links on post excerpts. Previously the link was empty.
- Fixed default values not showing in custom fields when creating or editing a post.
- Removed <ul> tags from Archive functions to bring it in line with the rest of the plugin and the standard list format used in GetSimple. You now need to provide your own <ul> tags and apply attributes as needed. 
- Fixed improperly closed <strong> tags on Help admin page.
- Fixed error "Trying to get property of non-object in frontEndFunctions.php on line 671".
