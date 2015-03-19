<?php
class DBConnection 
{
	// Database connection resource
	protected $dbc;
	
	// Constructor
	function __construct($host, $username, $password, $database) 
	{
		$this->dbc = pg_connect("host=".$host." user=".$username." password=".$password." dbname=".$database);
	}
	
	// simple querying, nothing returned?
	function query($sql)
	{
		pg_query($this->dbc, $sql);
	}
	
	// Get value of one variable (e.g. "SELECT name FROM contacts WHERE id = 1" or "INSERT INTO ... RETURNING xxxxx")
	function get_var($sql)
	{
		$query = pg_query($this->dbc, $sql);
		$row = pg_fetch_array($query, 0);
		return $row[0];
	}
	
	// Get one row
	function get_row($sql)
	{
		$query = pg_query($this->dbc, $sql);
		$row = pg_fetch_array($query, 0);
		return $row;
	}
	
	// Get results
	function get_results($sql)
	{
		$results = array();
		$query = pg_query($this->dbc, $sql);
		while ($row = pg_fetch_array($query))
		{
			$results[] = $row;
		}
		return $results;
	}
}
?>