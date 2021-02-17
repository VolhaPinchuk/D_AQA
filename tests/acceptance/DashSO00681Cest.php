<?php

use Codeception\Util\ActionSequence;
use Codeception\Util\Locator;
use Codeception\Util\Shared\Asserts;
use Helper\Acceptance;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;

class DashSO00681Cest extends BaseActions
{
	protected $helper = null;
	
	public function _before(DashAcceptanceTester $I, Acceptance $acceptanceHelper)
    {
        $this->helper = $acceptanceHelper;
    }
	
    //Publish page after add Ones
    public function publishpageafteraddones(DashAcceptanceTester $I)
    {
		//variables
		$sort_d='.item__info__department-sorting > span:nth-child(2)';
		$sort_up='.item__info__department-sorting > span:nth-child(1)';
		$position_locator='((//*[contains(@class, "item_artist")])[1]//*[contains(@class, "row__cell")])[4]';
		
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

		//select calendar period
        $I->selectcalendarperiod();
		$I->waitForElementClickable(Locator::contains('button', 'Confirm'), 20);
		$I->click(Locator::contains('button', 'Confirm'));
        $I->click(Locator::contains('button', 'Apply'));
        $I->loader();

        //open Add position popup
        $I->addpositionpopup();

        //add position Lead
        $I->addoneposition(1, 1);
        $I->confirmaddposition();
		//sorting
		$I->waitForElementClickable($sort_up, 20);
		$I->doubleclick($sort_up);
		$I->waitForElementClickable($sort_d, 20);
		$I->click($sort_d);
		
		//notification
		$I->waitForElementVisible($position_locator);
		$I->click($position_locator);
		$I->wait(3);
		if ($I->elementIsHere('//*[contains(@class, "VNotification")]') === true){
			$I->click(Locator::contains('button', 'OK'));
			$I->waitForElementVisible($position_locator);
			$I->click($position_locator);
		}
		
		//add grey mark (ones)
		$I->waitForElementVisible(Locator::contains('button', 'Confirm'));
		$I->click(Locator::contains('button', 'Confirm'));
		//save changes
		$I->save();
		$I->waitForElementNotVisible('.toast-message', 20);
		
		//find number of the end position in the first department
        list ($i, $position) = $I->numberofendposition();
		$i=$i-2;
		echo('count in column: ' . $i . "\n");
				
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
		$I->showonespublish();
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


