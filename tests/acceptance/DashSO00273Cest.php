<?php

use Codeception\Util\ActionSequence;
use Codeception\Util\Locator;
use Codeception\Util\Shared\Asserts;
use Helper\Acceptance;

class DashSO00273Cest
{
    //Move ones back one week
    public function moveonesbackoneweek(DashAcceptanceTester $I)
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
        $ones='((//*[contains(@class, "item_artist")])[' . $x . ']//*[contains(@class, "row__cell")])[7]';
        $I->waitForElementVisible($ones);
        $I->click($ones);
        $I->click(Locator::contains('button', 'Confirm'));

        //Move ones
        $I->click($ones);
        $I->click(Locator::contains('button', 'Move Ones'));
        $I->click('//*[contains(@class, "inputStep__icon_left")]');
        $I->click(Locator::contains('button', 'Confirm'));

        $totalAfterMove = $I->grabTextFrom('(//*[@class="item__info__department-seniority-split"])[1]');

        //assert ones is not marked
        $item = '((//*[contains(@class, "item_artist")])[' . $x . ']//*[contains(@class, "row__cell")])[7]/*[contains(@class, "W")]';
        $I->dontSeeElement($item);

        //assert previous ones is marked
        $item = '((//*[contains(@class, "item_artist")])[' . $x . ']//*[contains(@class, "row__cell")])[6]/*[contains(@class, "W")]';
        $I->seeElement($item);

        $I->click(Locator::contains('button', 'File'));
        $I->click(Locator::contains('span', 'Save'));
        $I->loader();

        //assert Total was not changed
        $I->assertEquals($total, $totalAfterMove, 'Total was changed');
    }
}
