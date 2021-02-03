<?php

use Codeception\Util\ActionSequence;
use Codeception\Util\Locator;
use Codeception\Util\Shared\Asserts;
use Helper\Acceptance;

class DashSO00732Cest extends BaseActions
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
		
        //open Show Ones page
		$I->showOnesPage();
		$I->loader();
		
		//open Ones tab
        $I->onesTab();
		$I->loader();
		
		//select show
		$I->selectShow();
		$I->loader();
		
		//grab time of last save (can be empty!!)
		$I->wait(3);
		$time1=$I->grabTextFrom($last_save);
		echo('Time1: ' . $time1);
		$time1=substr($time1, 0, strpos($time1, ' ', 30));
		echo('Time1: ' . $time1);
		
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
		$I->wait(1);
		if ($I->elementIsHere('//*[contains(@class, "modal-dialog")]') === true){
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
		
		//DELETE ONES
		//find number of the end position in the first department
        $i=2;
        $end_pos='//*[@id="app"]//div[2]/div[2]/div[2]/div/div[2]/div[2]/div/div[' . $i . ']';
        $atr=$I->grabAttributeFrom($end_pos, 'class');
        while (strpos($atr, 'item_artist') !== false):
            $i++;
            $end_pos='//*[@id="app"]//div[2]/div[2]/div[2]/div/div[2]/div[2]/div/div[' . $i . ']';
            $atr=$I->grabAttributeFrom($end_pos, 'class');
        endwhile;
		
		//remove mark
        for ($a = 1; $a <= $i; $a++){
            $item = '((//*[contains(@class, "item_artist")])[' . $a . ']//*[contains(@class, "row__cell")])[4]/*[contains(@class, "W")]';
            $itemIsHere = $I->elementIsHere($item);
            if ($itemIsHere !== false) {
                $I->click('((//*[contains(@class, "item_artist")])[' . $a . ']//*[contains(@class, "row__cell")])[4]');
                $a = $i+1;
                $I->waitForElementVisible(Locator::contains('button', 'Remove Ones'));
                $I->click(Locator::contains('button', 'Remove Ones'));
                $I->click(Locator::contains('button', 'Confirm'));
                $I->waitForElementNotVisible('.toast-message');
                $I->click(Locator::contains('button', 'File'));
                $I->click(Locator::contains('span', 'Save'));
                $I->loader();
            }
        }		
				
		//assert check message
		$I->waitForElementVisible('.toast-message', 20);
		$popup=$I->grabTextFrom('.toast-message');
		$I->assertEquals($popup, 'Save operation completed', 'Text of pop-up is incorrect');
		$I->wait(2);
		
		//assert check Export's spinner
		$I->seeElement($exp_spinner);
		
		//assert check last save (conditionally work)
		$I->waitForElementVisible($last_save, 20);
		$time2=$I->grabTextFrom($last_save);
		echo($time2);
		$time2=substr($time2, 0, strpos($time2, ' ', 30));
		echo($time2);
		$I->assertNotEquals($time1, $time2, 'Time of last save is not changed');
		
		//assert check username
		$I->waitForElementVisible('(//span[contains(@class,"header__history__icon")])');
		$last_username=$I->grabTextFrom('(//span[contains(@class,"header__history__icon")])[2]');
		$I->assertEquals($last_username, $username, 'Username in the timestamp is incorrect');	
    }
}


