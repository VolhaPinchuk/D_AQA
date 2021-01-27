<?php

use Codeception\Util\ActionSequence;
use Codeception\Util\Locator;
use Codeception\Util\Shared\Asserts;
use Helper\Acceptance;

class DashSO00114Cest extends BaseActions
{
    //add positions lead and key artist
    public function addpositionsleadandkeyartist(DashAcceptanceTester $I)
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

        //add position key artist
        $I->click('(//*[@id="AddPositionPopup"]//*[contains(@class,"ui-checkbox")])[2]');
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
        $I->waitForElementNotVisible('.request-progress-bar__wrapper.wave-loader', 20);
        $x=$i-1;
        $seniority='(//*[@class="item__info__seniority"])[' . $x . ']';
        $positionValue1=$I->grabTextFrom($seniority);
        $x=$x+1;
        $seniority='(//*[@class="item__info__seniority"])[' . $x . ']';
        $positionValue2=$I->grabTextFrom($seniority);

        $department=$I->grabTextFrom('(//*[@class="item__info__department-name"])[1]');
        $seniorityValue1='Lead (' . $department . ')';
        $seniorityValue2='Key Artist (' . $department . ')';

        //assert correct positions were added
        $I->assertEquals($seniorityValue1, $positionValue1, 'Incorrect position was added');
        $I->assertEquals($seniorityValue2, $positionValue2, 'Incorrect position was added');
    }
}
