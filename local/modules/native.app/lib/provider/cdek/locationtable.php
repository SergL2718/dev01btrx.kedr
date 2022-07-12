<?php
/*
 * Изменено: 29 декабря 2021, среда
 * Автор: Артамонов Денис <artamonov.d.i@yandex.com>
 * copyright (c) 2022
 */


namespace Native\App\Provider\Cdek;


use Bitrix\Main\ArgumentTypeException;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\FloatField;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\Validators\LengthValidator;
use Bitrix\Main\SystemException;

/**
 * Class LocationTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> CODE int optional
 * <li> POSTAL_CODE string(15) optional
 * <li> CITY string(255) optional
 * <li> FIAS_CITY_GUID string(255) optional
 * <li> COUNTRY_CODE string(2) optional
 * <li> COUNTRY_NAME string(255) optional
 * <li> REGION_CODE int optional
 * <li> REGION_NAME string(255) optional
 * <li> FIAS_REGION_GUID string(255) optional
 * <li> SUB_REGION_NAME string(255) optional
 * <li> LONGITUDE double optional
 * <li> LATITUDE double optional
 * <li> TIME_ZONE string(255) optional
 * <li> PAYMENT_LIMIT double optional
 * </ul>
 *
 * @package Native\App\Provider\Cdek
 **/
class LocationTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName(): string
    {
        return 'app_cdek_location';
    }

    /**
     * Returns entity map definition.
     *
     * @return array
     * @throws SystemException
     */
    public static function getMap(): array
    {
        return [
            new IntegerField(
                'ID',
                [
                    'primary' => true,
                    'autocomplete' => true,
                    'title' => 'ID'
                ]
            ),
            new IntegerField(
                'CODE',
                [
                    'title' => 'CODE'
                ]
            ),
            new StringField(
                'POSTAL_CODE',
                [
                    'validation' => [__CLASS__, 'validatePostalCode'],
                    'title' => 'POSTAL_CODE'
                ]
            ),
            new StringField(
                'CITY',
                [
                    'validation' => [__CLASS__, 'validateCity'],
                    'title' => 'CITY'
                ]
            ),
            new StringField(
                'FIAS_CITY_GUID',
                [
                    'validation' => [__CLASS__, 'validateFiasCityGuid'],
                    'title' => 'FIAS_CITY_GUID'
                ]
            ),
            new StringField(
                'COUNTRY_CODE',
                [
                    'validation' => [__CLASS__, 'validateCountryCode'],
                    'title' => 'COUNTRY_CODE'
                ]
            ),
            new StringField(
                'COUNTRY_NAME',
                [
                    'validation' => [__CLASS__, 'validateCountryName'],
                    'title' => 'COUNTRY_NAME'
                ]
            ),
            new IntegerField(
                'REGION_CODE',
                [
                    'title' => 'REGION_CODE'
                ]
            ),
            new StringField(
                'REGION_NAME',
                [
                    'validation' => [__CLASS__, 'validateRegionName'],
                    'title' => 'REGION_NAME'
                ]
            ),
            new StringField(
                'FIAS_REGION_GUID',
                [
                    'validation' => [__CLASS__, 'validateFiasRegionGuid'],
                    'title' => 'FIAS_REGION_GUID'
                ]
            ),
            new StringField(
                'SUB_REGION_NAME',
                [
                    'validation' => [__CLASS__, 'validateSubRegionName'],
                    'title' => 'SUB_REGION_NAME'
                ]
            ),
            new FloatField(
                'LONGITUDE',
                [
                    'title' => 'LONGITUDE'
                ]
            ),
            new FloatField(
                'LATITUDE',
                [
                    'title' => 'LATITUDE'
                ]
            ),
            new StringField(
                'TIME_ZONE',
                [
                    'validation' => [__CLASS__, 'validateTimeZone'],
                    'title' => 'TIME_ZONE'
                ]
            ),
            new FloatField(
                'PAYMENT_LIMIT',
                [
                    'title' => 'PAYMENT_LIMIT'
                ]
            ),
        ];
    }

    /**
     * Returns validators for POSTAL_CODE field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validatePostalCode(): array
    {
        return [
            new LengthValidator(null, 15),
        ];
    }

    /**
     * Returns validators for CITY field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validateCity(): array
    {
        return [
            new LengthValidator(null, 255),
        ];
    }

    /**
     * Returns validators for FIAS_CITY_GUID field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validateFiasCityGuid(): array
    {
        return [
            new LengthValidator(null, 255),
        ];
    }

    /**
     * Returns validators for COUNTRY_CODE field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validateCountryCode(): array
    {
        return [
            new LengthValidator(null, 2),
        ];
    }

    /**
     * Returns validators for COUNTRY_NAME field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validateCountryName(): array
    {
        return [
            new LengthValidator(null, 255),
        ];
    }

    /**
     * Returns validators for REGION_NAME field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validateRegionName(): array
    {
        return [
            new LengthValidator(null, 255),
        ];
    }

    /**
     * Returns validators for FIAS_REGION_GUID field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validateFiasRegionGuid(): array
    {
        return [
            new LengthValidator(null, 255),
        ];
    }

    /**
     * Returns validators for SUB_REGION_NAME field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validateSubRegionName(): array
    {
        return [
            new LengthValidator(null, 255),
        ];
    }

    /**
     * Returns validators for TIME_ZONE field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validateTimeZone(): array
    {
        return [
            new LengthValidator(null, 255),
        ];
    }
}
