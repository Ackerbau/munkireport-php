<?php

class Business_unit extends Model {
    
    function __construct($unitid='', $property='')
    {
		parent::__construct('id', strtolower(get_class($this))); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['unitid'] = 0; $this->rt['unitid'] = 'VARCHAR(20)';
        $this->rs['property'] = '';
        $this->rs['value'] = '';

        $this->idx[] = array('property');
        $this->idx[] = array('value');

        // Table version. Increment when creating a db migration
        $this->schema_version = 0;

		// Create table if it does not exist
        $this->create_table();
        
        if($unitid and $property)
        {
            $this->retrieve_one('unitid=? AND property=?', array($unitid, $property));
            $this->unitid = $unitid;
            $this->property = $property;
        }
        
        return $this;
    }
    
	// ------------------------------------------------------------------------

	/**
	 * Retrieve all entries for unitid or all entries if unitid = empty
	 *
	 * @param integer unitid
	 * @return array
	 * @author abn290
	 **/
    function all($unitid = '')
    {
        $out = array();
        $where = $unitid ? 'unitid=?' : '';
        foreach($this->select( 'unitid, property, value', $where, $unitid, PDO::FETCH_OBJ ) as $obj)
        {
            switch($obj->property)
            {
                case 'user':
                    $out[$obj->unitid]['users'][] = $obj->value;
                    break;
                case 'machine_group':
                    $out[$obj->unitid]['machine_groups'][] = intval($obj->value);
                    break;
                default:
                    $out[$obj->unitid][$obj->property] = $obj->value;
            }

            $out[$obj->unitid]['unitid'] = $obj->unitid;

        }

        if($unitid && $out)
        {
            return $out[$unitid];
        }
        else
        {
            return array_values($out);
        }
        
        
    }

    function delete_all($unitid = '') 
    {
        $dbh=$this->getdbh();
        $sql = 'DELETE FROM '.$this->enquote( $this->tablename ).' WHERE '.$this->enquote( 'unitid' ).'=?';
        $stmt = $this->prepare( $sql );
        $stmt->bindValue( 1, $unitid );
        return $this->execute($stmt);
    }


}