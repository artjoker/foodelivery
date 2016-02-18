<?php
  namespace Slim;


  class Database
  {
    var $db = null;

    function __construct ()
    {
      $this->db = new \Mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
      $this->db->set_charset("utf8");
    }

    function esc ($var)
    {
      return $this->db->real_escape_string(trim($var));
    }

    function getOne ($query)
    {
      return $this->query($query)->fetch_assoc();
    }

    function query ($query)
    {
      return $this->db->query($query);
    }

    function getAll ($query)
    {
      $result = $this->query($query);
      $res    = array();
      while ($row = $result->fetch_assoc())
        $res[] = $row;
      return $res;
    }

    function getID ()
    {
      return $this->db->insert_id;
    }

    function getAffectedRows ()
    {
      return $this->db->affected_rows;
    }

  }