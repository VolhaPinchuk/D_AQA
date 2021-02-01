<?php

use Codeception\Util\ActionSequence;
use Codeception\Util\Locator;
use Codeception\Util\Shared\Asserts;
use Helper\Acceptance;

class DashSO0017Cest extends BaseActions
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
        $I->loader();

        //select show
        $show = $I->selectShow();
        $I->loader();

        //select scenario
        $scenario = $I->selectScenario();
        $I->loader();

        $scenarioName = $I->grabTextFrom('.show-ones__header-select:nth-child(4)');

        //assert selected scenario is opened
        $I->assertContains($scenario, $scenarioName, 'Another scenario is shown');
    }
}
