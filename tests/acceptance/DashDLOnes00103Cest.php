<?php

use Codeception\Util\ActionSequence;
use Codeception\Util\Locator;
use Codeception\Util\Shared\Asserts;
use Helper\Acceptance;

class DashDLOnes00103Cest
{
	protected $helper = null;

    public function _before(DashAcceptanceTester $I, Acceptance $acceptanceHelper)
    {
        $this->helper = $acceptanceHelper;
    }
	
	//remove Training ones
    public function removeleave(DashAcceptanceTester $I)
    {
		//variables
		$popup = '.toast-message';
		$position='((//*[contains(@class, "item_artist")])[1]//*[contains(@class, "row__cell")])[1]';
		$confirm = '//*[@id="BookPopup"]//button[contains(text(), "Confirm")]';

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
		
		//count elements witout arrows ($no_arrowNumber)
		$no_arrow = $this->helper->findElements('//*[@class="icon__level seniorityLevel_"]');
		$no_arrowNumber = count($no_arrow);
		echo('Count of elements without arrows: ' . $no_arrowNumber . "\n");
		
		//last arrow
		$last_arrow = $rowsNumber - $no_arrowNumber;
		echo('Last arrow: ' . $last_arrow . "\n");
		
		//first element without arrow
		$non_arr1 = $last_arrow+1;
		echo('First element without arrow: ' . $non_arr1 . "\n");
		
		//find position to add
		for ($i=$non_arr1; $i <= $rowsNumber; $i++){
			$item = '((//*[contains(@class, "item_artist")])[' . $i . ']//*[contains(@class, "row__cell")])[1]//*[contains(text(), "1")]';
			$itemIsHere = $I->elementIsHere($item);
			if ($itemIsHere === true) {
				break;
			}
		}
		echo('Added ones on line: ' . $i . "\n");
		
		//add Leave ones
		$position = '((//*[contains(@class, "item_artist")])[' . $i . ']//*[contains(@class, "row__cell")])[1]';
		$I->waitForElementClickable($position, 20);
		$I->click($position);
		$I->waitForElementVisible('//span[text()="Book Leave (L)"]', 20);
		$I->click('//span[text()="Book Leave (L)"]');
		$I->waitForElementVisible($confirm, 20);
		$I->click($confirm);

		
		//save L
		$I->save();
		$I->loader();
		
		//check the chart
		$I->waitForElementClickable('//button[@id="ShowPopupButton"]', 20);
		$I->click('//button[@id="ShowPopupButton"]');
		$I->waitForElementVisible('(//*[contains(@class, "card__count")])[1]', 20);
		$cart_count = $I->grabTextFrom('(//*[contains(@class, "card__count")])[1]');
		echo('Count in the chart: ' . $cart_count . "\n");
		$I->waitForElementClickable('//*[contains(@class, "modal-mask")]//button[contains(text(), "Close")]', 20);
		$I->click('//*[contains(@class, "modal-mask")]//button[contains(text(), "Close")]');
		
		//remove L ones
		$I->waitForElementClickable($position, 20);
		$I->click($position);
		$I->waitForElementClickable('//*[@id="BookPopup"]//*[contains(text(), "Remove Ones")]', 20);
		$I->click('//*[@id="BookPopup"]//*[contains(text(), "Remove Ones")]');
		$I->waitForElementVisible($confirm, 20);
		$I->click($confirm);
		
		//check the chart after remove L
		$I->waitForElementClickable('//button[@id="ShowPopupButton"]', 20);
		$I->click('//button[@id="ShowPopupButton"]');
		$I->waitForElementVisible('(//*[contains(@class, "card__count")])[1]', 20);
		$cart_count1 = $I->grabTextFrom('(//*[contains(@class, "card__count")])[1]');
		echo('Count in the chart after delete added ones: ' . $cart_count1 . "\n");
		$I->waitForElementClickable('//*[contains(@class, "modal-mask")]//button[contains(text(), "Close")]', 20);
		$I->click('//*[contains(@class, "modal-mask")]//button[contains(text(), "Close")]');
		
		//assert count Ones in chart
		$I->assertEquals($cart_count, $cart_count1, 'Count of ones in chart is changed.');
		
		//assert cell is empty
		$position_text = $I->grabTextFrom($position);
		echo('Text in the cell: ' . $position_text . "\n");
		$I->assertEquals('1', $position_text, 'Text in cell is not 1');
		
		//save changes
		$I->save();
		$I->loader();	
	}
}


