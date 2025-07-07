<?php

namespace controllers;

use Flight;

class TestController
{
    public  function sendTo()
    {
        $pageName = Flight::request()->query['pageName'];
        Flight::render($pageName);
    }
}
