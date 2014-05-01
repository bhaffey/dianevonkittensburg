<?php
  include ('item_sc_fns.php');
  // The shopping cart needs sessions, so start one
  session_start();
  do_html_header("");

    // if logged in as admin, show add, delete, edit cat links
  if(isset($_SESSION['admin_user'])) {
    echo "<div class='floatright'>";
    display_button("admin.php", "admin-menu", "Admin Menu");
    echo "</div>";
  }

  echo "<p style=\"clear:both\">&nbsp;";
  echo "<img src=\"images/stockphoto.jpg\" height=\"400px\"></img>";
  echo "</p>";

  //echo '<p>10% of the proceeds from all sales go to <a href="http://www.ny-petrescue.org/">New York Pet Rescue</a></p>';

  do_html_footer();
?>
