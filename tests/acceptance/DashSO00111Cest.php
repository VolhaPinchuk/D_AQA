<?php

use Codeception\Util\ActionSequence;
use Codeception\Util\Locator;
use Codeception\Util\Shared\Asserts;
use Helper\Acceptance;

class DashSO00111Cest extends BaseActions
{
    //add position lead
    public function addpositionlead(DashAcceptanceTester $I)
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

        $I->click('//*[@id="AddPositionPopup"]//button[contains(text(), "Add")]');

        //find added position
        $atr=$I->grabAttributeFrom($position, 'class');

        //assert position was added
        $I->assertContains('item_artist', $atr, 'Position was not added');

        //check title of added position
        $I->click('.item__info__expand-icon');
        $I->waitForElementNotVisible('.request-progress-bar__wrapper.wave-loader', 20);
        $x=$i-1;
        $seniority='(//*[@class="item__info__seniority"])[' . $x . ']';
        $positionValue=$I->grabTextFrom($seniority);

        $department=$I->grabTextFrom('(//*[@class="item__info__department-name"])[1]');
        $seniorityValue='Lead (' . $department . ')';

        //assert correct position was added
        $I->assertEquals($seniorityValue, $positionValue, 'Incorrect position was added');
    }
}
