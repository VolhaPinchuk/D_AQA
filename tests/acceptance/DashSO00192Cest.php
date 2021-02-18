<?php

use Codeception\Util\ActionSequence;
use Codeception\Util\Locator;
use Codeception\Util\Shared\Asserts;
use Helper\Acceptance;

class DashSO00192Cest
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
        $I->maximizeWindow();

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
            $I->loader();

            $elements = $this->helper->findElements('.item__info__department-name');
            echo (count($elements));

            //assert one discipline was shown
            $I->assertEquals(count($elements), 1, 'More then one discipline was shown');

            $shownDisciplineTitle = $I->grabTextFrom('.item__info__department-name');
            echo ($shownDisciplineTitle);

            //assert selected discipline was shown
            $I->assertEquals($disciplineTitle, $shownDisciplineTitle, 'Another discipline was shown');

            $I->click('//*[contains(@id, "v-filter-select")]');
            $elements = $this->helper->findElements('.select-all');
            $I->executeJS("arguments[0].scrollIntoView(false);", $elements);
            $I->click('.select-all');
            $I->click('.select-all');
            $I->click('//*[contains(@id, "v-filter-select")]');
        }
    }
}
