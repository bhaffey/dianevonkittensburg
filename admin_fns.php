<?php
// This file contains functions used by the admin interface
// for the item-O-Rama shopping cart.

function display_category_form($category = '') {
// This displays the category form.
// This form can be used for inserting or editing categories.
// To insert, don't pass any parameters.  This will set $edit
// to false, and the form will go to insert_category.php.
// To update, pass an array containing a category.  The
// form will contain the old data and point to update_category.php.
// It will also add a "Delete category" button.

  // if passed an existing category, proceed in "edit mode"
  $edit = is_array($category);

  // most of the form is in plain HTML with some
  // optional PHP bits throughout
?>
  <form method="post"
      action="<?php echo $edit ? 'edit_category.php' : 'insert_category.php'; ?>">
  <table border="0">
  <tr>
    <td>Category Name:</td>
    <td><input type="text" name="catname" size="40" maxlength="40"
          value="<?php echo $edit ? $category['catname'] : ''; ?>" /></td>
   </tr>
  <tr>
    <td <?php if (!$edit) { echo "colspan=2";} ?> align="center">
      <?php
         if ($edit) {
            echo "<input type=\"hidden\" name=\"catid\" value=\"".$category['catid']."\" />";
         }
      ?>
      <input type="submit"
       value="<?php echo $edit ? 'Update' : 'Add'; ?> Category" /></form>
     </td>
     <?php
        if ($edit) {
          //allow deletion of existing categories
          echo "<td>
                <form method=\"post\" action=\"delete_category.php\">
                <input type=\"hidden\" name=\"catid\" value=\"".$category['catid']."\" />
                <input type=\"submit\" value=\"Delete category\" />
                </form></td>";
       }
     ?>
  </tr>
  </table>
<?php
}

function display_item_form($item = '') {
// This displays the item form.
// It is very similar to the category form.
// This form can be used for inserting or editing items.
// To insert, don't pass any parameters.  This will set $edit
// to false, and the form will go to insert_item.php.
// To update, pass an array containing a item.  The
// form will be displayed with the old data and point to update_item.php.
// It will also add a "Delete item" button.


  // if passed an existing item, proceed in "edit mode"
  $edit = is_array($item);

  // most of the form is in plain HTML with some
  // optional PHP bits throughout
?>
  <form method="post"
        action="<?php echo $edit ? 'edit_item.php' : 'insert_item.php';?>">
  <table border="0">
  <tr>
    <td>ISBN:</td>
    <td><input type="text" name="isbn"
         value="<?php echo $edit ? $item['items_id'] : ''; ?>" /></td>
  </tr>
  <tr>
    <td>item Title:</td>
    <td><input type="text" name="title"
         value="<?php echo $edit ? $item['items_id'] : ''; ?>" /></td>
  </tr>
  <tr>
    <td>item Author:</td>
    <td><input type="text" name="author"
         value="<?php echo $edit ? $item['items_id'] : ''; ?>" /></td>
   </tr>
   <tr>
      <td>Category:</td>
      <td><select name="catid">
      <?php
          // list of possible categories comes from database
          $cat_array=get_categories();
          foreach ($cat_array as $thiscat) {
               echo "<option value=\"".$thiscat['catid']."\"";
               // if existing item, put in current catgory
               if (($edit) && ($thiscat['catid'] == $item['catid'])) {
                   echo " selected";
               }
               echo ">".$thiscat['catname']."</option>";
          }
          ?>
          </select>
        </td>
   </tr>
   <tr>
    <td>Price:</td>
    <td><input type="text" name="price"
               value="<?php echo $edit ? $item['price'] : ''; ?>" /></td>
   </tr>
   <tr>
     <td>Description:</td>
     <td><textarea rows="3" cols="50"
          name="description"><?php echo $edit ? $item['description'] : ''; ?></textarea></td>
    </tr>
    <tr>
      <td <?php if (!$edit) { echo "colspan=2"; }?> align="center">
         <?php
            if ($edit)
             // we need the old isbn to find item in database
             // if the isbn is being updated
             echo "<input type=\"hidden\" name=\"oldisbn\"
                    value=\"".$item['items_id']."\" />";
         ?>
        <input type="submit"
               value="<?php echo $edit ? 'Update' : 'Add'; ?> item" />
        </form></td>
        <?php
           if ($edit) {
             echo "<td>
                   <form method=\"post\" action=\"delete_item.php\">
                   <input type=\"hidden\" name=\"isbn\"
                    value=\"".$items['items_id']."\" />
                   <input type=\"submit\" value=\"Delete item\"/>
                   </form></td>";
            }
          ?>
         </td>
      </tr>
  </table>
  </form>
<?php
}

