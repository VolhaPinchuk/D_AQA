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

        $I->click('//*[contains(@id, "v-filter-select")]');
        $I->click('.select-all');
        for ($i=2; $i<=6; $i++) {
            $discipline = '(//*[contains(@aria-labelledby, "v-filter-select")]//label[contains(@for, "VCheckbox")])[' . $i . ']';
            $I->click($discipline);
        }
        $I->click('//*[contains(@id, "v-filter-select")]');
        $I->click('.Vue-apply-filter');
        $I->loader();

        //find rows quantity
        $rows = $this->helper->findElements('//*[contains(@class, "item_artist")]');
        $rowsNumber = count($rows);
        //find columns quantity
        $columns = $this->helper->findElements('(//*[contains(@class, "item_artist")])[1]//*[contains(@class, "row__cell")]');
        $columnsNumber = count($columns);

        $m=1;
        for ($i=1; $i<=$rowsNumber; $i++){
            for ($k=1; $k<=$columnsNumber; $k++){
                $item = '((//*[contains(@class, "item_artist")])[' . $i . ']//*[contains(@class, "row__cell")])[' . $k . ']/*[contains(@class, "W")]';
                $itemIsHere = $I->elementIsHere($item);
                if ($itemIsHere === false){
                    $statusId[$m][$k] = '0';
                    echo ($statusId[$m][$k]);
                }
                else {
                    $atr = $I->grabAttributeFrom($item, 'class');
                    $statusId[$m][$k] = substr($atr, 10, 1);
                    echo ($statusId[$m][$k]);
                }
            }
            for ($l=1; $l<=$columnsNumber; $l++){
                echo ($statusId[$m][$l]);
                if ($statusId[$m][$l] != '0' or $statusId[$m][$l] != '8'){
                    $m++;
                    break;
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

        $rows = $this->helper->findElements('//*[contains(@class, "item_artist")]');
        $newRowsNumber = count($rows);
        $x=$m-1;
        $I->assertEquals($newRowsNumber, $x, 'error');

        for ($x=1; $x<=5; $x++){
            $a[$x] = rand(1, $columnsNumber);
        }
        for ($n=1; $n<=$newRowsNumber; $n++){
            for ($x=1; $x<=5; $x++){
                $item = '((//*[contains(@class, "item_artist")])[' . $n . ']//*[contains(@class, "row__cell")])[' . $a[$x] . ']/*[contains(@class, "W")]';
                $itemIsHere = $I->elementIsHere($item);
                if ($itemIsHere === false){
                    $newStatusId[$n][$x] = '0';
                    echo ($newStatusId[$n][$x]);
                    $I->assertEquals($newStatusId[$n][$x], $statusId[$n][$x], 'Grid is not as MASTER');
                }
                else {
                    $atr = $I->grabAttributeFrom($item, 'class');
                    $newStatusId[$n][$x] = substr($atr, 10, 1);
                    echo ($newStatusId[$n][$x]);
                    $I->assertEquals($newStatusId[$n][$x], $statusId[$n][$x], 'Grid is not as MASTER');
                }
            }
        }

        //delete scenario
        $I->waitForElementClickable(Locator::contains('button', 'File'), 20);
        $I->click(Locator::contains('button', 'File'));
        $I->click(Locator::contains('a', 'Delete'));
        $I->waitForElementClickable('//*[@id="notification"]//button[contains(text(), "Yes")]');
        $I->click('//*[@id="notification"]//button[contains(text(), "Yes")]');
        $I->loader();
    }
}
