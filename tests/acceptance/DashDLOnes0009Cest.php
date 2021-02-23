<?php

use Codeception\Util\ActionSequence;
use Codeception\Util\Locator;
use Codeception\Util\Shared\Asserts;
use Helper\Acceptance;

class DashDLOnes0009Cest
{
	protected $helper = null;

    public function _before(DashAcceptanceTester $I, Acceptance $acceptanceHelper)
    {
        $this->helper = $acceptanceHelper;
    }
	
	//check elements in cart
    public function checkcartelements(DashAcceptanceTester $I)
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
		$level = 1;
		$I->addpositionpopup();
		$I->addoneposition($level, $level);//a - level (lead), b - input for amount of positions(also 1 pos)
		
		//grab discipline
		$discipline = $I->grabTextFrom('//*[contains(@class,"title__discipline")]');
		//grab level
		$art_level = $I->grabTextFrom('(//*[@id="AddPositionPopup"]//*[contains(@class,"ui-checkbox")])[' . $level . ']');
		
		$I->confirmaddposition();
		
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
		
		//CHECK ELEMENTS IN CART
        //login as department
        $I->login('department', 'department');
		
        //go to DLDept.Ones page
        $I->dlDeptOnesPage();
        $I->loader();	
		
		//open cart
		$I->waitForElementClickable('//button[@id="ShowPopupButton"]', 20);
		$I->click('//button[@id="ShowPopupButton"]');
		
		//count of shows
		$show_cart = $this->helper->findElements('//*[@class="show__info"]'); 
		$show_cart_number = count($show_cart);
		echo('Count of shows in cart: ' . $show_cart_number . "\n"); 
		//assert there is only one show
		$I->assertEquals($show_cart_number, 1, 'There are more than 1 show');
		//assert Show name 
		$text_show_cart = $I->grabTextFrom('//*[contains(@class,"show__title")]');
		echo('Text from show : ' . $show . "\n"); 
		echo('Text from dept show: ' . $text_show_cart . "\n"); 
		$I->assertContains($text_show_cart, $show, 'Incorrect name of show');
		//expand Show
		$plus ='//*[contains(@class,"VPopupDraggable")]//*[contains(@class, "fa-plus-square")]';
		$I->waitForElementVisible($plus, 20);
		$I->click($plus);
		
		//count of disciplines
		$discipline_cart = $this->helper->findElements('//*[contains(@class,"discipline__info")]');
		$discipline_cart_number = count($discipline_cart);
		echo('Count of disciplines in cart: ' . $discipline_cart_number . "\n"); 
		//assert there is only one discipline
		$I->assertEquals($discipline_cart_number, 1, 'There are more than 1 disciplines');
		//assert Discipline name 
		$text_discipline_cart = $I->grabTextFrom('//*[contains(@class,"discipline__name")]');
		echo('Text from show discipline: ' . $discipline . "\n"); 
		echo('Text from dept discipline: ' . $text_discipline_cart . "\n"); 
		
		$cont_disc = strripos($text_discipline_cart, $discipline);
		if ($cont_disc !== false){
			$cont_disc = 1;
		}
		
		$I->assertEquals($cont_disc, 1, 'Incorrect name of discipline');
		//expand Discipline
		//$plus ='//*[contains(@class,"VPopupDraggable")]//*[contains(@class, "fa-plus-square")]';
		$I->waitForElementVisible($plus, 20);
		$I->click($plus);
		
		//count of artist level
		$art_level_cart = $this->helper->findElements('//*[contains(@class,"discipline__ones")]');
		$art_level_cart_number = count($art_level_cart);
		echo('Count of artist level in cart: ' . $art_level_cart_number . "\n"); 
		//assert there is only one artist level
		$I->assertEquals($art_level_cart_number, 1, 'There are more than 1 artist level');
		//assert Discipline name $art_level
		$text_art_level_cart = $I->grabTextFrom('//*[contains(@class,"one__title")]');
		echo('Text from show discipline: ' . $art_level . "\n"); 
		echo('Text from dept artist level: ' . $text_art_level_cart . "\n"); 

		$cont_lvl = strripos($text_art_level_cart, $art_level);
		if ($cont_lvl !== false){
			$cont_lvl = 1;
		}
		
		$I->assertEquals($cont_lvl, 1, 'Incorrect name of artist level');
		

	}
}


