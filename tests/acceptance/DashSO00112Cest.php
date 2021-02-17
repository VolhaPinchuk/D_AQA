<?php

use Codeception\Util\ActionSequence;
use Codeception\Util\Locator;
use Codeception\Util\Shared\Asserts;
use Helper\Acceptance;

class DashSO00112Cest
{
    //add position key artist
    public function addpositionkeyartist(DashAcceptanceTester $I)
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

        //add position Key artist
        $I->addoneposition(2, 1);

        $I->confirmaddposition();
        $I->click('.item__info__expand-icon');

        //find added position
        $atr=$I->grabAttributeFrom($position, 'class');

        //assert position was added
        $I->assertContains('item_artist', $atr, 'Position was not added');

        //check title of added position
        $I->assertContains('item_artist', $atr, 'Position was not added');

        //check title of added position
        $x=$i-1;
        $seniority='(//*[@class="item__info__seniority"])[' . $x . ']';
        $positionValue=$I->grabTextFrom($seniority);

        $department=$I->grabTextFrom('(//*[@class="item__info__department-name"])[1]');
        $seniorityValue='Key Artist (' . $department . ')';

        //assert correct position was added
        $I->assertEquals($seniorityValue, $positionValue, 'Incorrect position was added');
    }
}