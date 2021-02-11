<?php

use Codeception\Util\ActionSequence;
use Codeception\Util\Locator;
use Codeception\Util\Shared\Asserts;
use Helper\Acceptance;

class Dash000PreconditionCest
{
    public function _before(DashAcceptanceTester $I)
    {
    }

    //Precondition
    public function precondition(DashAcceptanceTester $I)
    {
        $I->amOnPage('/');

        //login as show
        $I->login('show', 'show');

        //open manage shows page
        $I->manageShowsPage();
        $I->loader();

        //try to create new show
        $I->click('//a[contains(text(), "Create New Show")]');
        $I->waitForElementVisible('//*[contains(text(), "Create New Show")]', 20);
        //show code
        $I->wait(1);
        $I->fillField('//input[contains(@class, "show-code")]', 'AQA-Test');
        $I->scrollTo('//*[contains(text(), "Create Show")]');
        $I->wait(5);
        $I->click('//*[contains(text(), "Create Show")]');
        $I->wait(5);
        $showCodeError = $I->grabAttributeFrom('//*[@id="showCode-error"]', 'style');
        if ($showCodeError === 'display: none') {
            //show type
            $I->click('//*[contains(@class, "show-type")]');
            $I->click('(//*[contains(@data-original-index, "1")])[1]');
            $I->wait(5);
            //show status
            $I->click('//*[@data-id="show-status"]');
            $I->click('(//*[contains(@data-original-index, "1")])[2]');
            $I->wait(5);
            //show category
            $I->click('//*[@data-id="show-category"]');
            $I->click('(//*[contains(@data-original-index, "1")])[3]');
            $I->wait(5);
            //start date
            $I->click('//*[@id="start-date"]');
            $I->click('//*[@class="datepicker-days"]//tr[2]//tr[1]');
            $I->wait(5);
            //end date
            $I->click('//*[@id="end-date"]');
            for ($i = 1; $i <= 6; $i++) {
                $I->click('//*[@class="datepicker-days"]//*[@class="next"]');
            }
            $I->click('//*[@class="datepicker-days"]//tr[5]//tr[7]');
            $I->wait(5);
            //seniority split
            $I->fillField('//*[@id="4"]', 50);
            $I->fillField('//*[@id="2"]', 25);
            $I->fillField('//*[@id="1"]', 25);
            $I->wait(5);
            //show colour
            $I->fillField('//*[@name="color"]', 'ad6926');
            $I->wait(5);
            //amount of assets
            $I->fillField('//*[@name="assetAmount"]', '1000000');
            $I->wait(5);
            //amount of shots
            $I->fillField('//*[@name="shotAmount"]', '1000');
            $I->wait(5);
            //show currency
            $I->click('//*[@data-id="show-currency"]');
            $I->click('(//*[contains(@data-original-index, "4")])[1]');
            $I->wait(5);
            //primary location
            $I->click('//*[@data-id="primaryLocationId"]');
            $I->click('(//*[contains(@data-original-index, "1")])[6]');
            $I->wait(5);
            //secondary location
            $I->click('//*[@data-id="secondaryFacilities[]"]');
            $I->click('//*[contains(@class, "secondary-facilities-dropdown")]//button[contains(text(), "Select All")]');
            $I->wait(5);
            //go to show input
            $I->click('//*[contains(text(), "Show Input")]');
            //primary producer
            $I->waitForElementClickable('(//*[contains(@class, "chosen-container")])[1]');
            $I->click('(//*[contains(@class, "chosen-container")])[1]');
            $I->click('(//*[contains(@class, "chosen-container")])[1]//li[2]');
            $I->wait(5);
            //executive produсer
            $I->click('(//*[contains(@class, "chosen-container")])[2]');
            $I->click('(//*[contains(@class, "chosen-container")])[2]//*[contains(text(), "showMpc")]');
            $I->wait(5);
            //create show
            $I->click('//button[contains(text(), "Create Show")]');
            $I->wait(5);
            $I->loader();
            $date = new DateTime();

        }
    }
}
