<?php

use Codeception\Util\ActionSequence;
use Codeception\Util\Locator;
use Codeception\Util\Shared\Asserts;
use Helper\Acceptance;

class DashDLOnes00124Cest
{
	protected $helper = null;

    public function _before(DashAcceptanceTester $I, Acceptance $acceptanceHelper)
    {
        $this->helper = $acceptanceHelper;
    }
	
	//swap Show ones with Training
    public function swapshowonestotraining(DashAcceptanceTester $I)
    {
		//variables
		$sort_d='.item__info__department-sorting > span:nth-child(2)';
		$sort_up='.item__info__department-sorting > span:nth-child(1)';
		$position='((//*[contains(@class, "item_artist")])[1]//*[contains(@class, "item__weekView")])[9]';
		$position1='((//*[contains(@class, "item_artist")])[1]//*[contains(@class, "item__weekView")])[10]';
		
        $I->amOnPage('/');
		
		//CLEAR CART
		//login as department
        $I->login('department', 'department');

        //go to DLDept.Ones page
        $I->dlDeptOnesPage();
        $I->loader();

        //clear shopping cart
        $I->clearcart();

        //logout
        $I->logout();
		
		//PUBLISH ONES
        //login as show
        $I->login('show', 'show');
		
        //open Show Ones page
		$I->showOnesPage();
		$I->loader();
		
		//open Ones tab
        $I->onesTab();
		$I->loader();
		
		//select show
		$show = $I->selectShow();
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
		//add grey mark 1
		$I->waitForElementVisible(Locator::contains('button', 'Confirm'));
		$I->click(Locator::contains('button', 'Confirm'));
		
		//save changes
		$I->save();
		$I->loader();
		$I->waitForElementNotVisible('.toast-message', 20);
		
		//publish
		$I->showonespublish();
		$I->loader();
		
		//aprove pop-ap (tabby ones)
		if ($I->elementIsHere('#approvePopup .modal-content') === true){
			$I->waitForElementClickable('#approvePopup .btn-blue', 20);
			$I->click('#approvePopup .btn-blue');
			$I->loader();
			
			//aprove tabby ones
			$notificationCount = $I->grabTextFrom('//span[@class="button__counter"]');
			$notificationCount = intval($notificationCount);
			for ($k=1; $k<=100; $k++){
				$I->wait(1);
				$notificationCountNew = $I->grabTextFrom('//*[@class="button__counter"]');
				$notificationCountNew = intval($notificationCountNew);
				if ($notificationCountNew > $notificationCount){
					$difference = $notificationCountNew - $notificationCount;
					break;
				}
			}
			//publish request
			$I->click('//*[@class="button__content"]');
			for ($a = 1; $a <= $difference; $a++) {
				$I->waitForElementVisible('//*[@id="NotificationsEl"]//*[@class="popup"]');
				$I->click('(//button//*[contains(text(), "Accept")])[1]');
				$I->loader();
			}
			$I->waitForElementClickable('//*[@id="NotificationsEl"]//*[@class="popup__close"]');
			$I->click('//*[@id="NotificationsEl"]//*[@class="popup__close"]');
		}	
		$I->loader();
		
		$I->logout();

		
		
		//variables
		$start_date = '(//*[@id="vueAddPositionsPopup"]//input[contains(@type,"text")])[4]';
		$end_date = '(//*[@id="vueAddPositionsPopup"]//input[contains(@type,"text")])[5]';
		$salary = '(//*[@id="vueAddPositionsPopup"]//input[contains(@type,"text")])[3]';
		$seniority = '(//*[@id="vueAddPositionsPopup"]//*[contains(@type,"button")])[3]';
		$pos_type = '(//*[@id="vueAddPositionsPopup"]//*[contains(@type,"button")])[2]';
		$add = '//*[@id="vueAddPositionsPopup"]//button[contains(text(),"Add")]';
		$popup = '.toast-message';
		$position='((//*[contains(@class, "item_artist")])[1]//*[contains(@class, "item__weekView")])[1]';
		$confirm = '//*[@id="BookPopup"]//button[contains(text(), "Confirm")]';
		
		//SWAP SHOW ONES TO TRAINING
        //login as department
        $I->login('department', 'department');
		
        //go to DLDept.Ones page
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
		
		//add Ones to empty cell
		for ($i=1; $i<=$rowsNumber; $i++){
			$position_dep = '((//*[contains(@class, "item_artist")])[' . $i .']//*[contains(@class, "item__weekView")])[9]';
			$cell_text = $I->grabTextFrom($position_dep);
			
			$position_dep1 = '((//*[contains(@class, "item_artist")])[' . $i .']//*[contains(@class, "item__weekView")])[10]';
			$cell_text1 = $I->grabTextFrom($position_dep1);

			//add SHOW ones
			$str_cont_1 = strpos($cell_text, '1');
			$str_empty = $I->elementIsHere('((//*[contains(@class, "item_artist")])[' . $i .']//*[contains(@class, "item__weekView")])[9]//*[contains(@class, "empty")]');
			
			$str_cont_11 = strpos($cell_text1, '1');
			$str_empty1 = $I->elementIsHere('((//*[contains(@class, "item_artist")])[' . $i .']//*[contains(@class, "item__weekView")])[10]//*[contains(@class, "empty")]');
			
			if (($str_cont_1 !== false or $str_empty === true) and ($str_cont_11 !== false or $str_empty1 === true)){
				//open cart
				$I->waitForElementClickable('//button[@id="ShowPopupButton"]', 20);
				$I->click('//button[@id="ShowPopupButton"]');
				//count of shows
				$show_cart = $this->helper->findElements('//*[@class="show__info"]'); 
				$show_cart_number = count($show_cart);
				echo('Count of shows in cart: ' . $show_cart_number . "\n");
				//select nessesary show 
				for ($j=1; $j<=$show_cart_number; $j++){
					$item_show = '(//*[@class="show__info"])[' . $j . ']//*[contains(text(),"' . $show . '")]';
					$item_show_here = $I->elementIsHere($item_show);
					if ($item_show_here !== false){
						//expand show
						$expand_show ='(//*[contains(@class,"VPopupDraggable")]//*[contains(@class, "fa-plus-square")])[' . $j . ']';
						$I->waitForElementVisible($expand_show, 20);
						$I->click($expand_show);
						$I->waitForElementClickable($expand_show, 20);
						$I->click($expand_show);
						//sort by Lead
						$I->waitForElementClickable('//*[@class="one"]//*[contains(text(),"Lead")]', 20);
						$I->click('//*[@class="one"]//*[contains(text(),"Lead")]');
						$I->loader();
						
						//add show ones
						$req_show = '(//*[contains(@class, "header__shows__items")]/div/div[2]//*[@class="row__cell"])[9]';
						$I->waitForElementClickable($req_show, 20);
						$I->click($req_show);
						//scroll to cell
						$elements = $this->helper->findElements($position_dep);
						$I->executeJS("arguments[0].scrollIntoView(true);", $elements);
					
						$I->waitForElementVisible($position_dep, 20);
						$I->click($position_dep);
						$I->click('//*[contains(@class,"modal-wrapper")]//button[contains(text(),"Close")]');
						$I->waitForElementVisible('//*[contains(@class,"header__shows__close")]', 20);
						$I->click('//*[contains(@class,"header__shows__close")]');
						$I->loader();
						
						//add training ones
						$I->waitForElementClickable($position_dep1, 20);
						$I->click($position_dep1);
						$I->waitForElementVisible('//span[text()="Book Training (T)"]', 20);
						$I->click('//span[text()="Book Training (T)"]');
						$I->waitForElementVisible($confirm, 20);
						$I->click($confirm);
						
						//scroll to File
						$file = '(//*[contains(text(),"File")])[2]';
						$elements = $this->helper->findElements($file);
						$I->executeJS("arguments[0].scrollIntoView(false);", $elements);
						
						//save
						$I->save();
						$I->loader();
						break;
					}
				}
				break;
			}
		}
		
		//check Ones text
		$cell_text = $I->grabTextFrom($position_dep);
		$cell_text1 = $I->grabTextFrom($position_dep1);
		echo('Ones 1 text: ' . $cell_text . "\n");
		echo('Ones 2 text: ' . $cell_text1 . "\n");
		
		//use only before fix comma
		$comma = strpos($cell_text, ',');		
		$comma1 = strpos($cell_text1, ',');

		if ($comma !== false){
			$comma = $comma+1;
			$after_comma = strlen(substr($cell_text, $comma)); 
			echo('Count after comma: ' . $after_comma . "\n");
			$cell_text = str_ireplace(',', '.', $cell_text);
			if ($after_comma < 2){
				$cell_text = $cell_text . '0';
			}
		}
		if ($comma1 !== false){
			$comma1 = $comma1+1;
			$after_comma1 = strlen(substr($cell_text1, $comma1)); 
			echo('Count after comma1: ' . $after_comma1 . "\n");
			$cell_text1 = str_ireplace(',', '.', $cell_text1);
			if ($after_comma1 < 2){
				$cell_text1 = $cell_text1 . '0';
			}
		}
		echo('Ones 1 text: ' . $cell_text . "\n");
		echo('Ones 2 text: ' . $cell_text1 . "\n");
		
		
		//scroll to cell
		$elements = $this->helper->findElements($position_dep);
		$I->executeJS("arguments[0].scrollIntoView(true);", $elements);
		
		//swap Ones
		$I->waitForElementClickable($position_dep, 20);
		$I->click($position_dep);
		$I->waitForElementVisible('//*[contains(text(),"Swap Ones")]',20);
		$I->click('//*[contains(text(),"Swap Ones")]');
		$I->waitForElementClickable($position_dep1, 20);
		$I->click($position_dep1);
		$I->waitForElementClickable('//*[@id="BookPopup"]//button[contains(text(),"Confirm")]', 20);
		$I->click('//*[@id="BookPopup"]//button[contains(text(),"Confirm")]');
		
		//check new Ones text
		$cell_text_new = $I->grabTextFrom($position_dep);
		$cell_text1_new = $I->grabTextFrom($position_dep1);
		echo('new Ones 1 text: ' . $cell_text_new . "\n");
		echo('new Ones 2 text: ' . $cell_text1_new . "\n");
		
		//assert ones swapped
		$I->assertEquals($cell_text, $cell_text1_new, 'Ones 1 is not swapped');
		$I->assertEquals($cell_text1, $cell_text_new, 'Ones 2 is not swapped');
		
		//delete added ones
		$I->click($position_dep);
		$I->waitForElementClickable('//*[@id="BookPopup"]//*[contains(text(), "Remove Ones")]', 20);
		$I->click('//*[@id="BookPopup"]//*[contains(text(), "Remove Ones")]');
		$I->waitForElementVisible($confirm, 20);
		$I->click($confirm);
		$I->waitForElementVisible($position_dep1, 20);
		$I->click($position_dep1);
		$I->waitForElementClickable('//*[@id="BookPopup"]//*[contains(text(), "Remove Ones")]', 20);
		$I->click('//*[@id="BookPopup"]//*[contains(text(), "Remove Ones")]');
		$I->waitForElementVisible($confirm, 20);
		$I->click($confirm);
		
		//scroll to File
		$file = '(//*[contains(text(),"File")])[2]';
		$elements = $this->helper->findElements($file);
		$I->executeJS("arguments[0].scrollIntoView(false);", $elements);
		
		//save changes
		$I->save();
		$I->loader();
		$I->waitForElementNotVisible('.toast-message', 20);
	}
}


