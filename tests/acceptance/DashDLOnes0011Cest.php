<?php

use Codeception\Util\ActionSequence;
use Codeception\Util\Locator;
use Codeception\Util\Shared\Asserts;
use Helper\Acceptance;

class DashDLOnes0011Cest
{
	protected $helper = null;

    public function _before(DashAcceptanceTester $I, Acceptance $acceptanceHelper)
    {
        $this->helper = $acceptanceHelper;
    }
	
	//remove Training ones
    public function removeones(DashAcceptanceTester $I)
    {
		//variables
		$sort_d='.item__info__department-sorting > span:nth-child(2)';
		$sort_up='.item__info__department-sorting > span:nth-child(1)';
		$position='((//*[contains(@class, "item_artist")])[1]//*[contains(@class, "item__weekView")])[9]';
		
        $I->amOnPage('/');
		
		//CLEAR CART
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
		
		//aprove pop-ap (tabby ones)
		if ($I->elementIsHere('#approvePopup .modal-content') === true){
			$I->waitForElementClickable('#approvePopup .btn-blue', 20);
			$I->click('#approvePopup .btn-blue');
			$I->loader();
			
			//aprove tabby ones
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
		
		$I->logout();

		
		//ADD/REMOVE SHOW ONES
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


        //login as show
        $I->login('department', 'department');
		
        //go to DLDept.Ones page
        $I->dlDeptOnesPage();
        $I->loader();		
		
		//count of all positions ($rowsNumber)
		$rows = $this->helper->findElements('//*[contains(@class,"item__info__name")]');
        $rowsNumber = count($rows);
		echo('Count of positions (all): ' . $rowsNumber . "\n");
		
		for ($i=1; $i<=$rowsNumber; $i++){
			$position_dep = '((//*[contains(@class, "item_artist")])[' . $i .']//*[contains(@class, "item__weekView")])[9]';
			$cell_text = $I->grabTextFrom($position_dep);
			//add SHOW ones
			$str_cont_1 = strpos($cell_text, '1');
			$str_empty = $I->elementIsHere('((//*[contains(@class, "item_artist")])[' . $i .']//*[contains(@class, "item__weekView")])[9]//*[contains(@class, "empty")]');
			if ($str_cont_1 !== false or $str_empty === true){
				//open cart
				$I->waitForElementClickable('//button[@id="ShowPopupButton"]', 20);
				$I->click('//button[@id="ShowPopupButton"]');
				//count of shows
				$show_cart = $this->helper->findElements('//*[@class="show__info"]'); 
				$show_cart_number = count($show_cart);
				echo('Count of shows in cart: ' . $show_cart_number . "\n");
				//select nessesary show  ' . $show . '
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
						
						//select ones
						$I->waitForElementClickable('(//*[contains(@class, "header__shows__items")]/div/div[2]//*[@class="row__cell"])[9]', 20);
						$I->click('(//*[contains(@class, "header__shows__items")]/div/div[2]//*[@class="row__cell"])[9]');
						
						//scroll to cell
						$elements = $this->helper->findElements($position_dep);
						$I->executeJS("arguments[0].scrollIntoView(true);", $elements);
						
						$I->waitForElementClickable($position_dep, 20);
						$I->click($position_dep);
						
						//scroll to File
						$file = '(//*[contains(text(),"File")])[2]';
						$elements = $this->helper->findElements($file);
						$I->executeJS("arguments[0].scrollIntoView(false);", $elements);
						
						$I->waitForElementClickable(Locator::contains('button', 'File'), 20);
						$I->click(Locator::contains('button', 'File'));
						$I->waitForElementVisible(Locator::contains('a', 'Save'), 20);
						$I->click(Locator::contains('a', 'Save'));
						$I->loader();
						break;
					}
				}
				break;
			}
		}
		
		//check the cart
		$I->waitForElementClickable('//button[@id="ShowPopupButton"]', 20);
		$I->click('//button[@id="ShowPopupButton"]');
		$I->waitForElementVisible('(//*[contains(@class, "card__count")])[1]', 20);
		$cart_count = $I->grabTextFrom('(//*[contains(@class, "card__count")])[1]');
		$cart_count = intval($cart_count);
		echo('Count in the cart: ' . $cart_count . "\n");
		$I->waitForElementClickable('//*[contains(@class, "modal-mask")]//button[contains(text(), "Close")]', 20);
		$I->click('//*[contains(@class, "modal-mask")]//button[contains(text(), "Close")]');
		
		//scroll to cell
		$elements = $this->helper->findElements($position_dep);
		$I->executeJS("arguments[0].scrollIntoView(true);", $elements);
		
		//remove ones
		$I->click($position_dep);
		$I->waitForElementClickable('//*[@id="BookPopup"]//*[contains(text(), "Remove Ones")]', 20);
		$I->click('//*[@id="BookPopup"]//*[contains(text(), "Remove Ones")]');
		$I->waitForElementVisible($confirm, 20);
		$I->click($confirm);
		
		//assert cell is emty
		$cell_text = $I->grabTextFrom($position_dep);
		$str_cont_1 = strpos($cell_text, '1');
		$str_empty = $I->elementIsHere('((//*[contains(@class, "item_artist")])[' . $i .']//*[contains(@class, "item__weekView")])[9]//*[contains(@class, "empty")]');
		if ($str_cont_1 !== false or $str_empty === true){
			$cell_text = 1;
		}
		else{
			$cell_text = 0;
		}
		$I->assertEquals(1, $cell_text, 'Cell is not empty');
		
		//check the chart after remove Ones
		$I->waitForElementClickable('//button[@id="ShowPopupButton"]', 20);
		$I->click('//button[@id="ShowPopupButton"]');
		$I->wait(5);//because cart count refresh after some time, no loader
		$I->waitForElementVisible('(//*[contains(@class, "card__count")])[1]', 20);
		$cart_count1 = $I->grabTextFrom('(//*[contains(@class, "card__count")])[1]');
		$cart_count1 = intval($cart_count1);
		echo('Count in the chart after delete added ones: ' . $cart_count1 . "\n");
		$I->waitForElementClickable('//*[contains(@class, "modal-mask")]//button[contains(text(), "Close")]', 20);		
		$I->click('//*[contains(@class, "modal-mask")]//button[contains(text(), "Close")]');
		
		//assert count Ones in chart
		$I->assertGreaterThan($cart_count1, $cart_count, 'Count of ones in cart is the same.');
		
		//scroll to File
		$elements = $this->helper->findElements($file);
		$I->executeJS("arguments[0].scrollIntoView(false);", $elements);
		
		//save changes
		$I->waitForElementClickable(Locator::contains('button', 'File'), 20);
		$I->click(Locator::contains('button', 'File'));
		$I->waitForElementVisible(Locator::contains('a', 'Save'), 20);
		$I->click(Locator::contains('a', 'Save'));
		$I->loader();
	}
}


