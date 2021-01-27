<?php

use Codeception\Util\ActionSequence;
use Codeception\Util\Locator;
use Codeception\Util\Shared\Asserts;
use Helper\Acceptance;

class DashSO00192Cest extends BaseActions
{
    protected $helper = null;

    public function _before(DashAcceptanceTester $I, Acceptance $acceptanceHelper)
    {
        $this->helper = $acceptanceHelper;
    }

    //Select one discipline
    public function selectonediscipline(DashAcceptanceTester $I)
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

        //select one discipline
        for ($i=2; $i<=24; $i++){
            $I->click('//*[contains(@id, "v-filter-select")]');
            $discipline = '(//*[contains(@aria-labelledby, "v-filter-select")]//label[contains(@for, "VCheckbox")])[' . $i . ']';
            $disciplineTitle = $I->grabTextFrom($discipline);
            $I->click($discipline);
            $I->click('//*[contains(@id, "v-filter-select")]');
            $I->click('.Vue-apply-filter');
            $I->waitForElementVisible('.request-progress-bar__wrapper.wave-loader', 20);
            $I->waitForElementNotVisible('.request-progress-bar__wrapper.wave-loader', 20);

            $elements = $this->helper->findElements('.item__info__department-name');
            echo (count($elements));

            //assert one discipline was shown
            $I->assertEquals(count($elements), 1, 'More then one discipline was shown');

            $shownDisciplineTitle = $I->grabTextFrom('.item__info__department-name');
            echo ($shownDisciplineTitle);

            //assert selected discipline was shown
            $I->assertEquals($disciplineTitle, $shownDisciplineTitle, 'Another discipline was shown');

            $I->click('//*[contains(@id, "v-filter-select")]');
            $I->click('.select-all');
            $I->click('.select-all');
            $I->click('//*[contains(@id, "v-filter-select")]');
        }
    }
}
