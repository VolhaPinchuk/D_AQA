<?php

use Codeception\Util\ActionSequence;
use Codeception\Util\Locator;
use Codeception\Util\Shared\Asserts;
use Helper\Acceptance;

class DashSO00191Cest extends BaseActions
{
    protected $helper = null;

    public function _before(DashAcceptanceTester $I, Acceptance $acceptanceHelper)
    {
        $this->helper = $acceptanceHelper;
    }

    //unselect all discipline
    public function unselectalldiscipline(DashAcceptanceTester $I)
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

        //unselect all discipline
        $I->click('//*[contains(@id, "v-filter-select")]');
        $I->click('.selected.select-all');
        $I->click('//*[contains(@id, "v-filter-select")]');
        $I->click('.Vue-apply-filter');

        $alert=$I->grabTextFrom('.toast-title');
        $I->assertEquals($alert, 'No disciplines selected.', 'Not true alert');
    }
}
