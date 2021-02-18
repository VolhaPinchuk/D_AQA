<?php

use Codeception\Util\ActionSequence;
use Codeception\Util\Locator;
use Codeception\Util\Shared\Asserts;
use Helper\Acceptance;

class DashSO00271Cest
{
    //Move ones forward one week
    public function moveonesforwardoneweek(DashAcceptanceTester $I)
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
        list ($i, $position) = $I->numberofendposition();

        //open Add position popup
        $I->addpositionpopup();

        //add position Lead
        $I->addoneposition(1, 1);
        $I->confirmaddposition();

        //add grey mark
        $x=$i-1;
        $ones='((//*[contains(@class, "item_artist")])[' . $x . ']//*[contains(@class, "row__cell")])[7]';
        $I->waitForElementVisible($ones);
        $I->click($ones);
        $I->click(Locator::contains('button', 'Confirm'));

        //Move ones
        $I->click($ones);
        $I->click(Locator::contains('button', 'Move Ones'));
        $I->click('//*[contains(@class, "inputStep__icon_right")]');
        $I->click(Locator::contains('button', 'Confirm'));

        $totalAfterMove = $I->grabTextFrom('(//*[@class="item__info__department-seniority-split"])[1]');

        //assert ones is not marked
        $item = '((//*[contains(@class, "item_artist")])[' . $x . ']//*[contains(@class, "row__cell")])[7]/*[contains(@class, "W")]';
        $I->dontSeeElement($item);

        //assert next ones is marked
        $item = '((//*[contains(@class, "item_artist")])[' . $x . ']//*[contains(@class, "row__cell")])[8]/*[contains(@class, "W")]';
        $I->seeElement($item);

        $I->save();
        $I->loader();
        $I->waitForElementClickable('(//*[@class="show-ones__header-select"])[1]');

        //assert Total was not changed
        $I->assertEquals($total, $totalAfterMove, 'Total was changed');
    }
}
