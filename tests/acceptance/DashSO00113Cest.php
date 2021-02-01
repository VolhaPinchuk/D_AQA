<?php

use Codeception\Util\ActionSequence;
use Codeception\Util\Locator;
use Codeception\Util\Shared\Asserts;
use Helper\Acceptance;

class DashSO00113Cest
{
    //add position artist
    public function addpositionartist(DashAcceptanceTester $I)
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
        $I->click('(//*[@id="AddPositionPopup"]//*[contains(@class,"ui-checkbox")])[3]');
        $I->waitForElementVisible('(//input[contains(@id,"VInput")])[1]');
        $I->fillField('(//input[contains(@id,"VInput")])[1]', '1');

        $I->click('//*[@id="AddPositionPopup"]//button[contains(text(), "Add")]');
        $I->click('.item__info__expand-icon');

        //find added position
        $atr=$I->grabAttributeFrom($position, 'class');

        //assert position was added
        $I->assertContains('item_artist', $atr, 'Position was not added');

        //check title of added position
        $x=$i-1;
        $seniority='(//*[@class="item__info__seniority"])[' . $x . ']';
        $positionValue=$I->grabTextFrom($seniority);

        $department=$I->grabTextFrom('(//*[@class="item__info__department-name"])[1]');
        $seniorityValue='Artist (' . $department . ')';

        //assert correct position was added
        $I->assertEquals($seniorityValue, $positionValue, 'Incorrect position was added');
    }
}
