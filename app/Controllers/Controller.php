<?php

namespace App\Controllers;

use App\Entity;

class Controller extends Entity
{
    /**
     * @var array
     * @access protected
     */
    protected $request;

    /**
     * @var bool
     * @access protected
     */
    protected $hasConn = false;

    public function __construct()
    {
        parent::__construct();

        $this->set('request', $_REQUEST);
    }
}