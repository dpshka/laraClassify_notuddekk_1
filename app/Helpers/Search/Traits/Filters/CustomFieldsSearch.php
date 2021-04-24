<?php
/**
 * LaraClassified - Classified Ads Web Application
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: https://bedigit.com
 *
 * LICENSE
 * -------
 * This software is furnished under a license and may be used and copied
 * only in accordance with the terms of such license and with the inclusion
 * of the above copyright notice. If you Purchased from CodeCanyon,
 * Please read the full License from here - http://codecanyon.net/licenses/standard
 */

namespace App\Helpers\Search\Traits\Filters;

use App\Models\Field;
use App\Models\FieldOption;
use App\Models\PostValue;

trait CustomFieldsSearch
{
	protected function applyCustomFieldsSearch()
	{
		if (!(isset($this->posts) && isset($this->having))) {
			return;
		}
		
		$inputFields = [];
		if (request()->filled('cf')) {
			$inputFields = request()->get('cf');
		}
		
		if (!(is_array($inputFields) && count($inputFields) > 0)) {
			return;
		}
		
		if (count($inputFields) > 0) {
			// Get Field object
			if($inputFields[0] != ""){
				$field = Field::find($inputFields[0]);
				if (!empty($field)) {
					if($inputFields['value'] == "")
					return;
					else
					$field_option = FieldOption::where('field_id','=',$field->id)
												->where('value', 'LIKE', '%' . $inputFields['value'] . '%')
												->get();
					foreach($field_option as $fo){
							
							$this->posts->whereHas('postValues', function ($query) use ($fo) {
								$query->where('value', 'LIKE', $fo->id);
							});
						
						
					}
				}
			}
			else if($inputFields[0] == ""){
				if($inputFields['value'] == "")
					return;
				else
				$field_option = FieldOption::where('value', 'LIKE', '%' . $inputFields['value'] . '%')
											->get();
				
				foreach($field_option as $fo){
					$pv = PostValue::where('value','=',$fo->id);
					if($pv->count()>0){
						$this->posts->whereHas('postValues', function ($query) use ($fo) {
							$query->where('value', 'LIKE', $fo->id);
						});
					}
					
				}
			}
		}	
		
	}
}
