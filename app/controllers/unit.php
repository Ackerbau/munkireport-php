<?php
class unit extends Controller
{
	function __construct()
	{
		if( ! $this->authorized())
		{
			redirect('auth/login');
		}
	}

	function index()
	{
		$data = array('session' => $_SESSION);

		echo 'BU dashboard<pre>';

		print_r($_SESSION);
		return;

		$obj = new View();


		// Check if there's a custom dashboard
		if( file_exists(VIEW_PATH.'dashboard/custom_dashboard'.EXT))
		{
			$obj->view('dashboard/custom_dashboard', $data);
		}
		else
		{
			$obj->view('dashboard/dashboard', $data);
		}
		
	}

	/**
	 * Get unit data for current user
	 *
	 * @author 
	 **/
	function get_data()
	{
		$out = array();

		// Initiate session
		$this->authorized();

		if(isset($_SESSION['business_unit']))
		{
			// Get data for this unit
			$unit = new Business_unit;
			$out = $unit->all($_SESSION['business_unit']);
		}

		$obj = new View();
        $obj->view('json', array('msg' => $out));
	}

	/**
	 * Get machine group data for current user
	 *
	 * @author 
	 **/
	function get_machine_groups()
	{
		$out = array();

		// Initiate session
		$this->authorized();

		if(isset($_SESSION['machine_groups']))
		{
			// Get data for this unit
			$mg = new Machine_group;
			foreach($_SESSION['machine_groups'] AS $group)
			{
				$out[] = $mg->all($group);
			}
		}

		$obj = new View();
        $obj->view('json', array('msg' => $out));
	}


	function listing($which = '')
	{
		if($which)
		{
			$data['page'] = 'clients';
			$data['scripts'] = array("clients/client_list.js");
			$view = 'listing/'.$which;
		}
		else
		{
			$data = array('status_code' => 404);
			$view = 'error/client_error';
		}

		$obj = new View();
		$obj->view($view, $data);
	}

	function reports($which = 'default')
	{
		if($which)
		{
			$data['page'] = 'clients';
			$view = 'report/'.$which;
		}
		else
		{
			$data = array('status_code' => 404);
			$view = 'error/client_error';
		}

		$obj = new View();
		$obj->view($view, $data);
	}
		
}