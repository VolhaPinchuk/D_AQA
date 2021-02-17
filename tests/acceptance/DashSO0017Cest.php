<?php

use Codeception\Util\ActionSequence;
use Codeception\Util\Locator;
use Codeception\Util\Shared\Asserts;
use Helper\Acceptance;

class DashSO0017Cest
{
    //select scenario
    public function selectscenario(DashAcceptanceTester $I)
    {
        $I->amOnPage('/');

        //login as show
        $I->login('show', 'show');

        //open Show Ones page
        $I->showOnesPage();
        $I->loader();

        //open Ones tab
        $I->onesTab();

        //select show
        $show = $I->selectShow();
        $I->loader();

        //select scenario
        $scenario = $I->selectScenario();
        $I->loader();

        $scenarioName = $I->grabTextFrom('(//*[@class="show-ones__header-select"])[2]');

        //assert selected scenario is opened
        $I->assertContains($scenario, $scenarioName, 'Another scenario is shown');
    }
}
