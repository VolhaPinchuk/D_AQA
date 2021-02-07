<?php

use Codeception\Util\ActionSequence;
use Codeception\Util\Locator;
use Codeception\Util\Shared\Asserts;
use Helper\Acceptance;

class DashSO00731Cest extends BaseActions
{
    //add position lead
    public function addpositionlead(DashAcceptanceTester $I)
    {
		//variables
		$sort_d='.item__info__department-sorting > span:nth-child(2)';
		$sort_up='.item__info__department-sorting > span:nth-child(1)';
		$position='((//*[contains(@class, "item_artist")])[1]//*[contains(@class, "row__cell")])[4]';
		$exp_spinner='.btn__loading';
		$last_save='.header__history__info:nth-child(2)';
		
        $I->amOnPage('/');

        //login as show
        $I->login('show', 'show');
		
		//grab username
		$username=$I->grabTextFrom('.user-name');
		
        //open Ones tab
		$I->showOnesPage();
		$I->loader();
        $I->onesTab();
		$I->loader();
		
		//grab time of last save (can be empty!!)
		$I->waitForElementVisible($last_save, 20);
		$time1=$I->grabTextFrom($last_save);
		$time1=substr($time1, 0, strpos($time1, ' ', 30));
		
		//select show
		$I->selectShow();
		$I->loader();
		
		//add position
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
		if ($I->elementIsHere('//*[contains(@class, "VNotification")]') === true)
		{
			$I->click(Locator::contains('button', 'OK'));
			$I->waitForElementVisible($position);
			$I->click($position);
		}
		
		//add ones
		$I->waitForElementVisible(Locator::contains('button', 'Confirm'));
		$I->click(Locator::contains('button', 'Confirm'));
		
		//save changes
		$I->waitForElementVisible(Locator::contains('button', 'File'));
		$I->click(Locator::contains('button', 'File'));
		$I->waitForElementVisible(Locator::contains('a', 'Save'));
		$I->click(Locator::contains('a', 'Save'));
		$I->loader();
		
		//assert check message
		$I->waitForElementVisible('.toast-message', 20);
		$popup=$I->grabTextFrom('.toast-message');
		$I->assertEquals($popup, 'Save operation completed', 'Text of pop-up is incorrect');
		$I->wait(2);
		
		//assert check Export's spinner
		$I->seeElement($exp_spinner);
		
		//assert check last save
		$I->waitForElementVisible($last_save, 20);
		$time2=$I->grabTextFrom($last_save);
		$time2=substr($time2, 0, strpos($time2, ' ', 30));
		$I->assertNotEquals($time1, $time2, 'Time of last save is not changed');
		
		//assert check username
		$I->waitForElementVisible('(//span[contains(@class,"header__history__icon")])');
		$last_username=$I->grabTextFrom('(//span[contains(@class,"header__history__icon")])[2]');
		$I->assertEquals($last_username, $username, 'Username in the timestamp is incorrect');	
    }
}


