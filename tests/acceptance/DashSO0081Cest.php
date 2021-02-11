<?php

use Codeception\Util\ActionSequence;
use Codeception\Util\Locator;
use Codeception\Util\Shared\Asserts;
use Helper\Acceptance;

class DashSO0081Cest
{
    //Published requests appear in the Dept Ones shopping cart
    public function publishedrequestsappearindeptonesshoppingcart(DashAcceptanceTester $I)
    {
        $I->amOnPage('/');

        //login as department
        $I->login('department', 'department');

        //clear shopping cart
        $I->click(Locator::contains('button', 'File'));
        $I->click(Locator::contains('a', 'Publish'));
        $I->loader();

        //logout
        $I->logout();

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
        $I->click('(//*[@id="AddPositionPopup"]//*[contains(@class,"ui-checkbox")])[1]');
        $I->waitForElementVisible('(//input[contains(@id,"VInput")])[1]');
        $I->fillField('(//input[contains(@id,"VInput")])[1]', '1');

        $I->click('//*[@id="AddPositionPopup"]//button[contains(text(), "Add")]');
        $I->click('.item__info__expand-icon');

        //add 5 grey marks
        $x=$i-1;
        for ($m=1; $m<=5; $m++){
            $a[$m] = rand(1, $columnsNumber);
        }
        for ($k=1; $k<=5; $k++){
            $ones='((//*[contains(@class, "item_artist")])[' . $x . ']//*[contains(@class, "row__cell")])[' . $a[$k] . ']';
            $I->waitForElementVisible($ones);
            $I->click($ones);
            $I->click(Locator::contains('button', 'Confirm'));
        }

        //publish
        $I->click(Locator::contains('button', 'File'));
        $I->click(Locator::contains('a', 'Save'));
        $I->loader();
        $I->waitForElementClickable(Locator::contains('button', 'File'));
        $I->click(Locator::contains('button', 'File'));
        $I->click(Locator::contains('a', 'Publish'));
        $I->click('((//*[contains(@class, "publishPopup__body__tabs-group")])[1]//*[contains(@class, "ui-checkbox_default")])[1]');
        $I->click('((//*[contains(@class, "publishPopup__body__tabs-group")])[2]//*[contains(@class, "ui-checkbox_default")])[1]');
        $I->click('//*[@class="publishPopup"]//button[contains(text(), "Send")]');
        $I->loader();
    }
}
