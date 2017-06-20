<?php
class Schedules extends CI_Controller {
    function __construct(){
        parent :: __construct();
    }
    public function index()
    {
        try{
            $this->load->library('cron_schedule');
            $this->cron_schedule->dispatch();
        }catch(Exception $e){
            print_r($e);
        }
    }
    
    public function onetime() {
        try{
        	include_once APPPATH.'config/onetime.php';
        	$param['path'] = $onetime['path'];
            $this->load->library('cron_schedule');
            $this->cron_schedule->onetime($onetime['class'], $onetime['function'], $param);
        }catch(Exception $e){
            var_dump($e);
        }
    }
}
