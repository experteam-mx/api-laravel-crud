<?php

namespace Experteam\ApiLaravelCrud\Listeners;


use Experteam\ApiLaravelCrud\Events\ModelSaved;

class SaveModel extends ModelListener
{
    const MAP = [
        [
            'class' => 'CompanyCountry',
            'prefix' => 'companyCountry',
            'toRedis' => true,
            'toStreamCompute' => true,
            'dispatchMessage' => false
        ],
        [
            'class' => 'CompanyCountry',
            'prefix' => 'company:country',
            'toRedis' => true,
            'toStreamCompute' => false,
            'toRedisId' => 'id_compound',
            'dispatchMessage' => false
        ],
        [
            'class' => 'CompanyCountryProduct',
            'prefix' => 'companyCountryProduct',
            'toRedis' => true,
            'toStreamCompute' => false,
            'dispatchMessage' => true
        ],
        [
            'class' => 'Location',
            'prefix' => 'location',
            'toRedis' => true,
            'toStreamCompute' => true,
            'dispatchMessage' => false
        ],
        [
            'class' => 'Company',
            'prefix' => 'company',
            'toRedis' => true,
            'toStreamCompute' => true,
            'dispatchMessage' => false
        ],
        [
            'class' => 'Installation',
            'prefix' => 'installation',
            'toRedis' => true,
            'toStreamCompute' => true,
            'dispatchMessage' => false
        ],
        [
            'class' => 'CompanyCountryExtraCharge',
            'prefix' => 'companyCountryExtraCharge',
            'toRedis' => true,
            'toStreamCompute' => false,
            'dispatchMessage' => false
        ],
        [
            'class' => 'Employee',
            'prefix' => 'employee',
            'toRedis' => true,
            'toStreamCompute' => true,
            'dispatchMessage' => false
        ],
        [
            'class' => 'CompanyCountryCurrency',
            'prefix' => 'companyCountryCurrency',
            'toRedis' => true,
            'toStreamCompute' => false,
            'dispatchMessage' => true
        ],
        [
            'class' => 'CompanyCountryShipmentType',
            'prefix' => 'companyCountryShipmentType',
            'toRedis' => true,
            'toStreamCompute' => false,
            'dispatchMessage' => false
        ],
        [
            'class' => 'System',
            'prefix' => 'system',
            'toRedis' => true,
            'toStreamCompute' => true,
            'dispatchMessage' => false
        ],
        [
            'class' => 'CompanyCountryLanguage',
            'prefix' => 'companyCountryLanguage',
            'toRedis' => true,
            'toStreamCompute' => false,
            'dispatchMessage' => false
        ],
        [
            'class' => 'LocationOfficeHour',
            'prefix' => 'locationOfficeHour',
            'toRedis' => true,
            'toStreamCompute' => false,
            'toRedisId' => 'location_id',
            'dispatchMessage' => false
        ],
        [
            'class' => 'CountryReference',
            'prefix' => 'countryReference',
            'toRedis' => true,
            'toStreamCompute' => true,
            'toRedisId' => 'country_id',
            'dispatchMessage' => true
        ],
        [
            'class' => 'CompanyCountryTax',
            'prefix' => 'companyCountryTax',
            'toRedis' => true,
            'toStreamCompute' => false,
            'dispatchMessage' => true
        ],
        [
            'class' => 'ProductEntity',
            'prefix' => 'productEntity',
            'toRedis' => true,
            'toStreamCompute' => false,
            'dispatchMessage' => false,
            'entityConfig' => true
        ],
        [
            'class' => 'ExtraChargeEntity',
            'prefix' => 'extraChargeEntity',
            'toRedis' => true,
            'toStreamCompute' => false,
            'dispatchMessage' => false,
            'entityConfig' => true
        ],
        [
            'class' => 'SupplyEntity',
            'prefix' => 'supplyEntity',
            'toRedis' => true,
            'toStreamCompute' => false,
            'dispatchMessage' => false,
            'entityConfig' => true
        ],
        [
            'class' => 'SystemEntity',
            'prefix' => 'systemEntity',
            'toRedis' => true,
            'toStreamCompute' => false,
            'dispatchMessage' => false,
            'entityConfig' => true
        ],
        [
            'class' => 'AccountEntity',
            'prefix' => 'accountEntity',
            'toRedis' => true,
            'toStreamCompute' => false,
            'dispatchMessage' => false,
            'entityConfig' => true
        ],
        [
            'class' => 'Account',
            'prefix' => 'account',
            'toRedis' => true,
            'toStreamCompute' => false,
            'dispatchMessage' => false
        ],
        [
            'class' => 'LocationEmployee',
            'prefix' => 'locationEmployee',
            'toRedis' => true,
            'toStreamCompute' => false,
            'dispatchMessage' => false
        ],
        [
            'class' => 'LocationEmployee',
            'prefix' => 'employee.location',
            'toRedis' => true,
            'toStreamCompute' => false,
            'toRedisSuffix' => 'suffix_employee_location',
            'toRedisId' => 'id_employee_location',
            'dispatchMessage' => false
        ],
        [
            'class' => 'LocationEmployee',
            'prefix' => 'location.employee',
            'toRedis' => true,
            'toStreamCompute' => false,
            'toRedisSuffix' => 'suffix_location_employee',
            'toRedisId' => 'id_location_employee',
            'dispatchMessage' => false
        ],
        [
            'class' => 'CompanyCountryExtraCharge',
            'prefix' => 'companyCountry:extraCharge',
            'toRedis' => true,
            'toStreamCompute' => false,
            'toRedisId' => 'id_compound',
            'dispatchMessage' => false
        ],
        [
            'class' => 'CompanyCountryProduct',
            'prefix' => 'companyCountry:product',
            'toRedis' => true,
            'toStreamCompute' => false,
            'toRedisId' => 'id_compound',
            'dispatchMessage' => false
        ],
        [
            'class' => 'Location',
            'prefix' => 'location.companyCountry',
            'toRedis' => true,
            'toStreamCompute' => false,
            'toRedisSuffix' => 'suffix_location_companyCountry',
            'dispatchMessage' => false
        ],
        [
            'class' => 'CompanyCountryCurrency',
            'prefix' => 'companyCountry:currency',
            'toRedis' => true,
            'toStreamCompute' => false,
            'toRedisId' => 'id_compound',
            'dispatchMessage' => false
        ],
        [
            'class' => 'Account',
            'prefix' => 'account.countryReference',
            'toRedis' => true,
            'toStreamCompute' => false,
            'toRedisSuffix' => 'suffix_by_country_reference',
            'dispatchMessage' => false
        ],
        [
            'class' => 'Account',
            'prefix' => 'account.number:countryReference',
            'toRedis' => true,
            'toStreamCompute' => false,
            'toRedisId' => 'id_compound',
            'dispatchMessage' => false
        ],
        [
            'class' => 'CountryReferenceProduct',
            'prefix' => 'countryReferenceProduct',
            'toRedis' => true,
            'toStreamCompute' => false,
            'dispatchMessage' => true
        ],
        [
            'class' => 'CountryReferenceProduct',
            'prefix' => 'countryReference:product',
            'toRedis' => true,
            'toStreamCompute' => false,
            'toRedisId' => 'id_compound',
            'dispatchMessage' => false
        ],
        [
            'class' => 'CountryReferenceExtraCharge',
            'prefix' => 'countryReferenceExtraCharge',
            'toRedis' => true,
            'toStreamCompute' => false,
            'dispatchMessage' => false
        ],
        [
            'class' => 'CountryReferenceExtraCharge',
            'prefix' => 'countryReference:extraCharge',
            'toRedis' => true,
            'toStreamCompute' => false,
            'toRedisId' => 'id_compound',
            'dispatchMessage' => false
        ],
        [
            'class' => 'CountryReferenceCurrency',
            'prefix' => 'countryReferenceCurrency',
            'toRedis' => false,
            'toStreamCompute' => false,
            'dispatchMessage' => true
        ],
        [
            'class' => 'CountryReferenceCurrency',
            'prefix' => 'currency.countryReference',
            'toRedis' => true,
            'toStreamCompute' => false,
            'toRedisSuffix' => 'suffix_by_country_reference',
            'dispatchMessage' => false
        ],
        [
            'class' => 'Exchange',
            'prefix' => 'exchange',
            'toRedis' => false,
            'toStreamCompute' => false,
            'dispatchMessage' => true
        ]
    ];

    /**
     * Handle the event.
     *
     * @param ModelSaved $event
     * @return void
     */
    public function handle(ModelSaved $event)
    {
        $this->proccess($event->model, self::MAP, self::SAVE_MODEL);
    }
}
