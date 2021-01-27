<?php

use Codeception\Util\ActionSequence;
use Codeception\Util\Locator;
use Helper\Acceptance;

class BaseActions
{
    //select show in dropdown
    public function varShow(){
        return $selectedShow = 'A-LIB';
    }

    //select scenario in dropdown
    public function varScenario(){
        return $selectedScenario = '123';
    }
}
