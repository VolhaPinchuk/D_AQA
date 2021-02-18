<?php

use Codeception\Util\ActionSequence;
use Codeception\Util\Locator;
use Codeception\Util\Shared\Asserts;
use Helper\Acceptance;

class DashDLOnes00072Cest
{
	protected $helper = null;

    public function _before(DashAcceptanceTester $I, Acceptance $acceptanceHelper)
    {
        $this->helper = $acceptanceHelper;
    }
	
	//change type from NH
    public function changetypenh(DashAcceptanceTester $I)
    {
		//variables
		$popup = '.toast-message';
		$NH = '//*[contains(@class,"VContextMenu__item")][contains(text(),"NH")]';

        $I->amOnPage('/');

        //login as show
        $I->login('department', 'department');
		
        //open DL Dept. Ones page
        $I->dlDeptOnesPage();
		$I->loader();
		
		//Add position CH
		$I->addpositionindept();
		$I->loader();
		$I->wait(3);
		
		//count of all positions ($rowsNumber)
		$rows = $this->helper->findElements('//*[contains(@class,"item__info__name")]');
        $rowsNumber = count($rows);
		echo('Count of positions (all): ' . $rowsNumber . "\n");
		
		//count elements without arrows ($no_arrowNumber)
		$no_arrow = $this->helper->findElements('//*[@class="icon__level seniorityLevel_"]');
		$no_arrowNumber = count($no_arrow);
		echo('Count of elements without arrows: ' . $no_arrowNumber . "\n");
		
		//last arrow
		$last_arrow = $rowsNumber - $no_arrowNumber;
		echo('Last arrow: ' . $last_arrow . "\n");
		
		//first element without arrow
		$non_arr1 = $last_arrow+1;
		echo('First element without arrow: ' . $non_arr1 . "\n");
		
		//set NH type
		$down_arr = '(//*[contains(@class,"item__info__contextButton")])['. $non_arr1 .']';
		$I->waitForElementVisible($down_arr, 20);
		$I->click($down_arr);
		$I->waitForElementClickable($NH, 20);
		$I->click($NH);
		$I->wait(1);
		//confirmation time period
		$elIsHere = $I->elementIsHere('//*[@id="PlanningGridEditTypePopup"]');
		if ($elIsHere === true){
			$I->waitForElementVisible('//*[@id="PlanningGridEditTypePopup"]//button[contains(text(),"Save")]', 20);
			$I->click('//*[@id="PlanningGridEditTypePopup"]//button[contains(text(),"Save")]');
		}
		$I->loader();
		
		//count of elements in dropdown
		$I->waitForElementClickable($down_arr, 20);
		$I->click($down_arr);
		$I->waitForElementClickable('(//*[contains(@class,"VContextMenu__item")])[1]', 20);
		$cont_it = $this->helper->findElements('//*[contains(@class,"VContextMenu__item")]');
        $itNumber = count($cont_it);
		$itNumber = $itNumber - 1;
		echo('Count of positions in dropdown: ' . $itNumber . "\n");
		
		//CHANGE TYPE AND RETURN TO NH
		for($i = 1; $i <= $itNumber; $i++){
			
			//open dropdown of NH 
			$itemIsHere = $I->elementIsHere('//*[contains(@class,"VContextMenu--active")]');
			if ($itemIsHere === false){
				$I->waitForElementClickable($down_arr, 20);
				$I->click($down_arr);
			}
		
			//change type   VContextMenu__item
			$menu_type = '(//*[contains(@class,"VContextMenu__item")])[' . $i . ']';
			$I->waitForElementClickable($menu_type, 20);
			$name_menu_type = $I->grabTextFrom($menu_type);
			echo('Text from element of dropdown: ' . $name_menu_type . "\n");
			$I->click($menu_type);
			$I->wait(1);
			
			//confirmation time period
			$elIsHere = $I->elementIsHere('//*[@id="PlanningGridEditTypePopup"]');
			if ($elIsHere === true){
				$I->waitForElementVisible('//*[@id="PlanningGridEditTypePopup"]//button[contains(text(),"Save")]', 20);
				$I->click('//*[@id="PlanningGridEditTypePopup"]//button[contains(text(),"Save")]');
			}
			$I->loader();
			
			//assert check set type
			$pos = '(//*[contains(@class,"item__info__name")])['. $non_arr1 .']';
			$I->waitForElementVisible($pos, 20);
			$type = $I->grabTextFrom($pos);
			echo('Text from element: ' . $type . "\n");
			$I->assertContains($name_menu_type, $type, 'Type is different from chosen');
			
			//warning
			$I->wait(1);
			$warningIsHere = $I->elementIsHere('.toast-message');
				if ($warningIsHere === true){
					$I->waitForElementNotVisible('.toast-message', 20);
				}
						
			//assert check save
			$I->save();
			$I->waitForElementVisible($popup, 20);
			$message = $I->grabTextFrom($popup);
			echo('Text from popup: ' . $message . "\n");
			$I->assertContains('No changes made.', $message, 'Another meaasge or some changes');
			
			//RETURN TO NH
			$I->waitForElementClickable($down_arr, 20);
			$I->click($down_arr);
			$I->waitForElementClickable($NH, 20);
			$I->click($NH);
			
			//confirmation time period
			$elIsHere = $I->elementIsHere('//*[@id="PlanningGridEditTypePopup"]');
			if ($elIsHere === true){
				$I->waitForElementVisible('//*[@id="PlanningGridEditTypePopup"]//button[contains(text(),"Save")]', 20);
				$I->click('//*[@id="PlanningGridEditTypePopup"]//button[contains(text(),"Save")]');
			}
			$I->loader();
		}
		
		//to CH
		$I->waitForElementClickable($down_arr, 20);
		$I->click($down_arr);
		$I->waitForElementClickable('//*[contains(@class,"VContextMenu__item")][contains(text(),"CH")]', 20);
		$I->click('//*[contains(@class,"VContextMenu__item")][contains(text(),"CH")]');
		$I->loader();
	}
}


