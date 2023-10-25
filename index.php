<?php
$host = 'db';
$user = 'flavors';
$pass = 'vanilla';
$database = 'flavors';
$conn = new mysqli($host, $user, $pass, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
}
$flavor_query="SELECT * FROM available_flavors";
$flavors_results = mysqli_query($conn,$flavor_query);

$flavor_diff_query="SELECT * FROM available_flavors";
$flavors_diff_results = mysqli_query($conn,$flavor_diff_query);
//phpinfo();
?>


<!DOCTYPE html>
<html lang="en">
<noscript>This form requires that you have javascript enabled to work properly please enable javascript in your browser.</noscript>
<head>
<style>
.parentDiv {
  display: inline-block;
  verticle-align: top;
  border: 1px solid black;
  margin: 1rem;
  padding: 2rem 2rem;
}
.varsDiv {
  verticle-align: top;
  border: 5px outset red;
  background-color: white;
  text-align: left;
  padding: 1rem 1rem;
  width: 400px;
}
</style>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport"
    content="width=device-width, initial-scale=1.0">    
</head>
<body bgcolor=777776>
  <?php
    if(empty($_POST["compare"])) {
  ?>
  <form name="flavor_version_form" id="flavor_version_form" action="http://localhost:8000/index.php" method="POST">

  <label>Select the first Flavor</label>
  <select id="flavor1"  name="flavor1">
     <option value=''> Choose One </option>
     <?php 
       while ($flavor = mysqli_fetch_array(
         $flavors_results,MYSQLI_ASSOC)):; 
     ?>
     <option value="<?php echo $flavor["id"]; ?>">
       <?php echo $flavor["flavor"];
       ?>
     </option>
       <?php 
         endwhile; 
       ?>
     </select>
     <br><br>
     <?php
       if(!empty($_POST["flavor1"])) {
     ?>
       <script type="text/javascript">
         document.getElementById('flavor1').value = "<?php echo $_POST['flavor1'];?>";
       </script>
     <?php
         $flavor1 = $_POST["flavor1"];
         $chosen_flavor_query ="SELECT flavor FROM available_flavors WHERE id= " . $_POST["flavor1"];
         $chosen_flavor_result = mysqli_query($conn,$chosen_flavor_query);
         $chosen_flavor = mysqli_fetch_row($chosen_flavor_result);
         $versions_query ="SELECT * FROM available_versions_flavors WHERE flavor_id = '" . $_POST["flavor1"] . "'";
         $versions1_results = mysqli_query($conn,$versions_query);
         echo "Choose a version of " . $chosen_flavor[0];
         echo "<select id='version1' name='version1' >";
         echo "<option value=''> Choose One </option>";
         while ($version1 = mysqli_fetch_array( 
           $versions1_results,MYSQLI_ASSOC)):;
           ?>
           <option value="<?php echo $version1["id"]; ?>">
             <?php echo $version1["version"]; ?> 
           </option> 
         <?php    
         endwhile;
       }
     ?>
     </select><br><br>
     <?php
       if(!empty($_POST["version1"])) {
     ?>
         <script type="text/javascript">
           document.getElementById('version1').value = "<?php echo $_POST['version1'];?>";
         </script>
     <?php
       }
     ?>
     <label>Select the Flavor to diff against</label>
     <select id="flavor2" name="flavor2">
     <option value=''> Choose One </option>
         <?php 
             while ($flavor2 = mysqli_fetch_array(
                     $flavors_diff_results,MYSQLI_ASSOC)):; 
         ?>
         <option value="<?php echo $flavor2["id"]; ?>">
                 <?php echo $flavor2["flavor"];
                 ?>
             </option>
         <?php 
             endwhile; 
         ?>
     </select>
     <br><br>
     <?php
       if(!empty($_POST["flavor2"])) {
     ?>
       <script type="text/javascript">
         document.getElementById('flavor2').value = "<?php echo $_POST['flavor2'];?>";
       </script>
     <?php
       $flavor2 = $_POST["flavor2"];
       $chosen_flavor_query ="SELECT flavor FROM available_flavors WHERE id= " . $_POST["flavor2"];
       $chosen_flavor_result = mysqli_query($conn,$chosen_flavor_query);
       $chosen_flavor = mysqli_fetch_row($chosen_flavor_result);
       $versions_query ="SELECT * FROM available_versions_flavors WHERE flavor_id = '" . $_POST["flavor2"] . "'";
       $versions2_results = mysqli_query($conn,$versions_query);
       echo "Choose a version of " . $chosen_flavor[0];
       echo "<select id='version2' name='version2' >";
       echo "<option value=''> Choose One </option>";
       while ($version2 = mysqli_fetch_array( 
         $versions2_results,MYSQLI_ASSOC)):;
         ?>
         <option value="<?php echo $version2["id"]; ?>">
           <?php echo $version2["version"]; ?> 
         </option> 
       <?php    
       endwhile;
     }
   ?>
   </select><br><br>
     <?php
       if(!empty($_POST["version2"])) {
     ?>
         <script type="text/javascript">
           document.getElementById('version2').value = "<?php echo $_POST['version2'];?>";
         </script>
     <?php
       }
     ?>
   <br>
     <?php
       if(!empty($_POST["flavor1"]) && !empty($_POST["flavor2"]) && !empty($_POST["version1"]) && !empty($_POST["version2"]) ) {
     ?>
       <input type="hidden" value="compare" name="compare">
       <input type="submit" value="Compare" name="btnsubmit">
     <?php
       }
     ?>
 </form>
 <script>
    const flavor1 = document.getElementById("flavor1");
    const flavor2 = document.getElementById("flavor2");
  
    flavor1.addEventListener("change", function() {
      document.getElementById('flavor_version_form').submit();
    });

    flavor2.addEventListener("change", function() {
      document.getElementById("flavor_version_form").submit();
    });
  </script>
  <?php
    if(!empty($_POST["flavor1"])) {
  ?>
     <script>
       const version1 = document.getElementById("version1");
       version1.addEventListener("change", function() {
         document.getElementById("flavor_version_form").submit();
        });
     </script>
  <?php
    }
    if(!empty($_POST["flavor2"])) {
  ?>
     <script>   
       const version2 = document.getElementById("version2");
       version2.addEventListener("change", function() {
         document.getElementById("flavor_version_form").submit();
       });
     </script>
  <?php
    }
  ?>
  </script>
 <br>
  <?php
    }
    else
    {
         $get_name_query = "SELECT f.flavor as flavor, v.version as version FROM available_flavors f, available_versions_flavors v" .
                            " WHERE f.id = v.flavor_id" .
                            " AND v.id = " . $_POST["version1"];

         $get_name2_query = "SELECT f.flavor as flavor, v.version as version FROM available_flavors f, available_versions_flavors v" .
                            " WHERE f.id = v.flavor_id" .
                            " AND v.id = " . $_POST["version2"];

         $name_result = mysqli_query($conn,$get_name_query);
         $name2_result = mysqli_query($conn,$get_name2_query);
         while ($names = mysqli_fetch_array(
           $name_result,MYSQLI_ASSOC)):;
           $flavor_name = $names["flavor"];
           $version_name = $names["version"];
         endwhile;
         while ($names2 = mysqli_fetch_array(
           $name2_result,MYSQLI_ASSOC)):;
           $flavor_name2 = $names2["flavor"];
           $version_name2 = $names2["version"];
         endwhile;
         $chosen_table_query ="SELECT var_table_name FROM available_versions_flavors WHERE id= " . $_POST["version1"];
         $chosen_table_result = mysqli_query($conn,$chosen_table_query);
         while ($tables = mysqli_fetch_array(
           $chosen_table_result,MYSQLI_ASSOC)):; 
           $chosen_table1 =$tables["var_table_name"];
         endwhile; 

        $get_table2_qry = "SELECT var_table_name FROM available_versions_flavors where id =" . $_POST["version2"] ;
        $chosen_table2_result = mysqli_query($conn,$get_table2_qry);
          while ($tables2 = mysqli_fetch_array(
            $chosen_table2_result,MYSQLI_ASSOC)):; 
            $chosen_table2=$tables2["var_table_name"];
          endwhile; 
        echo "<h2> <center> Variables In: " . $flavor_name . ":" . $version_name . "<br>Compared Against Variables In: " . $flavor_name2 . ":" . $version_name2 . "</center></h2>";
        echo "<div class='parentDiv'>";

        $get_new_variables = "SELECT " . $chosen_table2 . ".*" .
         " FROM " . $chosen_table2 .
         " LEFT JOIN " . $chosen_table1 . 
         " ON " . $chosen_table1 . ".variable_name = " . $chosen_table2 . ".variable_name WHERE " .
         $chosen_table1 . ".variable_name IS NULL";
         $new_variables_result = mysqli_query($conn,$get_new_variables);
         echo "<div class='varsDiv'>";
         echo "<b>New Variables in " . $flavor_name2 . " Version " . $version_name2 . "</b><br>";
         while($new_vars = mysqli_fetch_array(
           $new_variables_result,MYSQLI_ASSOC)):;
           $new_var=$new_vars["variable_name"];
           echo $new_var . "<br>";
         endwhile;
         echo "</div>";

       $get_removed_variables = "SELECT " . $chosen_table1 . ".*" .
         " FROM " . $chosen_table1 .
         " LEFT JOIN " . $chosen_table2 . 
         " ON " . $chosen_table1 . ".variable_name = " . $chosen_table2 . ".variable_name WHERE " .
         $chosen_table2 . ".variable_name IS NULL";
        $removed_variables_result = mysqli_query($conn,$get_removed_variables);
        echo "<div class='varsDiv'>";
        echo "<b>Variables Removed from " . $flavor_name . " Version " . $version_name . "</b><br>";
        while($removed_vars = mysqli_fetch_array(
          $removed_variables_result,MYSQLI_ASSOC)):;
          $removed_var=$removed_vars["variable_name"];
          echo $removed_var . "<br>";
        endwhile;
        echo "</div>";

       $get_diff_defaults = "SELECT t2.variable_name as name, t2.variable_value as new_val, t1.variable_value as old_val" .
         " FROM " . $chosen_table1 . " t1," . $chosen_table2 . " t2" .
         " WHERE t1.variable_name = t2.variable_name" .
         " AND t1.variable_value != t2.variable_value";
        $diff_defaults_result = mysqli_query($conn,$get_diff_defaults);
        echo "<div class='varsDiv'>";
        echo "<b>Default Values changed in " . $flavor_name2 . " Version " . $version_name2 . "</b><br>";
        echo "<b> Variable<br>Old Default | New Default</b><br>";
        while($diff_defaults = mysqli_fetch_array(
          $diff_defaults_result,MYSQLI_ASSOC)):;
          $var_name = $diff_defaults["name"];
          $old_val = $diff_defaults["old_val"];
          $new_val = $diff_defaults["new_val"];
          echo "<b>" . $var_name . "</b><br>" . $old_val . "<b> | </b>" . $new_val . "<br>";
        endwhile;
        echo "</div>";

      echo "</div>";
       
    ?>
   <?php
     }
   ?>
</body>
</html>
