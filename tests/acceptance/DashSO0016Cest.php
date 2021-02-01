<?php

use Codeception\Util\ActionSequence;
use Codeception\Util\Locator;
use Codeception\Util\Shared\Asserts;
use Helper\Acceptance;

class DashSO0016Cest
{
    //check default scenario
    public function defaultscenario(DashAcceptanceTester $I)
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

        $defaultScenarioName = $I->grabTextFrom('(//*[@class="show-ones__header-select"])[2]');

        //assert default scenario is MASTER
        $I->assertContains('MASTER', $defaultScenarioName, 'Another scenario is shown as default');
    }
}
