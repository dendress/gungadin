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
  verticle-align: top;
  font-family: Arial, sans-serif;
  border: 1px solid black;
  margin: 1rem;
  padding: 2rem 2rem;
}
.componentDiv {
  verticle-align: top;
  font-family: Arial, sans-serif;
  display: inline-block;
  border: 5px outset red;
  background-color: white;
  text-align: left;
  padding: 7rem 5rem;
  width: 700px;
}
</style>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport"
    content="width=device-width, initial-scale=1.0">    
</head>
<body style="font-family: Arial, sans-serif" bgcolor=777776>
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
         $versions_query ="SELECT * FROM available_versions_flavors WHERE flavor_id = '" . $_POST["flavor1"] . "' ORDER BY version";
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
       if(!empty($_POST["component"])) {
     ?>
         <script type="text/javascript">
           document.getElementById('component').value = "<?php echo $_POST['component'];?>";
         </script>
     <?php
     }
     ?>
     <?php
       if(!empty($_POST["flavor1"]) && !empty($_POST["flavor2"]) && !empty($_POST["version1"]) && !empty($_POST["version2"]) ) {
     ?>
       <label>Select the Component to Compare</label>
       <select id="component" name="component">
       <option value="var"> Variables</option>
       <option value="privileges"> Privileges</option>
       <option value="info_schema"> Information Schema Tables</option>
       <option value="perf_schema"> Performance Schema Tables</option>
       <option value="mysql"> MySQL Tables</option>
       <option value="plugin"> Plugins</option>
       <option value="collate"> Collations</option>
       <option value="charset"> Character Sets</option>
       </select>
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
        $component = $_POST["component"];
        $component_string = array(
          'var'=>'Variables',
          'privileges'=>'Privileges',
          'info_schema'=>'Information Schema Tables',
          'perf_schema'=>'Performance Schema Tables',
          'mysql'=>'MySQL Tables',
          'plugin'=>'Plugins',
          'collate'=>'Collations',
          'charset'=>'Character Sets'
        );

        $table_name = $component . "_table_name";
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
         $chosen_table_query ="SELECT " . $table_name . " FROM available_versions_flavors WHERE id= " . $_POST["version1"];
         $chosen_table_result = mysqli_query($conn,$chosen_table_query);
         while ($tables = mysqli_fetch_array(
           $chosen_table_result,MYSQLI_ASSOC)):; 
           $chosen_table1 =$tables["$table_name"];
         endwhile; 

        $get_table2_qry = "SELECT " . $table_name . " FROM available_versions_flavors where id =" . $_POST["version2"] ;
        $chosen_table2_result = mysqli_query($conn,$get_table2_qry);
          while ($tables2 = mysqli_fetch_array(
            $chosen_table2_result,MYSQLI_ASSOC)):; 
            $chosen_table2=$tables2["$table_name"];
          endwhile; 
        echo "<h2> <center>" . $component_string[$component] . "  In: " . $flavor_name . ":" . $version_name . "<br>Compared Against " . $component_string[$component] . "  In: " . $flavor_name2 . ":" . $version_name2 . "</center></h2>";

        //echo "<div class='parentDiv'>";
        if($component=='var') {
          $get_new_variables = "SELECT " . $chosen_table2 . ".*" .
           " FROM " . $chosen_table2 .
           " LEFT JOIN " . $chosen_table1 . 
           " ON " . $chosen_table1 . ".variable_name = " . $chosen_table2 . ".variable_name WHERE " .
           $chosen_table1 . ".variable_name IS NULL";
           $new_variables_result = mysqli_query($conn,$get_new_variables);
           echo "<div class='componentDiv'>";
           echo "<b>New " . $component_string[$component] . " in " . $flavor_name2 . " Version " . $version_name2 . "</b><br>";
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
          echo "<div class='componentDiv'>";
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
          echo "<div class='componentDiv'>";
          echo "<b>Default Values changed in " . $flavor_name2 . " Version " . $version_name2 . "</b><br>";
          echo "<b> Variable<br>" . $flavor_name2 . " " . $version_name2 . " Default | " . $flavor_name . " " . $version_name . " Default</b><br><br>";
          while($diff_defaults = mysqli_fetch_array(
            $diff_defaults_result,MYSQLI_ASSOC)):;
            $var_name = $diff_defaults["name"];
            $old_val = $diff_defaults["old_val"];
            $new_val = $diff_defaults["new_val"];
            if ($var_name == "optimizer_switch" || $var_name == "sql_mode") {
              echo "<b>" . $var_name . "</b><br>";
              $old_vals = explode(",", $old_val);
              $o = 0;
              foreach( $old_vals as $o_val) {
                ++$o;
                echo $o_val;
                if ($o/3 == 1) {
                  $o = 0;
                  echo "<br>";
                } else {
                  echo ", ";
                }
              }
              echo "<br><b><center>!=</center></b><br>";
              $new_vals = explode(",",$new_val);
              $n = 0;
              foreach( $new_vals as $n_val) {
                ++$n;
                echo $n_val;
                if ($n/3 == 1) {
                  $n = 0;
                  echo "<br>";
                } else {
                  echo ", ";
                }
              }
              echo "<br>";
            } else {
            echo "<b>" . $var_name . "</b><br>" . $old_val . "<b> != </b>" . $new_val . "<br>";
            }
          endwhile;
          echo "</div>";

       } elseif ($component == 'privileges') {
          $get_all = "SELECT " . $chosen_table2 . ".*" .
           " FROM " . $chosen_table2 ;

           $all_result = mysqli_query($conn,$get_all);
           echo "<div class='componentDiv'>";
           echo "<b>All " . $component_string[$component] . "s in " . $flavor_name2 . " Version " . $version_name2 . "</b><br>";
           while($all_privileges = mysqli_fetch_array(
             $all_result,MYSQLI_ASSOC)):;
             $privilege=$all_privileges["privilege_name"];
             echo $privilege . "<br>";
           endwhile;
           echo "</div>";

          $get_new = "SELECT " . $chosen_table2 . ".*" .
           " FROM " . $chosen_table2 .
           " LEFT JOIN " . $chosen_table1 . 
           " ON " . $chosen_table1 . ".privilege_name = " . $chosen_table2 . ".privilege_name WHERE " .
           $chosen_table1 . ".privilege_name IS NULL";
           $new_result = mysqli_query($conn,$get_new);
           echo "<div class='componentDiv'>";
           echo "<b>New " . $component_string[$component] . " in " . $flavor_name2 . " Version " . $version_name2 . "</b><br>";
           while($new_privileges = mysqli_fetch_array(
             $new_result,MYSQLI_ASSOC)):;
             $new_privilege=$new_privileges["privilege_name"];
             echo $new_privilege . "<br>";
           endwhile;
           echo "</div>";
  
         $get_removed = "SELECT " . $chosen_table1 . ".*" .
           " FROM " . $chosen_table1 .
           " LEFT JOIN " . $chosen_table2 . 
           " ON " . $chosen_table1 . ".privilege_name = " . $chosen_table2 . ".privilege_name WHERE " .
           $chosen_table2 . ".privilege_name IS NULL";
         $removed_result = mysqli_query($conn,$get_removed);
         echo "<div class='componentDiv'>";
          echo "<b>" . $component_string[$component] . " in " . $flavor_name . " Version " . $version_name . " not found in " . $flavor_name2 . " Version " . $version_name2 . "</b><br>";
          while($removed_privileges = mysqli_fetch_array(
            $removed_result,MYSQLI_ASSOC)):;
            $removed_privilege=$removed_privileges["privilege_name"];
            echo $removed_privilege . "<br>";
          endwhile;
       } elseif ($component == 'info_schema') {
          $get_all = "SELECT " . $chosen_table2 . ".*" .
           " FROM " . $chosen_table2 ;

           $all_result = mysqli_query($conn,$get_all);
           echo "<div class='componentDiv'>";
           echo "<b>All " . $component_string[$component] . "s in " . $flavor_name2 . " Version " . $version_name2 . "</b><br>";
           while($all_tables = mysqli_fetch_array(
             $all_result,MYSQLI_ASSOC)):;
             $table=$all_tables["table_name"];
             echo $table . "<br>";
           endwhile;
           echo "</div>";

          $get_new = "SELECT " . $chosen_table2 . ".*" .
           " FROM " . $chosen_table2 .
           " LEFT JOIN " . $chosen_table1 . 
           " ON " . $chosen_table1 . ".table_name = " . $chosen_table2 . ".table_name WHERE " .
           $chosen_table1 . ".table_name IS NULL";
           $new_result = mysqli_query($conn,$get_new);
           echo "<div class='componentDiv'>";
           echo "<b>New " . $component_string[$component] . " in " . $flavor_name2 . " Version " . $version_name2 . "</b><br>";
           while($new_info_schemas = mysqli_fetch_array(
             $new_result,MYSQLI_ASSOC)):;
             $new_info_schema=$new_info_schemas["table_name"];
             echo $new_info_schema . "<br>";
           endwhile;
           echo "</div>";
  
         $get_removed = "SELECT " . $chosen_table1 . ".*" .
           " FROM " . $chosen_table1 .
           " LEFT JOIN " . $chosen_table2 . 
           " ON " . $chosen_table1 . ".table_name = " . $chosen_table2 . ".table_name WHERE " .
           $chosen_table2 . ".table_name IS NULL";
         $removed_result = mysqli_query($conn,$get_removed);
         echo "<div class='componentDiv'>";
          echo "<b>" . $component_string[$component] . " in " . $flavor_name . " Version " . $version_name . " now removed from " . $flavor_name2 . " Version " . $version_name2 . "</b><br>";
          while($removed_info_schemas = mysqli_fetch_array(
            $removed_result,MYSQLI_ASSOC)):;
            $removed_info_schema=$removed_info_schemas["table_name"];
            echo $removed_info_schema . "<br>";
          endwhile;
       } elseif ($component == 'perf_schema') {
          $get_all = "SELECT " . $chosen_table2 . ".*" .
           " FROM " . $chosen_table2 ;

           $all_result = mysqli_query($conn,$get_all);
           echo "<div class='componentDiv'>";
           echo "<b>All " . $component_string[$component] . "s in " . $flavor_name2 . " Version " . $version_name2 . "</b><br>";
           while($all_tables = mysqli_fetch_array(
             $all_result,MYSQLI_ASSOC)):;
             $table=$all_tables["table_name"];
             echo $table . "<br>";
           endwhile;
           echo "</div>";

          $get_new = "SELECT " . $chosen_table2 . ".*" .
           " FROM " . $chosen_table2 .
           " LEFT JOIN " . $chosen_table1 . 
           " ON " . $chosen_table1 . ".table_name = " . $chosen_table2 . ".table_name WHERE " .
           $chosen_table1 . ".table_name IS NULL";
           $new_result = mysqli_query($conn,$get_new);
           echo "<div class='componentDiv'>";
           echo "<b>New " . $component_string[$component] . " in " . $flavor_name2 . " Version " . $version_name2 . "</b><br>";
           while($new_perf_schemas = mysqli_fetch_array(
             $new_result,MYSQLI_ASSOC)):;
             $new_perf_schema=$new_perf_schemas["table_name"];
             echo $new_perf_schema . "<br>";
           endwhile;
           echo "</div>";
  
         $get_removed = "SELECT " . $chosen_table1 . ".*" .
           " FROM " . $chosen_table1 .
           " LEFT JOIN " . $chosen_table2 . 
           " ON " . $chosen_table1 . ".table_name = " . $chosen_table2 . ".table_name WHERE " .
           $chosen_table2 . ".table_name IS NULL";
         $removed_result = mysqli_query($conn,$get_removed);
         echo "<div class='componentDiv'>";
          echo "<b>" . $component_string[$component] . " in " . $flavor_name . " Version " . $version_name . " now removed from " . $flavor_name2 . " Version " . $version_name2 . "</b><br>";
          while($removed_perf_schemas = mysqli_fetch_array(
            $removed_result,MYSQLI_ASSOC)):;
            $removed_perf_schema=$removed_perf_schemas["table_name"];
            echo $removed_perf_schema . "<br>";
          endwhile;
       } elseif ($component == 'plugin') {
          $get_all = "SELECT " . $chosen_table2 . ".*" .
           " FROM " . $chosen_table2 ;

           $all_result = mysqli_query($conn,$get_all);
           echo "<div class='componentDiv'>";
           echo "<b>All " . $component_string[$component] . "s in " . $flavor_name2 . " Version " . $version_name2 . "</b><br>";
           while($all_plugins = mysqli_fetch_array(
             $all_result,MYSQLI_ASSOC)):;
             $plugin=$all_plugins["plugin_name"];
             echo $plugin . "<br>";
           endwhile;

           echo "</div>";

           echo "</div>";
          $get_new = "SELECT " . $chosen_table2 . ".*" .
           " FROM " . $chosen_table2 .
           " LEFT JOIN " . $chosen_table1 . 
           " ON " . $chosen_table1 . ".plugin_name = " . $chosen_table2 . ".plugin_name WHERE " .
           $chosen_table1 . ".plugin_name IS NULL";
           $new_result = mysqli_query($conn,$get_new);
           echo "<div class='componentDiv'>";
           echo "<b>New " . $component_string[$component] . " in " . $flavor_name2 . " Version " . $version_name2 . "</b><br>";
           while($new_plugins = mysqli_fetch_array(
             $new_result,MYSQLI_ASSOC)):;
             $new_plugin=$new_plugins["plugin_name"];
             echo $new_plugin . "<br>";
           endwhile;
           echo "</div>";
  
         $get_removed = "SELECT " . $chosen_table1 . ".*" .
           " FROM " . $chosen_table1 .
           " LEFT JOIN " . $chosen_table2 . 
           " ON " . $chosen_table1 . ".plugin_name = " . $chosen_table2 . ".plugin_name WHERE " .
           $chosen_table2 . ".plugin_name IS NULL";
         $removed_result = mysqli_query($conn,$get_removed);
         echo "<div class='componentDiv'>";
          echo "<b>" . $component_string[$component] . " in " . $flavor_name . " Version " . $version_name . " now removed from " . $flavor_name2 . " Version " . $version_name2 . "</b><br>";
          while($removed_plugins = mysqli_fetch_array(
            $removed_result,MYSQLI_ASSOC)):;
            $removed_plugin=$removed_plugins["plugin_name"];
            echo $removed_plugin . "<br>";
          endwhile;
       } elseif ($component == 'mysql') {
echo "component is mysql";
       } elseif ($component == 'collate') {
          $get_new = "SELECT " . $chosen_table2 . ".*" .
           " FROM " . $chosen_table2 .
           " LEFT JOIN " . $chosen_table1 . 
           " ON " . $chosen_table1 . ".collation_name = " . $chosen_table2 . ".collation_name WHERE " .
           $chosen_table1 . ".collation_name IS NULL";
           $new_result = mysqli_query($conn,$get_new);
           echo "<div class='componentDiv'>";
           echo "<b>New " . $component_string[$component] . " in " . $flavor_name2 . " Version " . $version_name2 . "</b><br>";
           while($new_collations = mysqli_fetch_array(
             $new_result,MYSQLI_ASSOC)):;
             $new_collation=$new_collations["collation_name"];
             echo $new_collation . "<br>";
           endwhile;
           echo "</div>";
  
         $get_removed = "SELECT " . $chosen_table1 . ".*" .
           " FROM " . $chosen_table1 .
           " LEFT JOIN " . $chosen_table2 . 
           " ON " . $chosen_table1 . ".collation_name = " . $chosen_table2 . ".collation_name WHERE " .
           $chosen_table2 . ".collation_name IS NULL";
         $removed_result = mysqli_query($conn,$get_removed);
         echo "<div class='componentDiv'>";
          echo "<b>" . $component_string[$component] . " in " . $flavor_name . " Version " . $version_name . " now removed from " . $flavor_name2 . " Version " . $version_name2 . "</b><br>";
          while($removed_collations = mysqli_fetch_array(
            $removed_result,MYSQLI_ASSOC)):;
            $removed_collation=$removed_collations["collation_name"];
            echo $removed_collation . "<br>";
          endwhile;
       } elseif ($component == 'charset') {
          $get_new = "SELECT " . $chosen_table2 . ".*" .
           " FROM " . $chosen_table2 .
           " LEFT JOIN " . $chosen_table1 . 
           " ON " . $chosen_table1 . ".charset_name = " . $chosen_table2 . ".charset_name WHERE " .
           $chosen_table1 . ".charset_name IS NULL";
           $new_result = mysqli_query($conn,$get_new);
           echo "<div class='componentDiv'>";
           echo "<b>New " . $component_string[$component] . " in " . $flavor_name2 . " Version " . $version_name2 . "</b><br>";
           while($new_charsets = mysqli_fetch_array(
             $new_result,MYSQLI_ASSOC)):;
             $new_charset=$new_charsets["charset_name"];
             echo $new_charset . "<br>";
           endwhile;
           echo "</div>";
  
         $get_removed = "SELECT " . $chosen_table1 . ".*" .
           " FROM " . $chosen_table1 .
           " LEFT JOIN " . $chosen_table2 . 
           " ON " . $chosen_table1 . ".charset_name = " . $chosen_table2 . ".charset_name WHERE " .
           $chosen_table2 . ".charset_name IS NULL";
         $removed_result = mysqli_query($conn,$get_removed);
         echo "<div class='componentDiv'>";
          echo "<b>" . $component_string[$component] . " Removed from " . $flavor_name . " Version " . $version_name . "</b><br>";
          while($removed_charsets = mysqli_fetch_array(
            $removed_result,MYSQLI_ASSOC)):;
            $removed_charset=$removed_charsets["charset_name"];
            echo $removed_charset . "<br>";
          endwhile;
          echo "</div>";
       }
     //echo "</div>";
    ?>
   <?php
     }
   ?>
</body>
</html>
