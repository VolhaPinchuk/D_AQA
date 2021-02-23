<?php

use Codeception\Util\ActionSequence;
use Codeception\Util\Locator;
use Codeception\Util\Shared\Asserts;
use Helper\Acceptance;

class DashDLOnes0006Cest extends BaseActions
{
	protected $helper = null;

    public function _before(DashAcceptanceTester $I, Acceptance $acceptanceHelper)
    {
        $this->helper = $acceptanceHelper;
    }
	
	//add position lead in DL dep
    public function addpositionleaddl(DashAcceptanceTester $I)
    {
		//variables
		$start_date = '(//*[@id="vueAddPositionsPopup"]//input[contains(@type,"text")])[4]';
		$end_date = '(//*[@id="vueAddPositionsPopup"]//input[contains(@type,"text")])[5]';
		$salary = '(//*[@id="vueAddPositionsPopup"]//input[contains(@type,"text")])[3]';
		$seniority = '(//*[@id="vueAddPositionsPopup"]//*[contains(@type,"button")])[3]';
		$pos_type = '(//*[@id="vueAddPositionsPopup"]//*[contains(@type,"button")])[2]';
		$add = '//*[@id="vueAddPositionsPopup"]//button[contains(text(),"Add")]';

        $I->amOnPage('/');

        //login as show
        $I->login('department', 'department');
		
        //open DL Dept. Ones page
        $I->waitForElementClickable('//span[text()="DL Dept. Ones"]', 20);
        $I->click('//span[text()="DL Dept. Ones"]');
		$I->loader();
		
		//Add position
		/**/$I->waitForElementClickable('.fa-user-plus', 20);
		$I->click('.fa-user-plus');
		
		//select type = CH 
		$I->waitForElementVisible($pos_type, 20);
		$I->click($pos_type);
		$I->waitForElementClickable('//li[contains(@value, "CH")]', 20);
		$I->click('//li[contains(@value, "CH")]');
		
		//select Seniority = Lead
		$I->waitForElementClickable($seniority, 20);
		$I->click($seniority);
		$I->waitForElementVisible('(//li[contains(@value, "Lead")])[2]', 20);
		$I->click('(//li[contains(@value, "Lead")])[2]');
		
		//set salary
		$I->waitForElementClickable($salary, 20);
		$sel_val = $I->grabValueFrom($salary);
		echo('Salary is: ' . $sel_val . "\n");
		if ($sel_val <= 0) {
			$I->fillField($salary, '17000');
		}
		
		//select start date
		$I->waitForElementClickable($start_date, 20); 
		$I->click($start_date);
		$I->waitForElementVisible('.today', 20);
		$I->click('.today');
		
		//select end date
		$I->waitForElementClickable($end_date, 20); 
		$I->click($end_date);
		$I->waitForElementVisible('//tr[5]/td[7]', 20);
		$I->click('//tr[5]/td[7]');
		
		//Add 
		$I->waitForElementClickable($add, 20);
		$I->click($add);
		$I->loader();
		$I->wait(3);/**/
		
		//count of all positions ($rowsNumber) (padding-bottom / 50px)
		$rows = $this->helper->findElements('//*[contains(@class,"item__info__name")]');
        $rowsNumber = count($rows);
		echo('Count of positions (all): ' . $rowsNumber . "\n");
		
		//largest Position name ($larg_num)
		$larg_num = 0;
		for ($i = 1; $i <= $rowsNumber; $i++){
			$pos = '(//*[contains(@class,"item__info__name")])['. $i .']';
			$pos_1 = '(//*[contains(@class,"item__info__name")])['. $i .']//*[contains(@class,"item__info__seniority")]';
			$num = $I->grabTextFrom($pos);
			$seniority = $I->grabTextFrom($pos_1);
			//"Outsource (100079) Lead" - "Lead" = "Outsource (100079) "
			//substr("Outsource (100079) ", -8, -2) = "100079"
			$num = intval(substr((str_replace($seniority, '', $num)), -8, -2)); 
			echo('Name number: ' . $num . "\n");
			if ($larg_num < $num) {
				$larg_num = $num;
			}
			
		}
		echo('Largest number: : ' . $larg_num . "\n");
		
		//count elements witout arrows ($no_arrowNumber)
		$no_arrow = $this->helper->findElements('//*[@class="icon__level seniorityLevel_"]');
		$no_arrowNumber = count($no_arrow);
		echo('Count of elements without arrows: ' . $no_arrowNumber . "\n");
		
		//last arrow
		$last_arrow = $rowsNumber - $no_arrowNumber;
		echo('Last arrow: ' . $last_arrow . "\n");
		
		//count of CH ($chNumber)
		$chNumber = 0;
		for ($i = $last_arrow+1; $i <= $rowsNumber; $i++){
			$pos = '(//*[contains(@class,"item__info__name")])['. $i .'][contains(text(),"CH")]';
			$itemIsHere = $I->elementIsHere($pos);
				if ($itemIsHere === true) {
					$chNumber++;
				}
		}
		echo('Count of CH: ' . $chNumber . "\n");
		
		//end position with CH
		$start_pos = 0;
		
		//if there is CH
		if ($chNumber > 0) {
			// first position of CH ($start_pos)
			for ($i = $last_arrow+1; $i <= $rowsNumber; $i++){
				$start_pos++;
				$pos = '(//*[contains(@class,"item__info__name")])['. $i .'][contains(text(),"CH")]';
				$itemIsHere = $I->elementIsHere($pos);
				if ($itemIsHere === true) {
					break;
				}
			}
			$start_pos = $start_pos + $last_arrow;
			echo('First CH: ' . $start_pos . "\n");
			
			//end position of CH ($end_pos)
			$end_pos = ($start_pos + $chNumber) - 1;
			echo('Last CH: ' . $end_pos . "\n");
			
		}
		//if there is NO CH
	}
}


