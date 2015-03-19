<?php
	include_once 'DBConnection.php';

	class Student extends DBConnection
	{
		/**
		 * Searches for a student
		 * @param studid
		 * @return info
		 */
		public function search($studid)
		{
			$personal_info = $this->get_row("SELECT s.id AS stud_records_id, s.studid, s.fname, s.lname, cr.name AS course_name, cr.code AS course_code, cl.id AS college_id, cl.name AS college_name, cl.code AS college_code FROM students s INNER JOIN courses cr ON (s.course_id = cr.id) INNER JOIN colleges cl ON (cr.college = cl.id) WHERE s.studid = '$studid'");
			return $personal_info;
		}
	}

?>