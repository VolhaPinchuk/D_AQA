<?php

use Codeception\Util\ActionSequence;
use Codeception\Util\Locator;
use Codeception\Util\Shared\Asserts;
use Helper\Acceptance;

class DashSO00116Cest extends BaseActions
{
    //add positions lead and artist
    public function addpositionsleadandartist(DashAcceptanceTester $I)
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

        //add position artist
        $I->click('(//*[@id="AddPositionPopup"]//*[contains(@class,"ui-checkbox")])[3]');
        $I->waitForElementVisible('(//input[contains(@id,"VInput")])[2]');
        $I->fillField('(//input[contains(@id,"VInput")])[2]', '1');

        $I->click('//*[@id="AddPositionPopup"]//button[contains(text(), "Add")]');

        //find added positions
        $atr1=$I->grabAttributeFrom($position, 'class');

        $x=$i+1;
        $position2='//*[@id="app"]//div[2]/div[2]/div[2]/div/div[2]/div[2]/div/div[' . $x . ']';
        $atr2=$I->grabAttributeFrom($position2, 'class');

        //assert positions were added
        $I->assertContains('item_artist', $atr1, 'Position was not added');
        $I->assertContains('item_artist', $atr2, 'Position was not added');

        //check title of added positions
        $I->click('.item__info__expand-icon');
        $I->loader();
        $x=$i-1;
        $seniority='(//*[@class="item__info__seniority"])[' . $x . ']';
        $positionValue1=$I->grabTextFrom($seniority);
        $x=$x+1;
        $seniority='(//*[@class="item__info__seniority"])[' . $x . ']';
        $positionValue2=$I->grabTextFrom($seniority);

        $department=$I->grabTextFrom('(//*[@class="item__info__department-name"])[1]');
        $seniorityValue1='Lead (' . $department . ')';
        $seniorityValue2='Artist (' . $department . ')';

        //assert correct positions were added
        $I->assertEquals($seniorityValue1, $positionValue1, 'Incorrect position was added');
        $I->assertEquals($seniorityValue2, $positionValue2, 'Incorrect position was added');
    }
}
