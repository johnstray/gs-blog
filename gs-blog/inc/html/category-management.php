  <h3 class="floated" style="float:left;"><?php i18n(BLOGFILE.'/MANAGE_CATEGORIES'); ?></h3>
  <div class="edit-nav">
    <p class="text 1">
      <a href="#" id="metadata_toggle"><?php i18n(BLOGFILE.'/ADD_CATEGORY'); ?></a>
      Sort Categories:
        <i class="fa fa-sort-alpha-asc sortbutton" title="Sort categories ascending by name"></i>
        <i class="fa fa-sort-numeric-asc sortbutton" title="Sort categories ascending by # of posts"></i>&nbsp;
    </p>
    <div class="clear"></div>
  </div>
  <!--<p class="text 2"><?php i18n(BLOGFILE.'/SETTINGS_CATEGORY_DESC'); ?></p>-->
  <div id="metadata_window" style="display:none;text-align:left;padding:10px 15px;">
        <form class="largeform" action="load.php?id=<?php echo BLOGFILE; ?>&categories&add_category" method="post">
      <label for="page-url" style="display:inline;margin-right:15px;"><?php i18n(BLOGFILE.'/ADD_CATEGORY'); ?>:</label>
      <input class="text" type="text" name="new_category" value="" style="height:16px;width:150px;margin-right:15px !important;" />
      <input class="submit" type="submit" name="category_edit" value="<?php i18n(BLOGFILE.'/ADD_CATEGORY'); ?>" style="width:auto;height:24px;padding:3px 10px;" />
        </form>
    <div class="clear"></div>
    </div>
    <table class="highlight" id="categoriesTable">
    <thead>
      <tr>
        <th data-sort="string-ins" id="cat_name"><?php i18n(BLOGFILE.'/CATEGORY_NAME'); ?></th>
        <th><i class="fa fa-pencil-square-o"></i></th>
        <th><i class="fa fa-rss"></i></th>
        <th><i class="fa fa-trash-o"></i></th>
        <th class="hidden" data-sort="int" id="cat_posts">Posts</th>
      </tr>
    </thead>
    <tbody>
      <?php
    foreach($category_file->category as $category)
    {
    $count = 0;
    $all_posts = $Blog->listPosts(true);
    if($all_posts !== false) {
      foreach($all_posts as $post) { // For each post in the list...
        $data = getXML($post['filename']); // Get the XML data of the post
        if((string) $data->category == $category) { // Is the post in the requested category?
          $count++; // Increase the counter.
        }
      }
    }
        ?>
        <tr>
            <td class="cat_name">
        <?php echo $category; ?> <span>[
        <a href="load.php?id=<?php echo BLOGFILE; ?>&manage&filter=category&filterval=<?php echo $category; ?>"><?php echo $count; ?> posts</a> ]</span>
      </td>
            <td class="edit"><a href="#" title="Edit Category: <?php echo $category; ?>" class="editButton" data-name="<?php echo $category; ?>"><i class="fa fa-pencil-square-o"></i></td>
      <td class="rss"><a href="<?php echo $Blog->get_blog_url('rss').'?filter=category&value='.$category; ?>" title="RSS Feed: <?php echo $category; ?>" target="_blank"><i class="fa fa-rss"></i></a></td>
            <td class="delete"><a href="load.php?id=<?php echo BLOGFILE; ?>&categories&delete_category=<?php echo $category; ?>" title="Delete Category: <?php echo $category; ?>" ><i class="fa fa-trash-o"></i></a></td>
      <td class="hidden"><?php echo $count; ?></td>
        </tr>
        <?php
    }
      ?>
    </tbody>
    </table>
  <script type="text/javascript">
    $(document).ready(function() {
      $("#categoriesTable").stupidtable();
    });
    $('.fa-sort-alpha-asc').click(function() {
      $('#cat_name').stupidsort();
      $(this).toggleClass('fa-sort-alpha-asc');
      $(this).toggleClass('fa-sort-alpha-desc');
    });
    $('.fa-sort-numeric-asc').click(function() {
      $('#cat_posts').stupidsort();
      $(this).toggleClass('fa-sort-numeric-asc');
      $(this).toggleClass('fa-sort-numeric-desc');
    });
    $('.editButton').on('click', function() {
      var data = $(this).data("name");
      var html =  '<form class="largeform" action="load.php?id=<?php echo BLOGFILE; ?>&categories&edit_category" method="post">' +
                  '<input type="hidden" name="previousName" value="' + data + '" />' +
                  '<input class="text" type="text" name="newName" value="' + data + '" />' +
                  '<button class="submit" type="submit"><i class="fa fa-floppy-o"></i></button</form>'
      $("td:contains('" + data + "')").html(html);
    });
  </script>

