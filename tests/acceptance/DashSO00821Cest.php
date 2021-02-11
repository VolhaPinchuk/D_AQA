<?php

use Codeception\Util\ActionSequence;
use Codeception\Util\Locator;
use Codeception\Util\Shared\Asserts;
use Helper\Acceptance;

class DashSO00821Cest
{
    protected $helper = null;

    public function _before(DashAcceptanceTester $I, Acceptance $acceptanceHelper)
    {
        $this->helper = $acceptanceHelper;
    }

    //Create new scenario
    public function createscenario(DashAcceptanceTester $I)
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

        //find rows quantity
        $rows = $this->helper->findElements('//*[contains(@class, "item_artist")]');
        $rowsNumber = count($rows);
        //find columns quantity
        $columns = $this->helper->findElements('(//*[contains(@class, "item_artist")])[1]//*[contains(@class, "row__cell")]');
        $columnsNumber = count($columns);

        //create arrow with statusId of each ones and without empty rows
        $m=1;
        for ($i=1; $i<=$rowsNumber; $i++){
            for ($k=1; $k<=$columnsNumber; $k++){
                $item = '((//*[contains(@class, "item_artist")])[' . $i . ']//*[contains(@class, "row__cell")])[' . $k . ']/*[contains(@class, "W")]';
                $itemIsHere = $I->elementIsHere($item);
                if ($itemIsHere === false){
                    $statusId[$m][$k] = '0';
                }
                else {
                    $atr = $I->grabAttributeFrom($item, 'class');
                    $statusId[$m][$k] = substr($atr, 10, 1);
                }
            }
            for ($l=1; $l<=$columnsNumber; $l++){
                if ($statusId[$m][$l] != '0' or $statusId[$m][$l] != '8'){
                    $m++;
                    break;
                }
            }
        }
        //count ones with each statusId in created arrow
        $statusId1 = 0;
        $statusId2 = 0;
        $statusId3 = 0;
        $statusId4 = 0;
        $statusId0 = 0;
        for ($i=1; $i<=$m-1; $i++){
            for ($k=1; $k<=$columnsNumber; $k++){
                if ($statusId[$i][$k] == '1'){
                    $statusId1++;
                }
                elseif ($statusId[$i][$k] == '2'){
                    $statusId2++;
                }
                elseif ($statusId[$i][$k] == '3'){
                    $statusId3++;
                }
                elseif ($statusId[$i][$k] == '4'){
                    $statusId4++;
                }
                else {
                    $statusId0++;
                }
            }
        }

        try {
            $I->click(Locator::contains('button', 'File'));
            $I->click(Locator::contains('a', 'Save as'));

            //add scenario
            $I->wait(1);
            $I->fillField('//*[@id="saveAsShowOnes"]//*[@placeholder="Scenario name"]', 'AQATest');
            $I->waitForElementClickable('//*[@id="saveAsShowOnes"]//button[contains(text(), "Save")]');
            $I->click('//*[@id="saveAsShowOnes"]//button[contains(text(), "Save")]');
            $I->wait(1);
            $I->dontSeeElement('//*[@class="toast-message"]');
        }
        catch (\Exception $exception){
            $I->waitForElementClickable('//*[@id="saveAsShowOnes"]//button[contains(text(), "Cancel")]');
            $I->click('//*[@id="saveAsShowOnes"]//button[contains(text(), "Cancel")]');
            $I->waitForElementClickable('(//*[@class="show-ones__header-select"])[2]', 10);
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

            //add scenario again
            $I->waitForElementClickable(Locator::contains('button', 'File'));
            $I->click(Locator::contains('button', 'File'));
            $I->click(Locator::contains('a', 'Save as'));
            $I->wait(1);
            $I->fillField('//*[@placeholder="Scenario name"]', 'AQATest');
            $I->waitForElementClickable('//*[@id="saveAsShowOnes"]//button[contains(text(), "Save")]');
            $I->click('//*[@id="saveAsShowOnes"]//button[contains(text(), "Save")]');
        }
        $I->loader();
        $I->waitForElementClickable('(//*[@class="show-ones__header-select"])[2]', 20);
        $scenario = $I->grabTextFrom('(//*[@class="show-ones__header-select"])[2]');

        //scenario was added
        $I->assertContains('AQATest', $scenario, 'Scenario was not added');

        //find rows quantity
        $rows = $this->helper->findElements('//*[contains(@class, "item_artist")]');
        $newRowsNumber = count($rows);
        $y=$m-1;
        //assert new scenario has the same rows as MASTER (without blank rows)
        $I->assertEquals($newRowsNumber, $y, 'error');

        for ($n=1; $n<=$newRowsNumber; $n++){
            for ($x=1; $x<=$columnsNumber; $x++){
                $item = '((//*[contains(@class, "item_artist")])[' . $n . ']//*[contains(@class, "row__cell")])[' . $a[$x] . ']/*[contains(@class, "W")]';
                $itemIsHere = $I->elementIsHere($item);
                if ($itemIsHere === false){
                    $newStatusId[$n][$x] = '0';
                }
                else {
                    $atr = $I->grabAttributeFrom($item, 'class');
                    $newStatusId[$n][$x] = substr($atr, 10, 1);
                }
            }
        }
        //count ones with each statusId in created arrow
        $newStatusId1 = 0;
        $newStatusId2 = 0;
        $newStatusId3 = 0;
        $newStatusId4 = 0;
        $newStatusId0 = 0;
        for ($i=1; $i<=$newRowsNumber; $i++){
            for ($k=1; $k<=$columnsNumber; $k++){
                if ($statusId[$i][$k] == '1'){
                    $newStatusId1++;
                }
                elseif ($statusId[$i][$k] == '2'){
                    $newStatusId2++;
                }
                elseif ($statusId[$i][$k] == '3'){
                    $newStatusId3++;
                }
                elseif ($statusId[$i][$k] == '4'){
                    $newStatusId4++;
                }
                else {
                    $newStatusId0++;
                }
            }
        }
        //asserts count ones for each statusId in new scenario is as count ones for each StatusId in MASTER
        $I->assertEquals($statusId1, $newStatusId1, 'New scenario is not as MASTER');
        $I->assertEquals($statusId2, $newStatusId2, 'New scenario is not as MASTER');
        $I->assertEquals($statusId3, $newStatusId3, 'New scenario is not as MASTER');
        $I->assertEquals($statusId4, $newStatusId4, 'New scenario is not as MASTER');
        $I->assertEquals($statusId0, $newStatusId0, 'New scenario is not as MASTER');

        //delete scenario
        $I->waitForElementClickable(Locator::contains('button', 'File'), 20);
        $I->click(Locator::contains('button', 'File'));
        $I->click(Locator::contains('a', 'Delete'));
        $I->waitForElementClickable('//*[@id="notification"]//button[contains(text(), "Yes")]');
        $I->click('//*[@id="notification"]//button[contains(text(), "Yes")]');
        $I->loader();
    }
}
