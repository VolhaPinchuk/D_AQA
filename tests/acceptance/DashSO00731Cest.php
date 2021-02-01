<?php

use Codeception\Util\ActionSequence;
use Codeception\Util\Locator;
use Codeception\Util\Shared\Asserts;
use Helper\Acceptance;

class DashSO00731Cest extends BaseActions
{
    //save page after add position lead
    public function savepageafteraddpositionlead(DashAcceptanceTester $I)
    {
		//variables
		$loader='.request-progress-bar__wrapper.wave-loader';
		$sort='.item__info__department-sorting';
		$position='((//*[contains(@class, "item_artist")])[1]//*[contains(@class, "row__cell")])[4]';
		$exp_spinner='.btn__loading';
		$last_save='(//span[contains(@class,"header__history__info")])';
		
        $I->amOnPage('/');

        //login as show
        $I->login('show', 'show');
		
		//grab username
		$username=$I->grabTextFrom('.user-name');
		
        //open Ones tab
		$I->waitForElementVisible(Locator::contains('span', 'Show Ones'), 20);
        $I->wait(1);
        $I->click(Locator::contains('span', 'Show Ones'));
        $I->waitForElementVisible($loader, 20);
        $I->waitForElementNotVisible($loader, 20);
        $I->click('//*[@class="VTab__header"]//*[contains(text(),"Ones")]');
        $I->waitForElementVisible($loader, 20);
        $I->waitForElementNotVisible($loader, 20);
		
		//grab time of last save
		$I->waitForElementVisible($last_save);
		$time1=$I->grabTextFrom($last_save);
		$time1=substr($time1, 0, strpos($time1, ' ', 30));
		
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
		$I->waitForElementClickable($sort, 20);
		$I->doubleclick($sort);
		$I->wait(2);
		$I->click($sort);
		
		//add ones
		$I->waitForElementVisible($position);
		$I->click($position);
		$I->waitForElementVisible(Locator::contains('button', 'Confirm'));
		$I->click(Locator::contains('button', 'Confirm'));
		
		//save changes
		$I->waitForElementVisible(Locator::contains('button', 'File'));
		$I->click(Locator::contains('button', 'File'));
		$I->waitForElementVisible(Locator::contains('a', 'Save'));
		$I->click(Locator::contains('a', 'Save'));
		/*$I->waitForElementVisible($loader, 20);
		$I->waitForElementNotVisible($loader, 20);*/
		
		//check message
		$I->waitForElementVisible('.toast-message', 20);
		$popup=$I->grabTextFrom('.toast-message');

		$I->assertEquals($popup, 'Save operation completed', 'Text of pop-up is incorrect');
		$I->wait(2);
		
		//check Export's spinner
		$I->seeElement($exp_spinner);
		
		//check last save
		$I->waitForElementVisible($last_save, 20);
		$time2=$I->grabTextFrom($last_save);
		$time2=substr($time2, 0, strpos($time2, ' ', 30));

		$I->assertNotEquals($time1, $time2, 'Time of last save is not changed');
		
		//check username
		$I->waitForElementVisible('(//span[contains(@class,"header__history__icon")])');
		$last_username=$I->grabTextFrom('(//span[contains(@class,"header__history__icon")])[2]');

		$I->assertEquals($last_username, $username, 'Username in the timestamp is incorrect');
    }
}


