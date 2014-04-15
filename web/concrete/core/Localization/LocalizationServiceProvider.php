<?php 
namespace Concrete\Core\Localization;
use \Concrete\Core\Foundation\Service\Provider as ServiceProvider;

class LocalizationServiceProvider extends ServiceProvider {

	public function register() {
		$singletons = array(
			'localization/countries' => '\Concrete\Core\Localization\Service\CountryList',
			'localization/states_provinces' => '\Concrete\Core\Localization\Service\StatesProvincesList',
			'lists/countries' => '\Concrete\Core\Localization\Service\CountryList',
			'lists/states_provinces' => '\Concrete\Core\Localization\Service\StatesProvincesList',
			'date' => '\Concrete\Core\Localization\Service\Date'

		);

		foreach($singletons as $key => $value) {
			$this->app->singleton($key, $value);
		}
	}


}