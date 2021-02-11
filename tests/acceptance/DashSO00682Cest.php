<?php

use Codeception\Util\ActionSequence;
use Codeception\Util\Locator;
use Codeception\Util\Shared\Asserts;
use Helper\Acceptance;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;

class DashSO00682Cest extends BaseActions
{
	protected $helper = null;
	
	public function _before(DashAcceptanceTester $I, Acceptance $acceptanceHelper)
    {
        $this->helper = $acceptanceHelper;
    }
	
    //add position lead
    public function addpositionlead(DashAcceptanceTester $I)
    {
		//variables
		$sort_d='.item__info__department-sorting > span:nth-child(2)';
		$sort_up='.item__info__department-sorting > span:nth-child(1)';
		$position='((//*[contains(@class, "item_artist")])[1]//*[contains(@class, "row__cell")])[4]';
		
        $I->amOnPage('/');

        //login as show
        $I->login('show', 'show');
		
        //open Show Ones page
		$I->showOnesPage();
		$I->loader();
		
		//open Ones tab
        $I->onesTab();
		$I->loader();
		
		//select show
		$I->selectShow();
		$I->loader();

		//select calendar period
        $I->waitForElementVisible('.form-control.reportrange-text', 20);
        $I->click('.form-control.reportrange-text');
        $I->waitForElementVisible('.calendars-container', 20);		
		$I->click('//*[contains(@class, "left")]//*[contains(@class, "next")]');
		$I->click('//*[contains(@class, "left")]//*[contains(@class, "next")]');
		$I->click('//*[contains(@class, "left")]//tr[3]/td[1]');
		$I->click('//*[contains(@class, "right")]//tr[6]/td[7]');
		$I->waitForElementClickable(Locator::contains('button', 'Confirm'), 20);
		$I->click(Locator::contains('button', 'Confirm'));
        $I->click(Locator::contains('button', 'Apply'));
        $I->loader();
		
		//add position lead
		$I->waitForElementClickable('.item__info__department-add-icon', 20); 
		$I->click('.item__info__department-add-icon');
		//lead choise
		$I->waitForElementVisible(Locator::contains('label', 'Lead'));
		$I->click(Locator::contains('label', 'Lead'));
		//quantity of leads
		$I->waitForElementVisible('(//input[contains(@type,"text")])[1]', '1');
		$I->fillField('(//input[contains(@type,"text")])[1]', '1');
		//click on add button
		$I->waitForElementClickable(Locator::contains('button', 'Add'));
		$I->click(Locator::contains('button', 'Add'));
		//sorting
		$I->waitForElementClickable($sort_up, 20);
		$I->doubleclick($sort_up);
		$I->waitForElementClickable($sort_d, 20);
		$I->click($sort_d);
		
		//notification
		$I->waitForElementVisible($position);
		$I->click($position);
		$I->wait(3);
		if ($I->elementIsHere('//*[contains(@class, "VNotification")]') === true){
			$I->click(Locator::contains('button', 'OK'));
			$I->waitForElementVisible($position);
			$I->click($position);
		}
		
		//add grey mark (ones)
		$I->waitForElementVisible(Locator::contains('button', 'Confirm'));
		$I->click(Locator::contains('button', 'Confirm'));
		//save changes
		$I->waitForElementVisible(Locator::contains('button', 'File'));
		$I->click(Locator::contains('button', 'File'));
		$I->waitForElementVisible(Locator::contains('a', 'Save'));
		$I->click(Locator::contains('a', 'Save'));
		$I->loader();
		$I->waitForElementNotVisible('.toast-message', 20);
		
		//publish
		$I->waitForElementVisible(Locator::contains('button', 'File'), 20);
		$I->click(Locator::contains('button', 'File'));
		$I->waitForElementVisible(Locator::contains('a', 'Publish'), 20);
		$I->click(Locator::contains('a', 'Publish'));
		$I->waitForElementVisible('(//label[contains(text(),"Select all")])[1]', 20);
		$I->click('(//label[contains(text(),"Select all")])[1]');
		$I->click('(//label[contains(text(),"Select all")])[2]');
		$I->click(Locator::contains('button', 'Send'));
		$I->loader();
		$I->loader();
		
		//aprove pop-ap
		if ($I->elementIsHere('#approvePopup .modal-content') === true){
			$I->waitForElementClickable('#approvePopup .btn-blue', 20);
			$I->click('#approvePopup .btn-blue');
			$I->loader();
		}	
		
		//expand 
		$I->waitForElementClickable('.item__info__expand-icon > span:nth-child(1)', 20);
		$I->click('.item__info__expand-icon > span:nth-child(1)');
		
		//find number of the end position in the first department
        $i=2;
        $end_pos='//*[@id="app"]//div[2]/div[2]/div[2]/div/div[2]/div[2]/div/div[' . $i . ']';
        $atr=$I->grabAttributeFrom($end_pos, 'class');
        while (strpos($atr, 'item_artist') !== false):
            $i++;
            $end_pos='//*[@id="app"]//div[2]/div[2]/div[2]/div/div[2]/div[2]/div/div[' . $i . ']';
            $atr=$I->grabAttributeFrom($end_pos, 'class');
        endwhile;
		$i=$i-2;
		echo('count in column: ' . $i . "\n");
		
		//add OT
        for ($a = 1; $a <= $i; $a++){
            $item = '((//*[contains(@class, "item_artist")])[' . $a . ']//*[contains(@class, "row__cell")])[4]/*[contains(@class, "W")]';
			$item1 = '((//*[contains(@class, "item_artist")])[' . $a . ']//*[contains(@class, "row__cell")])[4]/*[contains(@class, "W")]/*[contains(@class, "cell-inside-value")]';
			$itemIsHere = $I->elementIsHere($item);
			$itemIsHere1 = $I->elementIsHere($item1);
            if ($itemIsHere !== false and $itemIsHere1 === false) {
                $I->click('((//*[contains(@class, "item_artist")])[' . $a . ']//*[contains(@class, "row__cell")])[4]');
                $a = $i+1;
				$I->waitForElementClickable('(//input[contains(@id,"VInput")])[2]');
				$I->fillField('(//input[contains(@id,"VInput")])[2]', '1.00');
                $I->click(Locator::contains('button', 'Confirm'));
                $I->waitForElementNotVisible('.toast-message');
                $I->click(Locator::contains('button', 'File'));
                $I->click(Locator::contains('span', 'Save'));
                $I->loader();
				$I->waitForElementNotVisible('.toast-message', 20);
            }
        }	
				
		//quantity of cells in string
		$elementsPosition = $this->helper->findElements('(//*[contains(@class, "item_artist")])[1]//*[contains(@class, "row__cell")]');
        $elementsPositionNumber=count($elementsPosition);
		echo('count in line: ' . $elementsPositionNumber . "\n");
		
		//check Ones color
		$color_it = $gray_it = $orange_it = $green_it = $red_it = $tabby_it = 0;
        for ($a = 1; $a <= $elementsPositionNumber; $a++){
			for ($b = 1; $b <= $i; $b++){
				$item_gray = '((//*[contains(@class, "item_artist")])[' . $b . ']//*[contains(@class, "row__cell")])[' . $a . ']/*[contains(@class, "statusId1")]';
				$itemIsHere_gray = $I->elementIsHere($item_gray);
				if ($itemIsHere_gray === true) {
					$gray_it = $gray_it + 1;
				}
				
				$item_orange = '((//*[contains(@class, "item_artist")])[' . $b . ']//*[contains(@class, "row__cell")])[' . $a . ']/*[contains(@class, "statusId2")]';
				$itemIsHere_orange = $I->elementIsHere($item_orange);
				if ($itemIsHere_orange === true) {
					$orange_it = $orange_it + 1;
				}
				
				$item_green = '((//*[contains(@class, "item_artist")])[' . $b . ']//*[contains(@class, "row__cell")])[' . $a . ']/*[contains(@class, "statusId3")]';
				$itemIsHere_green = $I->elementIsHere($item_green);
				if ($itemIsHere_green === true) {
					$green_it = $green_it + 1;
				}
				
				$item_red = '((//*[contains(@class, "item_artist")])[' . $b . ']//*[contains(@class, "row__cell")])[' . $a . ']/*[contains(@class, "statusId4")]';
				$itemIsHere_red = $I->elementIsHere($item_red);
				if ($itemIsHere_red === true) {
					$red_it = $red_it + 1;
				}
				
				$item_tabby = '((//*[contains(@class, "item_artist")])[' . $b . ']//*[contains(@class, "row__cell")])[' . $a . ']/*[contains(@class, "statusId8")]';
				$itemIsHere_tabby = $I->elementIsHere($item_tabby);
				if ($itemIsHere_tabby === true) {
					$tabby_it = $tabby_it + 1;
				}
			}
        }
		$color_it = $gray_it + $orange_it + $green_it + $red_it + $tabby_it;
		echo('Color(all): ' . $color_it . "\n");
		echo('Gray: ' . $gray_it . "\n");
		echo('Orange: ' . $orange_it . "\n");
		echo('Green: ' . $green_it . "\n");
		echo('Red: ' . $red_it . "\n");
		echo('Tabby: ' . $tabby_it . "\n");
		
		//publish
		$I->waitForElementVisible(Locator::contains('button', 'File'), 20);
		$I->click(Locator::contains('button', 'File'));
		$I->waitForElementVisible(Locator::contains('a', 'Publish'), 20);
		$I->click(Locator::contains('a', 'Publish'));
		$I->waitForElementVisible('(//label[contains(text(),"Select all")])[1]', 20);
		$I->click('(//label[contains(text(),"Select all")])[1]');
		$I->click('(//label[contains(text(),"Select all")])[2]');
		$I->click(Locator::contains('button', 'Send'));
		$I->loader();
		$I->loader();
		
		//aprove pop-ap
		if ($I->elementIsHere('#approvePopup .modal-content') === true){
			$I->waitForElementClickable('#approvePopup .btn-blue', 20);
			$I->click('#approvePopup .btn-blue');
			$I->loader();
		}		
		
		//check Ones color after publish
		$I->waitForElementClickable($position, 20);
		$color_it_1 = $gray_it_1 = $orange_it_1 = $green_it_1 = $red_it_1 = $tabby_it_1 = 0;
		
        for ($a = 1; $a <= $elementsPositionNumber; $a++){
			for ($b = 1; $b <= $i; $b++){
				$item_gray_1 = '((//*[contains(@class, "item_artist")])[' . $b . ']//*[contains(@class, "row__cell")])[' . $a . ']/*[contains(@class, "statusId1")]';
				$itemIsHere_gray_1 = $I->elementIsHere($item_gray_1);
				if ($itemIsHere_gray_1 === true) {
					$gray_it_1 = $gray_it_1 + 1;
				}
				
				$item_orange_1 = '((//*[contains(@class, "item_artist")])[' . $b . ']//*[contains(@class, "row__cell")])[' . $a . ']/*[contains(@class, "statusId2")]';
				$itemIsHere_orange_1 = $I->elementIsHere($item_orange_1);
				if ($itemIsHere_orange_1 === true) {
					$orange_it_1 = $orange_it_1 + 1;
				}
				
				$item_green_1 = '((//*[contains(@class, "item_artist")])[' . $b . ']//*[contains(@class, "row__cell")])[' . $a . ']/*[contains(@class, "statusId3")]';
				$itemIsHere_green_1 = $I->elementIsHere($item_green_1);
				if ($itemIsHere_green_1 === true) {
					$green_it_1 = $green_it_1 + 1;
				}
				
				$item_red_1 = '((//*[contains(@class, "item_artist")])[' . $b . ']//*[contains(@class, "row__cell")])[' . $a . ']/*[contains(@class, "statusId4")]';
				$itemIsHere_red_1 = $I->elementIsHere($item_red_1);
				if ($itemIsHere_red_1 === true) {
					$red_it_1 = $red_it_1 + 1;
				}
				
				$item_tabby_1 = '((//*[contains(@class, "item_artist")])[' . $b . ']//*[contains(@class, "row__cell")])[' . $a . ']/*[contains(@class, "statusId8")]';
				$itemIsHere_tabby_1 = $I->elementIsHere($item_tabby_1);
				if ($itemIsHere_tabby_1 === true) {
					$tabby_it_1 = $tabby_it_1 + 1;
				}
			}
        }
		$color_it_1 = $gray_it_1 + $orange_it_1 + $green_it_1 + $red_it_1 + $tabby_it_1;
		echo("	Quantity: 	End quantity: \n");
		echo('Color (all): 	' . $color_it . '	'. $color_it_1 ."\n");
		echo('Gray: 		' . $gray_it . '	'. $gray_it_1 ."\n");
		echo('Orange: 	' . $orange_it . '	'. $orange_it_1 ."\n");
		echo('Green: 		' . $green_it . '	'. $green_it_1 ."\n");
		echo('Red: 		' . $red_it . '	'. $red_it_1 ."\n");
		echo('Tabby: 		' . $tabby_it . '	'. $tabby_it_1 ."\n");	
		
		//assert check items
		$I->assertEquals($color_it_1, $color_it, 'Different count of color Ones');
		$I->assertEquals($gray_it_1, 0, 'There are gray Ones');
		$I->assertGreaterOrEquals($orange_it_1, $orange_it, 'There are less orange Ones');
		$I->assertGreaterOrEquals($tabby_it_1, $tabby_it, 'There are less tabby Ones');
		$I->wait(2);
    }
}


