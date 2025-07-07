<?php

namespace controllers;

use models\TestModel;

class TestController
{
    private TestModel $Test_Model;

    public function __construct()
    {
        $this->Test_Model = new TestModel();
    }

    public function getAllEtudiant(): array
    {
        return $this->Test_Model->getAllEtudiant();
    }
}
