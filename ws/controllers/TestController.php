<?php

namespace controllers;

use models\TestModel;


class TestController
{
    final private $Test_Model;
    public function __construct()
    {
        $this->Test_Model = new TestModel();
    }
    public function getAllEtudiant()
    {
        return $this->Test_Model->getAllEtudiant();
    }
}
