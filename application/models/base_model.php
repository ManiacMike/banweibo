<?php
/* *
 * mike 
 * 2012-12-5
 * */

class Base_model extends CI_Model
{
	function __construct()
	{
		parent::__construct ();
//		$this->load->database();
	}
	
	function countRecord($tableName, $condition = '')
	{
		$sql = "SELECT COUNT(*) as num FROM  " . $tableName;
		if ($condition)
		{
			$sql .= " WHERE " . $condition . ' limit 1';
		}
		$res = $this->fetchRecord ( $sql, 1 );
		return $res ['num'];
	}
	
	function getFiledValues($field = array(), $tableName, $condition = '')
	{
		$ra = array ();
		if (is_array ( $field ))
		{
			$field = implode ( ',', $field );
		}
		elseif ($field == '' || empty ( $field ))
		{
			$field = '*';
		}
		$SQL = "SELECT {$field} FROM " . $tableName;
		if ($condition)
		{
			$SQL .= " WHERE " . $condition;
		}
		$ra = $this->fetchRecord ( $SQL );
		return $ra;
	}
	function fetchRecord($SQL, $isSingle = 0)
	{
		if(!property_exists($this,'db'))
		{
			$this->load->database();
		}
		$result = $this->db->query ( $SQL );
		if ($result == NULL)
		{
			return NULL;
		}
		elseif ($result == false)
		{
			die ( "<p style='color:red;'>sql error</p>{$SQL}" );
		}
		$this->db->close ();
		if($isSingle)
			return $result->row_array ();
		else
			return $result->result_array();
	}
	
	function getSingleFiledValues($field = array(), $tableName, $condition = '')
	{
		if (is_array ( $field ))
		{
			$field = implode ( ',', $field );
		}
		elseif ($field == '' || empty ( $field ))
		{
			$field = '*';
		}
		
		$SQL = "SELECT {$field} FROM " . $tableName;
		if ($condition)
		{
			$SQL .= " WHERE " . $condition;
		}
		$rs = $this->fetchRecord ( $SQL, 1 );
		return $rs;
	}
	
	function addRecords($field = array(), $tableName,$debug = false)
	{
		if(!property_exists($this,'db'))
		{
			$this->load->database();
		}
		if (empty ( $field ) || ! is_array ( $field ))
		{
			$this->db->close ();
			return FALSE;
		}
		$SQL = "INSERT INTO " . $tableName;
		$SQL .= $this->_parseSetSQL ( $field );
		if($debug)
		{
			echo $SQL;
		}
		$this->db->query ( $SQL );
		$id = $this->db->insert_id ();
		$this->db->close ();
		return $id;
	}
	
	function delRecords($tableName, $condition = '')
	{
		if(!property_exists($this,'db'))
		{
			$this->load->database();
		}
		$SQL = "DELETE FROM " . $tableName;
		if ($condition)
		{
			$SQL .= " WHERE " . $condition;
		}
		$return = $this->db->query ( $SQL );
		$this->db->close ();
		return $return ;
	}
	
	function updateRecords($tableName, $field = array(), $condition = '')
	{
		if(!property_exists($this,'db'))
		{
			$this->load->database();
		}
		if (empty ( $field ) || ! is_array ( $field ))
		{
			return FALSE;
		}
		$SQL = "UPDATE " . $tableName;
		$SQL .= $this->_parseSetSQL ( $field );
		if ($condition)
		{
			$SQL .= " WHERE " . $condition;
		}
		$return = $this->db->query ( $SQL );
		$this->db->close ();
		return $return ;
	}
	
	function _parseSetSQL($arrays, $expr = null)
	{
		if (! is_array ( $arrays ) && ! $expr)
		{
			return '';
		}
		$sets = " SET ";
		if ($expr)
		{
			foreach ( $expr as $v )
			{
				$sets .= " " . $v . ",";
			}
		}
		if ($arrays)
		{
			foreach ( $arrays as $k => $v )
			{
				$sets .= " " . $this->sqlMetadata ( $k ) . " = " . $this->sqlEscape ( $v ) . ",";
			}
		}
		$sets = trim ( $sets, "," );
		return ($sets) ? $sets : '';
	}
	
	function getAffectedRows()
	{
		return $this->affected_rows ();
	}
	function sqlMetadata($data, $tlists = array())
	{
		if (empty ( $tlists ) || ! in_array ( $data, $tlists ))
		{
			$data = str_replace ( array ( 
				'`' , 
				' ' 
			), '', $data );
		}
		return ' `' . $data . '` ';
	}
	function sqlEscape($var, $strip = true, $isArray = false)
	{
		if (is_array ( $var ))
		{
			if (! $isArray) return " '' ";
			foreach ( $var as $key => $value )
			{
				$var [$key] = trim ( $this->qlEscape ( $value, $strip ) );
			}
			return $var;
		}
		elseif (is_numeric ( $var ))
		{
			return " '" . $var . "' ";
		}
		else
		{
			return " '" . addslashes ( $strip ? stripslashes ( $var ) : $var ) . "' ";
		}
	}
}
