<?php

	function array_to_option_list($array, $key, $value, $selection = NULL, $zero_option = NULL) {
		$output = "";
		$output .= "<option value =\"\">(Select)</option>";
		if($zero_option != NULL) {
			$output .= "<option value =\"0\">{$zero_option}</option>";
		}
		foreach($array as $row) {
			$output .= "<option value =\"{$row[$key]}\"";
			if($selection == $row[$key]) { $output .= " selected"; }
			$output .=">{$row[$value]}</option>";
		}
		return $output;
	}
	 
	function get_row_by_id($item_id, $table_name) {
		global $wpdb;
		$table = get_table_name($table_name);
		$query = $wpdb->prepare("SELECT * FROM {$table} WHERE id = %d LIMIT 1", $item_id);
		$row = $wpdb->get_row($query, ARRAY_A);
		if ($row != NULL) {
			return $row;
		} else {
			return NULL;
		}
	}

	function get_all_table_rows($table) {
		global $wpdb;
		$table_name = get_table_name($table);
		$query = "SELECT * ";
		$query .= "FROM {$table_name} ";
		$query .= "ORDER BY name";

		$result_set = $wpdb->get_results($query, ARRAY_A);
		if ($result_set != NULL) {
			return $result_set;
		} else {
			return NULL;
		}
	}

	function display_table_list($table) {
		global $wpdb;	
		$table_name = get_table_name($table); 
		
		$query = "SELECT * ";
		$query .= "FROM {$table_name} ";
		$query .= "ORDER BY name";
	
		$result_set = $wpdb->get_results($query, ARRAY_A);
	
		if($result_set != NULL) {
			echo "<legend>{$table}</legend>
				<table class=\"table table-striped\">
				 <tr><th>Name</th><th>Action</th></tr>";		
			foreach($result_set as $row) {
				echo "<tr>";
				echo "<td>{$row['name']}</td>";
				echo "<td><a href=\"admin.php?page=mokamrp_edit_{$table}&amp;id={$row['id']}\"><i class=\"icon-pencil\"></i> Edit</a></td></tr>";
			}
			echo "</table>";
		}   
	}

	function display_recipe_lines($recipe_id) {
		global $wpdb;	
		$materials = get_table_name("materials");
		$lines = get_table_name("lines");
		$recipes = get_table_name("recipes"); 
		
		$query = "SELECT {$lines}.id, {$lines}.material_type, {$materials}.name, {$recipes}.name AS recipe ";
		$query .= "FROM {$lines}, {$materials}, {$recipes} ";
		$query .= "WHERE ({$lines}.recipe_id = %d AND {$lines}.material_id = {$materials}.id AND {$lines}.recipe_id = {$recipes}.id)";
		
		$query_prep = $wpdb->prepare($query, $recipe_id);
	
		$result_set = $wpdb->get_results($query_prep, ARRAY_A);
	
		if($result_set != NULL) {
			echo "<legend>Recipe: {$result_set[0]['recipe']}</legend>
				<table class=\"table table-striped\">
				 <tr><th>Material</th><th>Type</th><th>Action</th></tr>";		
			foreach($result_set as $row) {
				echo "<tr>";
				echo "<td>{$row['name']}</td>";
				if($row['material_type'] == 1) {
					echo "<td>Input</td>";
				} else {
					echo "<td>Output</td>";
				}
				
				echo "<td><a href=\"admin.php?page=mokamrp_edit_lines&amp;id={$row['id']}\"><i class=\"icon-pencil\"></i> Edit</a></td></tr>";
			}
			echo "</table>";
		}   
	}

	function display_admin_navigation($active) {
		echo "<ul class=\"nav nav-pills\" style=\"margin-top:0px;padding-right:0px;padding-left:0px;\">";				  
		echo "<li";
		if($active == "groups") echo " class=\"active\"";
		echo "><a href=\"admin.php?page=mokamrp_new_groups\">Groups</a></li>";
		echo "<li";
		if($active == "recipes") echo " class=\"active\"";
		echo "><a href=\"admin.php?page=mokamrp_new_recipes\">Recipes</a></li>";
		echo "<li";
		if($active == "materials") echo " class=\"active\"";
		echo "><a href=\"admin.php?page=mokamrp_new_materials\">Materials</a></li>";
		echo "<li";
		if($active == "lines") echo " class=\"active\"";
		echo "><a href=\"admin.php?page=mokamrp_new_lines\">Lines</a></li>";
		echo "<li";
		if($active == "logs") echo " class=\"active\"";
		echo "><a href=\"admin.php?page=mokamrp_new_logs\">Logs</a></li>";
		echo "<li";
		if($active == "import") echo " class=\"active\"";
		echo "><a href=\"admin.php?page=mokamrp_import_export\">Import/Export</a></li>";
		echo "</ul>";
	}

	function display_create_page($type) {
		if($type != "actions") {
			//IF NOT ACTION
			display_admin_navigation($type);
			echo "<legend>Add {$type}</legend>
				<form action=\"admin.php?page=mokamrp_create_{$type}&amp;noheader=true\" method=\"post\">";
			wp_nonce_field( "mokamrp_create_{$type}","mokamrp_create_{$type}_nonce" );
			$edit = false;
			include(MOKAMRP_PATH . "/{$type}/{$type}_form.php"); 						
			echo	"<div class=\"form-actions\">
				  <button type=\"submit\" class=\"btn btn-primary\">Add {$type}</button>
				  <a href=\"admin.php?page=mokamrp_new_{$type}\" class=\"btn\">Cancel</a>
				</div>	
			</form>";
			display_table_list($type);
		} else {
			//IF ACTION
			echo "<legend>Log an Action</legend>
				<form action=\"admin.php?page=mokamrp_create_{$type}&amp;noheader=true\" method=\"post\">";
			wp_nonce_field( "mokamrp_create_{$type}","mokamrp_create_{$type}_nonce" );
			$edit = false;
			include(MOKAMRP_PATH . "/{$type}/{$type}_form.php"); 						
			echo	"<div class=\"form-actions\">
				<button type=\"submit\" class=\"btn btn-primary\">Log Action</button>
				<a href=\"admin.php?page=mokamrp_home\" class=\"btn\">Cancel</a>
				</div>	
				</form>";
		}
	}

	
	function display_edit_page($type, $message) {
		$id = $_GET['id']; 
		$row = get_row_by_id($id, $type);
		include(MOKAMRP_PATH . "/includes/header.php");
		echo "<h2>Edit {$type}: {$row['name']}</h2>";
		if (!empty($message)) {
			echo "<p>" . $message . "</p>";
		}
		echo "<form action=\"admin.php?page=mokamrp_edit_{$type}&amp;id={$row['id']}\" method=\"post\">";
		wp_nonce_field( "mokamrp_edit_{$type}","mokamrp_edit_{$type}_nonce" );
		$edit = true;
		include(MOKAMRP_PATH . "/{$type}/{$type}_form.php"); 
		echo "<div class=\"form-actions\">
			  <input type=\"submit\" name=\"submit\" id=\"submit\" value=\"Save Changes\" class=\"btn btn-primary\">
			  <a href=\"admin.php?page=mokamrp_edit_{$type}&amp;id={$row['id']}\" class=\"btn\">Cancel</a>
			</div>	
			</form>";
              
		 	display_table_list($type);
	}
	


	 /* **************************************
	  * ^^ ABOVE IN USE ^^
	  * **************************************/  

	function redirect_to( $location = NULL ) {
		if ($location != NULL) {
			wp_redirect( $location );
			exit;
		}
	}
	
	function today() {
		date_default_timezone_set(get_option('timezone_string'));
		$today = date("Y-m-d", strtotime("now"));
		
		return $today;
	}
	
	function time_now() {
		date_default_timezone_set(get_option('timezone_string'));
		$time_now = date("H:i", strtotime("now"));
		
		return $time_now;
	}
	
	function timestamp_now() {
		date_default_timezone_set(get_option('timezone_string'));
		$now = date("Y-m-d H:i:s", strtotime("now")); // For MySQL format should be: YYYY-MM-DD HH:MM:SS
		
		return $now;
	}
	
	function get_table_name( $name ) {
		global $wpdb;	
			
		if($name == "materials") {
			return $wpdb->prefix . "mokamrp_materials";
		} elseif ($name == "groups") {
			return $wpdb->prefix . "mokamrp_groups";
		} elseif ($name == "recipes") {
			return $wpdb->prefix . "mokamrp_recipes";
		} elseif ($name == "lines") {
			return $wpdb->prefix . "mokamrp_lines";
		} elseif ($name == "logs") {
			return $wpdb->prefix . "mokamrp_logs";
		} else {
			die;
		}
	}
	
	
	
	 /* **************************************
	  * ADMIN
	  * **************************************/  
	
	
	function query_to_csv($query, $filename, $attachment = false, $headers = true) {
        global $wpdb;	  
        if($attachment) {
            // send response headers to the browser
            header( 'Content-Type: text/csv' );
            header( 'Content-Disposition: attachment;filename='.$filename);
            $fp = fopen('php://output', 'w');
        } else {
            $fp = fopen($filename, 'w');
        }
        
        $result_set = $wpdb->get_results($query, ARRAY_A);
        
        if($headers) {
            // output header row (if at least one row exists)
            $row = $result_set[0];
            if($row) {
                fputcsv($fp, array_keys($row));
                // reset pointer back to beginning
                //mysql_data_seek($result, 0);
            }
        }
        
        foreach($result_set as $row) {
            fputcsv($fp, $row);
        }
        
        fclose($fp);
    }
    
    //Here is a function that accepts the path to a CSV file, 
    //and inserts all records to the given MySQL table, paying attention to the column names.
    //This assumes that the columns in the table have exactly the same name as the columns in the CSV file, 
    //except that the dots (".") are removed. This is because MySQL column names cannot contain dots.
	function csv_file_to_mysql_table($source_file, $target_table, $max_line_length=10000) { 
		global $wpdb;
		
		ini_set('auto_detect_line_endings',TRUE);
		
		if (($handle = fopen("$source_file", "r")) !== FALSE) { 
			$columns = fgetcsv($handle, $max_line_length, ","); 
			foreach ($columns as &$column) { 
				$column = str_replace(".","",$column); 
			} 
			$insert_query_prefix = "INSERT INTO $target_table (" . join(",",$columns) . ")\nVALUES"; 
			while (($data = fgetcsv($handle, $max_line_length, ",")) !== FALSE) { 
				while (count($data)<count($columns)) 
					array_push($data, NULL); 
				$query = "$insert_query_prefix (".join(",",quote_all_array($data)).");"; 
				$wpdb->query($query); 
			} 
			fclose($handle); 
		} 
	} 
	
	function quote_all_array($values) { 
		foreach ($values as $key=>$value) 
			if (is_array($value)) 
				$values[$key] = quote_all_array($value); 
			else 
				$values[$key] = quote_all($value); 
		return $values; 
	} 
	
	function quote_all($value) {
		global $wpdb;
			 
		if (is_null($value)) 
			return "NULL"; 
	
		$value = "'" . $wpdb->escape($value) . "'"; 
		return $value; 
	}
	
	
?>