<?php

use Codeception\Util\ActionSequence;
use Codeception\Util\Locator;
use Codeception\Util\Shared\Asserts;
use Helper\Acceptance;

class DashSO00115Cest
{
    //add positions key artist and artist
    public function addpositionskeyartistandartist(DashAcceptanceTester $I)
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
        list ($i, $position) = $I->numberofendposition();

        //open Add position popup
        $I->addpositionpopup();

        //add position key artist
        $I->addoneposition(2, 1);

        //add position artist
        $I->addoneposition(3, 2);

        $I->confirmaddposition();
        $I->click('.item__info__expand-icon');

        //find added positions
        $atr1=$I->grabAttributeFrom($position, 'class');

        $x=$i+1;
        $position2='//*[@id="app"]//div[2]/div[2]/div[2]/div/div[2]/div[2]/div/div[' . $x . ']';
        $atr2=$I->grabAttributeFrom($position2, 'class');

        //assert positions were added
        $I->assertContains('item_artist', $atr1, 'Position was not added');
        $I->assertContains('item_artist', $atr2, 'Position was not added');

        //check title of added positions
        $x=$i-1;
        $seniority='(//*[@class="item__info__seniority"])[' . $x . ']';
        $positionValue1=$I->grabTextFrom($seniority);
        $x=$x+1;
        $seniority='(//*[@class="item__info__seniority"])[' . $x . ']';
        $positionValue2=$I->grabTextFrom($seniority);

        $department=$I->grabTextFrom('(//*[@class="item__info__department-name"])[1]');
        $seniorityValue1='Key Artist (' . $department . ')';
        $seniorityValue2='Artist (' . $department . ')';

        //assert correct positions were added
        $I->assertEquals($seniorityValue1, $positionValue1, 'Incorrect position was added');
        $I->assertEquals($seniorityValue2, $positionValue2, 'Incorrect position was added');
    }
}
