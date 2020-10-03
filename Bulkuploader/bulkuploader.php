<?php
/**
 * bulkuploader.php
 *
 * @package   OpenEMR
 * @link      http://www.open-emr.org
 * @copyright Copyright (c) 2020 KITB Technical Labs
 * @license   https://github.com/kitbtechlabs/KITB-OPEN-ERM-Modules/blob/master/Bulkuploader/LICENSE
 */

require_once(dirname(__FILE__) . "../../../../globals.php");


use OpenEMR\Common\Csrf\CsrfUtils;
use OpenEMR\Core\Header;

if (!CsrfUtils::verifyCsrfToken($_POST["csrf_token_form"])) {
    CsrfUtils::csrfNotVerified();
} 

$data_type = $_POST['data_type'];
if(isset($data_type)) { 
		
   //if ( isset($_POST["submit"]) ) {
	   
	if(trim($data_type) == 'Patients'){  
		require_once(dirname(__FILE__) . '/classes/Patients.php');
		$patients = new Patients(); 
		if ( isset($_FILES["data_file"])) {			
			if ($_FILES["data_file"]["error"] > 0) {
				echo "Return Code: " . $_FILES["data_file"]["error"] . "<br />";
			} else {
				
				//Store file in directory "upload" with the new name
				$storagename = "PATIENT-List_".date('Y-m-d')."-".time('H-i-s').".csv"; 
				$storage_path = $_SERVER['DOCUMENT_ROOT']."/upload/" . $storagename;
				$patients->setFilename($storagename);
				$patients->setFilepath($storage_path);
				$patients->upload($_FILES["data_file"]["tmp_name"]);
				$alertmsg = $patients->operate();
				$message =  "Bulk Upload is now complete.";	
			}
		}
	}
	else {
		$message =  "Select Data Entity to be uploaded.";	
	}
   //}
}  
?>
<html>
<head></head<head>
<?php Header::setupHeader(); ?>
<title><?php echo xlt('Bulk Upload Data');?></title>
<style>
.list-group-item {
    display: list-item;
}
</style>
</head>
<body>
<script language="Javascript">
<?php
if ($alertmsg) {
    echo "alert(" . js_escape($alertmsg) . ");\n";
}
?>
</script>
<h2><?php echo $message; ?></h2>
<a href="/interface/modules/custom_modules/Bulkuploader/index.php">Back</a>
</body>
</html>

