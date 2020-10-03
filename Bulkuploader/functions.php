<?php
/**
 * functions.php
 *
 * @package   OpenEMR
 * @link      http://www.open-emr.org
 * @author    Guruprasad K Murthy <guruprasad.km@gmail.com>
 * @copyright Copyright (c) 2020 KITB Technical Labs
 * @license   https://github.com/kitbtechlabs/KITB-OPEN-ERM-Modules/blob/master/Bulkuploader/LICENSE
 */

function updatePatientDataOld($data) {
	
	sqlQuery("UPDATE patient_data SET 
				`pubpid` = ?,				
				`title` = ?,
				`fname` = ?,
				`mname` = ?,
				`lname` = ?,
				`sex` = ?,
				`mothersname` = ?,
				`dob` = ?,
				`street` = ?,
				`city` = ?,
				`postal_code` = ?,
				`state` = ?,
				`country_code` = ?,
				`phone_home` = ?,
				`phone_cell` = ?,
				`phone_contact` = ?,
				`status` = ?,
				`email` = ?	
			where pid = ?", $data);
	return true;
	
}

function deletePatientData($pid, $pubpid ) {
	
	sqlQuery("DELETE FROM patient_data WHERE pid=? and pubpid=?", array($pid, $pubpid));
	return true;
	
}

?>