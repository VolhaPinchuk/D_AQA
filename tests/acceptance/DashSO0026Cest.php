<?php

use Codeception\Util\ActionSequence;
use Codeception\Util\Locator;
use Codeception\Util\Shared\Asserts;
use Helper\Acceptance;

class DashSO0026Cest
{
    protected $helper = null;

    public function _before(DashAcceptanceTester $I, Acceptance $acceptanceHelper)
    {
        $this->helper = $acceptanceHelper;
    }

    //Remove Ones from the grid
    public function removeones(DashAcceptanceTester $I, Acceptance $acceptanceHelper)
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

        $total = $I->grabTextFrom('(//*[@class="item__info__department-seniority-split"])[1]');

        //find number of the end position in the first department
        list ($i, $position) = $I->numberofendposition();

        //open Add position popup and add position Lead
        $I->addpositionpopup();
        $I->addoneposition(1, 1);
        $I->confirmaddposition();

        //find columns number
        $columnsNumber = $I->columnsnumber($acceptanceHelper);

        //add random grey mark
        list ($a, $b) = $I->addrandomgreymark($i, $columnsNumber);
        $I->save();

        //remove mark
        for ($k = 1; $k <= $i-1; $k++){
            $item = '((//*[contains(@class, "item_artist")])[' . $k . ']//*[contains(@class, "row__cell")])[' . $b . ']/*[contains(@class, "W")]';
            $itemIsHere = $I->elementIsHere($item);
            if ($itemIsHere !== false) {
                $I->click('((//*[contains(@class, "item_artist")])[' . $k . ']//*[contains(@class, "row__cell")])[' . $b . ']');
                $k = $i;
                $I->waitForElementVisible(Locator::contains('button', 'Remove Ones'));
                $I->click(Locator::contains('button', 'Remove Ones'));
                $I->click(Locator::contains('button', 'Confirm'));
                $I->waitForElementNotVisible('.toast-message');
                $I->click(Locator::contains('button', 'File'));
                $I->click(Locator::contains('span', 'Save'));
                $I->loader();
            }
        }
        $totalAfterRemove = $I->grabTextFrom('(//*[@class="item__info__department-seniority-split"])[1]');

        //assert total was not changed
        $I->assertEquals($total, $totalAfterRemove, 'Total was changed');
    }
}
