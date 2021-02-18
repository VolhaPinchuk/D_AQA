<?php

use Codeception\Util\ActionSequence;
use Codeception\Util\Locator;
use Codeception\Util\Shared\Asserts;
use Helper\Acceptance;

class DashSO00734Cest
{
    protected $helper = null;

    public function _before(DashAcceptanceTester $I, Acceptance $acceptanceHelper)
    {
        $this->helper = $acceptanceHelper;
    }

    //save page after change type of Ones
    public function savepageafterchangetypeofones(DashAcceptanceTester $I)
    {
		//variables
		$sort_d='.item__info__department-sorting > span:nth-child(2)';
		$sort_up='.item__info__department-sorting > span:nth-child(1)';
		$position_locator='((//*[contains(@class, "item_artist")])[1]//*[contains(@class, "row__cell")])[4]';
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

		//select show
		$I->selectShow();
		$I->loader();
		
		//grab time of last save (can be empty!!)
		$I->wait(3);
		$time1=$I->grabTextFrom($last_save);
		$time1=substr($time1, 0, strpos($time1, ' ', 30));

        //open Add position popup and add position Lead
        $I->addpositionpopup();
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
        $I->loader();
		
		//EDIT ONES
		//expand 
		$I->waitForElementVisible('.item__info__expand-icon > span:nth-child(1)', 20);
		$I->click('.item__info__expand-icon > span:nth-child(1)');
		
		//find number of the end position in the first department
        list ($i, $position) = $I->numberofendposition();
		
		//Change Ones type
        for ($a = 1; $a <= $i; $a++){
            $item = '((//*[contains(@class, "item_artist")])[' . $a . ']//*[contains(@class, "row__cell")])[4]/*[contains(@class, "W")]';
			$item1 = '((//*[contains(@class, "item_artist")])[' . $a . ']//*[contains(@class, "row__cell")])[4]/*[contains(@class, "W")]/*[contains(@class, "cell-inside-value")]';
			$itemIsHere = $I->elementIsHere($item);
			$itemIsHere1 = $I->elementIsHere($item1);
            if ($itemIsHere !== false and $itemIsHere1 === false) {
                $I->click('((//*[contains(@class, "item_artist")])[' . $a . ']//*[contains(@class, "row__cell")])[4]');
                $a = $i+1;
				$I->waitForElementClickable(Locator::contains('button', 'Change Ones Type'), 20);
				$I->click(Locator::contains('button', 'Change Ones Type'));
				$I->waitForElementClickable('//label[contains(text(),"Potential")]', 20);
				$I->click('//label[contains(text(),"Potential")]');
                $I->click(Locator::contains('button', 'Confirm'));
                $I->save();
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
		$time2=substr($time2, 0, strpos($time2, ' ', 30));
		$I->assertNotEquals($time1, $time2, 'Time of last save is not changed');
		
		//assert check username
		$I->waitForElementVisible('(//span[contains(@class,"header__history__icon")])');
		$last_username=$I->grabTextFrom('(//span[contains(@class,"header__history__icon")])[2]');
		$I->assertEquals($last_username, $username, 'Username in the timestamp is incorrect');	
    }
}


