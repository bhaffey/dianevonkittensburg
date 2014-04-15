<?php
  include ('item_sc_fns.php');
  // The shopping cart needs sessions, so start one
  session_start();
  do_html_header("Diane Von Kittensburg");

  echo "<p style=\"clear:both\">&nbsp;";
  echo "<img src=\"images/stockphoto.jpg\" height=\"600px\"></img>";
  echo "</p>";

  //echo "<p>Please choose a category:</p>";

  // get categories out of database
  $category_array = get_categories();

  // display as links to cat pages
  //display_categories($category_array);

  // if logged in as admin, show add, delete, edit cat links
  if(isset($_SESSION['admin_user'])) {
    display_button("admin.php", "admin-menu", "Admin Menu");
  }
  do_html_footer();
?>
