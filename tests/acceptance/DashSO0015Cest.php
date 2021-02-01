<?php

use Codeception\Util\ActionSequence;
use Codeception\Util\Locator;
use Codeception\Util\Shared\Asserts;
use Helper\Acceptance;

class DashSO0015Cest
{
    //select show
    public function selectshow(DashAcceptanceTester $I)
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

        $showName = $I->grabTextFrom('(//*[@class="show-ones__header-select"])[1]');

        //assert selected show is opened
        $I->assertContains($show, $showName, 'Another show is shown');
    }
}
