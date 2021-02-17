<?php

use Codeception\Util\ActionSequence;
use Codeception\Util\Locator;
use Codeception\Util\Shared\Asserts;
use Helper\Acceptance;

class DashSO0047Cest
{
    //Delete position with grey ones
    public function deletepositionwithgreyones(DashAcceptanceTester $I)
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

        $endArtistNumber = $i-1;

        //open Add position popup and add position Lead
        $I->addpositionpopup();
        $I->addoneposition(1);
        $I->confirmaddposition();

        //delete added position
        $x=$i-1;
        $I->click('.item__info__expand-icon');
        $trash = '(//*[contains(@class, "item_artist")])[' . $x . ']//*[contains(@class, "item__info__trash-icon")]';
        $I->click($trash);
        $I->click('(//*[contains(@id, "notification")]//*[contains(@class, "btn")])[2]');
        $I->loader();

        //find number of the end position in the first discipline
        list ($i, $position) = $I->numberofendposition();

        $endArtistNumberAfterDeleting = $i-1;

        //assert number of position in the first discipline was not changed
        $I->assertEquals($endArtistNumber, $endArtistNumberAfterDeleting, 'Position was not deleted');
    }
}
