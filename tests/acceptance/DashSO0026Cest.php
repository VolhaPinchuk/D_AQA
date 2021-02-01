<?php

use Codeception\Util\ActionSequence;
use Codeception\Util\Locator;
use Codeception\Util\Shared\Asserts;
use Helper\Acceptance;

class DashSO0026Cest extends BaseActions
{
    //Remove Ones from the grid
    public function removeones(DashAcceptanceTester $I)
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

        $total = $I->grabTextFrom('(//*[@class="item__info__department-seniority-split"])[1]');

        //find number of the end position in the first department
        $i=2;
        $position='//*[@id="app"]//div[2]/div[2]/div[2]/div/div[2]/div[2]/div/div[' . $i . ']';
        $atr=$I->grabAttributeFrom($position, 'class');
        while (strpos($atr, 'item_artist') !== false):
            $i++;
            $position='//*[@id="app"]//div[2]/div[2]/div[2]/div/div[2]/div[2]/div/div[' . $i . ']';
            $atr=$I->grabAttributeFrom($position, 'class');
        endwhile;

        //open Add position popup
        $I->click('.item__info__department-add-icon');
        $I->waitForElementVisible('#AddPositionPopup', 20);

        //add position Lead
        $I->click('(//*[@id="AddPositionPopup"]//*[contains(@class,"ui-checkbox")])[1]');
        $I->waitForElementVisible('(//input[contains(@id,"VInput")])[1]');
        $I->fillField('(//input[contains(@id,"VInput")])[1]', '1');

        $I->click('//*[@id="AddPositionPopup"]//button[contains(text(), "Add")]');

        //add grey mark
        $x=$i-1;
        $line='((//*[contains(@class, "item_artist")])[' . $x . ']//*[contains(@class, "row__cell")])[7]';
        $I->click($line);
        $I->click('//*[@class="modal-dialog"]//button[contains(text(), "Confirm")]');
        $I->click(Locator::contains('button', 'File'));
        $I->click(Locator::contains('span', 'Save'));
        $I->loader();

        //remove mark
        for ($a = 1; $a <= $x; $a++){
            $item = '((//*[contains(@class, "item_artist")])[' . $a . ']//*[contains(@class, "row__cell")])[7]/*[contains(@class, "W")]';
            $itemIsHere = $I->elementIsHere($item);
            if ($itemIsHere !== false) {
                $I->click('((//*[contains(@class, "item_artist")])[' . $a . ']//*[contains(@class, "row__cell")])[7]');
                $a = $x+1;
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
