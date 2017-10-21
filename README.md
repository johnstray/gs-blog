# GetSimple Blog
A simple an easy to use blog for GetSimple. With this plugin, you can create blog posts, sort posts by category, view posts in monthly archives, plus much more. This version is a fork of the original by @mikehenken and is now being actively maintained here.

## Features Include:
- GetSimple Blog Plugin
- Blog Categories
- Post Archives
- Recent Posts Lists
- Blog Post Tags
- Search Box
- RSS Auto-Importer
- Per-Category RSS Feeds
- External Comments Support
- Post Thumbnails
- Custom Fields

## Installation
You can install this plugin to your GetSimple installation by following these simple instructions.

- Download ZIP file with plugin from the GetSimple Extend Repository.
  http://get-simple.info/extend/plugin/getsimple-blog/810/
- Unzip it into the "plugins" folder of your GetSimple installation.
- Ensure your /Data and /Backups folder have write permissions.
- Log in to your GetSimple administration panel.
- Activate the plugin under the Plugins tab.

## Usage
Once installed, click on the new "Blog" tab in the Admin panel then click on "Help" in the side menu. Instructions for usage of this plugin can be found there.

## Contributions
Everyone is welcome to make suggestions on how this plugin can be improved by either submitting an issue or a pull-requests.

#### When making pull requests, there are a few simple rules that should be followed.
- Make sure that your fork is up to date with the master first. This helps to prevent conflicts from occuring. A pull request cannot be accepted if there is a conflict.
- All commits must reference a related issue in the comment. For example, "Part of #xx" or "Fixes #xx". If a related issue does not exist, please first create one.
- Code should follow common practises and the standards as set out in this [GetSimple Wiki] (http://get-simple.info/wiki/getsimple_coding) article. This also includes:
  - Indenting of code should consist of 4 spaces, never tabs.
  - Each file should contain an empty line at the end.
  - To make it easier for others to read and understand the code, it is recomended that you space out the code a bit more more. An example is provided below:
    ```php
    if ( exampleFunction( $variable ) ) {
      $exploded = explode( $variable );
    } else {
      $imploded = implode( $variable );
    }
    
    // Note the spacing around brackets.
    // Curly braces should remain on the same line as the 'if' or 'else' statements.
    ```

Acceptance of pull requests is at the descretion of the repository manager. All contributions to this repositiory will be appropriatly credited where possible.
