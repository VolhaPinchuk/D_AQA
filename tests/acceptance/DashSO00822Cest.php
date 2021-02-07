<?php

use Codeception\Util\ActionSequence;
use Codeception\Util\Locator;
use Codeception\Util\Shared\Asserts;
use Helper\Acceptance;

class DashSO00822Cest
{
    //Delete scenario
    public function deletescenario(DashAcceptanceTester $I)
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

        try {
            $I->click(Locator::contains('button', 'File'));
            $I->click(Locator::contains('a', 'Save as'));

            //add scenario
            $I->fillField('//*[@placeholder="Scenario name"]', 'AQATest');
            $I->click('//*[@id="saveAsShowOnes"]//button[contains(text(), "Save")]');
            $I->dontSeeElement('//*[@class="toast-message"]');
        }
        catch (\Exception $exception){
            $I->click('//*[@id="saveAsShowOnes"]//button[contains(text(), "Cancel")]');
            $I->waitForElementClickable('(//*[@class="show-ones__header-select"])[2]', 20);
            $I->click('(//*[@class="show-ones__header-select"])[2]');
            $I->click('AQATest');
            $I->loader();

            //delete scenario
            $I->waitForElementNotVisible('//*[@class="toast-message"]');
            $I->click(Locator::contains('button', 'File'));
            $I->click(Locator::contains('a', 'Delete'));
            $I->waitForElementClickable('//*[@id="notification"]//button[contains(text(), "Yes")]');
            $I->click('//*[@id="notification"]//button[contains(text(), "Yes")]');
            $I->loader();

            $I->waitForElementClickable('(//*[@class="show-ones__header-select"])[2]', 20);
            $I->click('(//*[@class="show-ones__header-select"])[2]');
            $scenario = $I->elementIsHere(Locator::contains('a', 'AQATest'));

            //assert scenario was deleted
            $I->assertEquals($scenario, false, 'Scenario was not deleted');
        }
        finally {
            $I->loader();
            $I->waitForElementClickable(Locator::contains('button', 'File'), 20);

            //delete scenario
            $I->click(Locator::contains('button', 'File'));
            $I->click(Locator::contains('a', 'Delete'));
            $I->waitForElementClickable('//*[@id="notification"]//button[contains(text(), "Yes")]');
            $I->click('//*[@id="notification"]//button[contains(text(), "Yes")]');
            $I->loader();

            $I->waitForElementClickable('(//*[@class="show-ones__header-select"])[2]', 20);
            $I->click('(//*[@class="show-ones__header-select"])[2]');
            $scenario = $I->elementIsHere(Locator::contains('a', 'AQATest'));

            //assert scenario was deleted
            $I->assertEquals($scenario, false, 'Scenario was not deleted');
        }
    }
}
