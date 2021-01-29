<?php

use Codeception\Util\ActionSequence;
use Codeception\Util\Locator;
use Codeception\Util\Shared\Asserts;
use Helper\Acceptance;

class DashSO00121Cest extends BaseActions
{
    //mark ones for Lead as grey
    public function markonesgreylead(DashAcceptanceTester $I)
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

        $total = $I->grabTextFrom('(//*[@class="item__info__department-seniority-split"])[1]');
        $i = 0;
        $b = null;
        while ($a != '/'):
            $a = $total[$i];
            $b = $b . $a;
            $i++;
        endwhile;
        $d1 = substr($total, $i);

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

        $totalAfterAddMark = $I->grabTextFrom('(//*[@class="item__info__department-seniority-split"])[1]');

        //assert total did not change after add mark
        $I->assertEquals($total, $totalAfterAddMark, 'Total changed after add mark');

        //save mark
        $I->click(Locator::contains('button', 'File'));
        $I->click(Locator::contains('span', 'Save'));
        $I->waitForElementNotVisible('.request-progress-bar__wrapper.wave-loader', 40);

        //check mark was saved
        $totalAfterSave = $I->grabTextFrom('(//*[@class="item__info__department-seniority-split"])[1]');
        $i = 0;
        $c = null;
        while ($a != '/'):
            $a = $totalAfterSave[$i];
            $c = $c . $a;
            $i++;
        endwhile;
        $c = $c-1;
        $d2 = substr($totalAfterSave, $i);

        //assert total changed and the difference is equal 1
        $I->assertNotEquals($total, $totalAfterSave, 'Total was not changed');
        $I->assertEquals($b, $c, 'The difference is not equal 1');
        $I->assertEquals($d1, $d2, 'Other part of total was not changed');
    }
}
