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

        //open Ones tab
        $I->waitForElementVisible(Locator::contains('span', 'Show Ones'), 20);
        $I->wait(1);
        $I->click(Locator::contains('span', 'Show Ones'));
        $I->waitForElementVisible('.request-progress-bar__wrapper.wave-loader', 20);
        $I->waitForElementNotVisible('.request-progress-bar__wrapper.wave-loader', 20);
        $I->click('//*[@class="VTab__header"]//*[contains(text(),"Ones")]');
        $I->waitForElementVisible('.request-progress-bar__wrapper.wave-loader', 20);
        $I->waitForElementNotVisible('.request-progress-bar__wrapper.wave-loader', 20);

        //select show
        $I->waitForElementVisible('.show-ones__header-select:nth-child(2)');
        $I->click('.show-ones__header-select:nth-child(2)');
        $selectedShow = $this->varShow();
        $I->click($selectedShow);
        $I->waitForElementNotVisible('.request-progress-bar__wrapper.wave-loader', 20);

        //unselect all discipline
        $I->click('//*[contains(@id, "v-filter-select")]');
        $I->click('.selected.select-all');
        $I->click('//*[contains(@id, "v-filter-select")]');
        $I->click('.Vue-apply-filter');

        $alert=$I->grabTextFrom('.toast-title');
        $I->assertEquals($alert, 'No disciplines selected.', 'Not true alert');
    }
}
