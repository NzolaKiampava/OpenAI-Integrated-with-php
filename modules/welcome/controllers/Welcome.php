<?php
class Welcome extends Trongate {
 
    function index() {
        //$this->view('welcome');
        $data['view_module'] = 'welcome';
        $data['view_file'] = 'homepage';
        $this->template('public', $data);
    }

}