<?php

use Codeception\Util\ActionSequence;
use Codeception\Util\Locator;
use Codeception\Util\Shared\Asserts;
use Helper\Acceptance;

class DashSO0081Cest
{
    protected $helper = null;

    public function _before(DashAcceptanceTester $I, Acceptance $acceptanceHelper)
    {
        $this->helper = $acceptanceHelper;
    }

    //Published requests appear in the Dept Ones shopping cart
    public function publishedrequestsappearindeptonesshoppingcart(DashAcceptanceTester $I)
    {
        $I->amOnPage('/');

        //login as department
        $I->login('department', 'department');

        //go to DLDept.Ones page
        $I->dlDeptOnesPage();
        $I->loader();

        //clear shopping cart
        $I->deptpublish();
        try{
            $I->elementIsHere('//*[@id="publishPopup"]');
            $I->waitForElementClickable('//*[@id="publishPopup"]//button[contains(text(), "Yes, Publish")]');
            $I->click('//*[@id="publishPopup"]//button[contains(text(), "Yes, Publish")]');
        }
        catch (\Exception $exception) {
            $I->waitForElementClickable('//*[@id="publishWarningPopup"]//button[contains(text(), "Yes, Publish")]');
            $I->click('//*[@id="publishWarningPopup"]//button[contains(text(), "Yes, Publish")]');
            $I->loader();
            $popup = $I->elementIsHere('//*[@id="publishOnesPrioritiesPopup"]');
            if ($popup === true) {
                $I->waitForElementClickable('//*[@id="publishOnesPrioritiesPopup"]//button[contains(text(), "Yes, Publish")]');
                $I->click('//*[@id="publishOnesPrioritiesPopup"]//button[contains(text(), "Yes, Publish")]');
            }
        }
        $I->loader();
        $I->waitForElementNotVisible('//*[contains(@class, "toast-message")]');

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
        list ($i, $position) = $I->numberofendposition();

        //find columns number
        $column = '(//*[contains(@class, "item_artist")])[1]//*[contains(@class, "row__cell")]';
        $columns = $this->helper->findElements($column);
        $columnsNumber = count($columns);

        //open Add position popup and add position Lead
        $I->addpositionpopup();
        $I->addoneposition(1, 1);
        $I->confirmaddposition();

        //add random grey mark
        $I->addrandomgreymark($i, $columnsNumber);
        $I->waitForElementVisible('//span[@class="button__counter"]', 60);

        //publish
        $I->save();
        $I->loader();
        $I->waitForElementNotVisible('//*[contains(@class, "toast-message")]');
        $I->showonespublish();
        $I->wait(1);
        $publishRequestAlert = $I->elementIsHere('//*[@id="approvePopup"]//button[contains(text(), "Yes")]');
        if ($publishRequestAlert === true){
            $I->waitForElementClickable('//*[@id="approvePopup"]//button[contains(text(), "Yes")]');
            $I->click('//*[@id="approvePopup"]//button[contains(text(), "Yes")]');
            $notificationCount = $I->grabTextFrom('//span[@class="button__counter"]');
            for ($k=1; $k<=100; $k++){
                $I->wait(1);
                $notificationCountNew = $I->grabTextFrom('//*[@class="button__counter"]');
                if (intval($notificationCountNew) > intval($notificationCount)){
                    break;
                }
            }
            //publish request
            $I->click('//*[@class="button__content"]');
            $I->waitForElementVisible('//*[@id="NotificationsEl"]//*[@class="popup"]');
            $I->click('(//*[contains(text(), "Accept")])[1]');
            $I->loader();
            $I->waitForElementClickable('//*[@id="NotificationsEl"]//*[@class="popup__close"]');
            $I->click('//*[@id="NotificationsEl"]//*[@class="popup__close"]');
        }
        $I->loader();
        $I->logout();

        //login as department
        $I->login('department', 'department');

        //go to DLDept.Ones page
        $I->dlDeptOnesPage();
        $I->loader();

        //open cart
        $I->waitForElementClickable('//button[@id="ShowPopupButton"]');
        $I->click('//button[@id="ShowPopupButton"]');
        $I->waitForElementVisible('//*[@class="modal-container VPopupDraggable"]');
        $elements=$this->helper->findElements('//*[@class="modal-container VPopupDraggable"]//*[@class="show__info"]');
        for ($m=1; $m<=count($elements); $m++){
            $item='(//*[@class="modal-container VPopupDraggable"]//*[@class="show__info"])[' . $m . ']//*[@class="show__title"]';
            $showTitle = $I->grabTextFrom($item);
            if ($showTitle == $show){
                $count = '(//*[@class="modal-container VPopupDraggable"]//*[@class="show__info"])[' . $m . ']//*[@class="show__count"]';
                $showCount = $I->grabTextFrom($count);
            }
        }

        //assert published ones is on cart
        $I->assertEquals($showCount, 1, 'Ones publish is incorrect');
    }
}
