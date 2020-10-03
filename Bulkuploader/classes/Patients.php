<?php

/**
 * classes/Patients.php
 *
 * @package   OpenEMR
 * @link      http://www.open-emr.org
 * @author    Guruprasad K Murthy <guruprasad.km@gmail.com>
 * @copyright Copyright (c) 2020 KITB Technical Labs
 * @license   https://github.com/kitbtechlabs/KITB-OPEN-ERM-Modules/blob/master/OPEN%20EMR%20Modules/Bulkuploader/LICENSE
 */

require_once(dirname(__FILE__) . "../../../../../globals.php");
require_once(dirname(__FILE__) . "/../functions.php");
require_once("$srcdir/patient.inc");
require_once("$srcdir/options.inc.php");

class Patients{
	
	private $filename;
	private $filepath;
		
	public function setFilename($filename = null) {
		if(isset($filename))
			$this->filename = $filename;
	}
	
	public function setFilepath($filepath = null) {
		if(isset($filepath))
			$this->filepath = $filepath;
	}
	
	public function upload($tmp_file){
		move_uploaded_file($tmp_file, $this->filepath);
	}
	
	public function operate() {
		
		if ( isset($this->filename) && $file = fopen( $this->filepath , 'r+' ) ) {

			//echo "File opened.<br />";

			$firstline = fgets ($file, 4096 );
				//Gets the number of fields, in CSV-files the names of the fields are mostly given in the first line
			$num = strlen($firstline) - strlen(str_replace(",", "", $firstline));

				//save the different fields of the firstline in an array called fields
			$fields = array();
			$fields = explode( ",", $firstline, ($num+1) );
			
			$line = array();
			$i = 0;
			
			$tblname   = 'patient_data';
			
			//CSV: one line is one record and the cells/fields are seperated by ";"
			//so $dsatz is an two dimensional array saving the records like this: $dsatz[number of record][number of cell]
			while ( $line[$i] = fgets ($file, 4096) ) {
				
				// here, we lock the patient data table while we find the most recent max PID
				// other interfaces can still read the data during this lock, however
				// sqlStatement("lock tables patient_data read");

				
				
				$dsatz[$i] = array();
				$dsatz[$i] = explode( ",", $line[$i], ($num+1) ); 
				
				$extid = $dsatz[$i][1];
				
				$result = sqlQuery("SELECT pubpid, pid, fname, mname, lname FROM patient_data where pubpid= " . $extid);
				
				if( isset($result['pubpid']) ){
					$mode = 'edit';
					$pid = $result['pid'];					
				} else {
					$mode = 'add';
					$result = sqlQuery("SELECT MAX(pid)+1 AS pid FROM patient_data");
					$pid = 1;
					if ($result['pid'] > 1) {
						$pid = $result['pid'];
					}
				}  
				if( (strlen($dsatz[$i][3]) == 0) && (strlen($dsatz[$i][4]) == 0)  && (strlen($dsatz[$i][5]) ==0) ){
					$mode = 'delete';
				}
				
				$newdata[$tblname]['pubpid'] = $extid;				
				$newdata[$tblname]['title'] = $dsatz[$i][2];
				$newdata[$tblname]['fname'] = $dsatz[$i][3];
				$newdata[$tblname]['mname'] = $dsatz[$i][4];
				$newdata[$tblname]['lname'] = $dsatz[$i][5];
				$newdata[$tblname]['sex'] = $dsatz[$i][6];
				$newdata[$tblname]['mothersname'] = $dsatz[$i][7];
				$newdata[$tblname]['dob'] = $dsatz[$i][8];
				$newdata[$tblname]['street'] = $dsatz[$i][9];
				$newdata[$tblname]['city'] = $dsatz[$i][10];
				$newdata[$tblname]['postal_code'] = $dsatz[$i][11];
				$newdata[$tblname]['state'] = $dsatz[$i][12];
				$newdata[$tblname]['country_code'] = $dsatz[$i][13];
				$newdata[$tblname]['phone_home'] = $dsatz[$i][14];
				$newdata[$tblname]['phone_cell'] = $dsatz[$i][15];
				$newdata[$tblname]['phone_contact'] = $dsatz[$i][16];
				$newdata[$tblname]['status'] = $dsatz[$i][17];
				$newdata[$tblname]['email'] = $dsatz[$i][18];				
				
				if($mode == 'add') {
					updatePatientData($pid, $newdata[$tblname], true);
				} else if($mode == 'edit'){
					$newdata[$tblname]['pid'] = $pid;
					updatePatientDataOld($newdata[$tblname]);
				} else {
					deletePatientData( $pid, $extid );
				}
				$i++;
				
			}
			
		}
		$msg = "Patient data uploaded successfully";
		return $msg;
	}
	
}
?>