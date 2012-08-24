<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Main extends CI_Controller 
{
 
    function __construct()
    {
        parent::__construct();
 
        /* Standard Libraries of codeigniter are required */
        $this->load->database();
        $this->load->helper('url');
        /* ------------------ */ 
 
        $this->load->library('grocery_CRUD');
		$this->grocery_crud->set_theme('datatables');
    }
 
    public function index()
    {
        echo "<h1>Welcome to the world of Codeigniter</h1>";//Just an example to ensure that we get into the function
        die();
    }
 
    public function jobs()
    {
        $this->grocery_crud->set_table('jobs');
		$this->grocery_crud->set_relation("server_id","servers","name");
		$this->grocery_crud->set_rules('min','Min','required');
		$this->grocery_crud->set_rules('hour','Hour','required');
		$this->grocery_crud->set_rules('dom','DOM','required');
		$this->grocery_crud->set_rules('dow','DOW','required');
		$this->grocery_crud->set_rules('command','Command','required');
		$this->grocery_crud->set_rules('server_id','Server','required');
        $output = $this->grocery_crud->render();
 
        $this->_list($output);
    }
	public function servers()
    {
        $this->grocery_crud->set_table('servers');
		$this->grocery_crud->add_action('More', '', 'main/details','ui-icon-plus');
        $output = $this->grocery_crud->render();
        $this->_list($output);
    }
	
	public function details()
	{
		echo "Details HERE";
	}	
	function _list($output = null)
    {
        $this->load->view('list.php',$output);    
    }
}
?>