function display_password_form() {
// displays html change password form
?>
   <br />
   <form action="change_password.php" method="post">
   <table width="250" cellpadding="2" cellspacing="0" bgcolor="#cccccc">
   <tr><td>Old password:</td>
       <td><input type="password" name="old_passwd" size="16" maxlength="16" /></td>
   </tr>
   <tr><td>New password:</td>
       <td><input type="password" name="new_passwd" size="16" maxlength="16" /></td>
   </tr>
   <tr><td>Repeat new password:</td>
       <td><input type="password" name="new_passwd2" size="16" maxlength="16" /></td>
   </tr>
   <tr><td colspan=2 align="center"><input type="submit" value="Change password">
   </td></tr>
   </table>
   <br />
<?php
}

function insert_category($catname) {
// inserts a new category into the database

   $conn = db_connect();

   // check category does not already exist
   $query = "select category
             from items
             where category='".$catname."'";
   $result = $conn->query($query);
   if ((!$result) || ($result->num_rows!=0)) {
     return false;
   }

   // insert new category
   $query = "insert into items values
            ('', '".$catname."')";
   $result = $conn->query($query);
   if (!$result) {
     return false;
   } else {
     return true;
   }
}

function insert_item($items_id, $quantity, $category, $description, $size, $image_loc, $price, $name) {
// insert a new item into the database

   $conn = db_connect();

   // check item does not already exist
   $query = "select *
             from items
             where items_id='".$items_id."'";

   $result = $conn->query($query);
   if ((!$result) || ($result->num_rows!=0)) {
     return false;
   }

   // insert new item
   $query = "insert into items values
            ('".$items_id."', '".$quantity."', '".$category."',
             '".$description."', '".$size."', '".$image_loc."',
             '".$price."', '".$name."')";

   $result = $conn->query($query);
   if (!$result) {
     return false;
   } else {
     return true;
   }
}

//i dont think this function will work for us since we dont keep categories in a separate table
function update_category($catid, $catname) {
// change the name of category with catid in the database

   $conn = db_connect();

   $query = "update items
             set category='".$catname."'
             where catid='".$catid."'";
   $result = @$conn->query($query);
   if (!$result) {
     return false;
   } else {
     return true;
   }
}

function update_item($items_id, $quantity, $category, $description, $size, $image_loc, $price, $name) {
// change details of item stored under $oldisbn in
// the database to new details in arguments

   $conn = db_connect();

   $query = "update items
             set items_id= '".$items_id."',
             quantity = '".$quantity."',
             category = '".$category."',
             description = '".$description."'
             size = '".$size."',
             image_loc = '".$image_loc."',
             price = '".$price."',
             name = '".$name."'";

   $result = @$conn->query($query);
   if (!$result) {
     return false;
   } else {
     return true;
   }
}

//i dont think this function will work for us since we dont keep categories in a separate table
function delete_category($catid) {
// Remove the category identified by catid from the db
// If there are items in the category, it will not
// be removed and the function will return false.

   $conn = db_connect();

   // check if there are any items in category
   // to avoid deletion anomalies
   $query = "select category
             from items
             where catid='".$catid."'";

   $result = @$conn->query($query);
   if ((!$result) || (@$result->num_rows > 0)) {
     return false;
   }

   $query = "delete from categories
             where catid='".$catid."'";
   $result = @$conn->query($query);
   if (!$result) {
     return false;
   } else {
     return true;
   }
}


function delete_item($items_id) {
// Deletes the item identified by $isbn from the database.

   $conn = db_connect();

   $query = "delete from items
             where items_id='".$items."'";
   $result = @$conn->query($query);
   if (!$result) {
     return false;
   } else {
     return true;
   }
}

?>
