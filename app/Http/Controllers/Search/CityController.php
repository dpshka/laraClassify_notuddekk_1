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

namespace App\Http\Controllers\Search;

use App\Helpers\Search\PostQueries;
use Torann\LaravelMetaTags\Facades\MetaTag;
use App\Models\Field;

class CityController extends BaseController
{
	public $isCitySearch = true;
	
	/**
	 * City URL
	 * Pattern: (countryCode/)free-ads/city-slug/ID
	 *
	 * @param $countryCode
	 * @param $citySlug
	 * @param null $cityId
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index($countryCode, $citySlug, $cityId = null)
	{
		// Check if City is found
		if (empty($this->city)) {
			abort(404, t('city_not_found'));
		}
		
		// Search
		$data = (new PostQueries($this->preSearch))->fetch();
		
		// Get Titles
		$bcTab = $this->getBreadcrumb();
		$htmlTitle = $this->getHtmlTitle();
		view()->share('bcTab', $bcTab);
		view()->share('htmlTitle', $htmlTitle);
		
		// Meta Tags
		$title = $this->getTitle();
		$description = t('free_ads_in_location', ['location' => $this->city->name])
			. ', ' . config('country.name')
			. '. ' . t('looking_for_product_or_service')
			. ' - ' . $this->city->name
			. ', ' . config('country.name');
		
		MetaTag::set('title', $title);
		MetaTag::set('description', $description);
		$data['field'] = Field::all();
		return appView('search.results', $data);
	}
}
