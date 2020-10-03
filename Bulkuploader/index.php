<?php
/**
 * interface/modules/custom_modules/Bulkuploader/index.php Upload screen and parser for the CCR XML.
 *
 * Functions to upload the CSV file data and to parse and insert it into respective entity tables.
 *
 * @package   OpenEMR
 * @link      http://www.open-emr.org
 * @copyright Copyright (c) 2020 KITB Technical Labs
 * @license   https://github.com/kitbtechlabs/KITB-OPEN-ERM-Modules/blob/master/Bulkuploader/LICENSE
 */

require_once(dirname(__FILE__) . "../../../../globals.php");


use OpenEMR\Common\Csrf\CsrfUtils;
use OpenEMR\Core\Header;

if (!acl_check('admin', 'batchcom')) {
    echo "<html>\n<body>\n<h1>";
    echo xlt('You are not authorized for this.');
    echo "</h1>\n</body>\n</html>";
    exit();
}

$data_type1 = "Patients";

?>
<html>
<head>
<?php Header::setupHeader(); ?>
<title><?php echo xlt('Bulk Upload Data');?></title>
<style>
.list-group-item {
    display: list-item;
}
</style>
</head>
<body class="body_top" >
  <main class="container">
    <div class="row">
      <div class="col-md-6 col-md-offset-3">
        <h3><?php echo xlt("Form to do bulk upload.");?></h3>
		<div class="row">			
			<form action="bulkuploader.php" method="post" enctype="multipart/form-data">
				<input type="hidden" name="csrf_token_form" value="<?php echo attr(CsrfUtils::collectCsrfToken()); ?>">
				<table border="0" cellpadding="0">
					<tbody>
						<tr>
							<td colspan="1" id="label_title" class="bold"><b><?php echo xlt('Select Entity Type'); ?>:</b></td>	
							<td colspan="1" class="text data" style="padding-left:5pt" id="text_title">							
							<SELECT class="form-control" id="data_type" name="data_type">
								<OPTION><?php echo xlt('Select Type'); ?></OPTION>
								<OPTION value="<?php echo xlt($data_type1); ?>"><?php echo xlt($data_type1); ?></OPTION>
							</SELECT>
							</td>
						</tr>
						<tr>
							<td colspan="1" id="label_title" class="bold"><b><?php echo xlt('Upload File'); ?>:</b></td>	
							<td colspan="1" class="text data" style="padding-left:5pt" id="text_title">							
								<input  class="form-control"  id="data_file" name="data_file" type="file" />	
							</td>
						</tr>
						<tr colspan=2>
							<td colspan="1" id="label_title" class="bold">
								<input class="form-control" type="submit" value="submit" />	
							</td>
						</tr>
				    </tbody>
				</table>
		   </form>
	   </div>
      </div>
    </div>
  </main>
</body>
</html>