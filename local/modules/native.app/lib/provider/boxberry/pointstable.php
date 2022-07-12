<?php
/*
 * @updated 19.01.2021, 0:30
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2021, Компания Webco <hello@wbc.cx>
 * @link https://wbc.cx
 */


namespace Native\App\Provider\Boxberry;


use Bitrix\Main\ArgumentTypeException;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\DatetimeField;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\Validators\LengthValidator;
use Bitrix\Main\SystemException;

/**
 * Class PointsTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> DATE_CREATE datetime optional
 * <li> POINT_ID string(15) optional
 * <li> ZIP string(10) optional
 * <li> COUNTRY_CODE string(2) optional
 * <li> COUNTRY_ID string(3) optional
 * <li> CITY_CODE string(15) optional
 * <li> CITY_NAME string(40) optional
 * <li> CITY_SEARCH string(40) optional
 * <li> ADDRESS_REDUCE string(300) optional
 * <li> PHONE string(40) optional
 * <li> WORK_SCHEDULE string(150) optional
 * <li> WEIGHT_LIMIT int optional
 * <li> VOLUME_LIMIT int optional
 * <li> LONGITUDE string(15) optional
 * <li> LATITUDE string(15) optional
 * </ul>
 *
 * @package Native\App\Provider\Boxberry
 **/

class PointsTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName(): string
    {
        return 'app_boxberry_points';
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
            new DatetimeField(
                'DATE_CREATE',
                [
                    'title' => 'Дата получения'
                ]
            ),
            new StringField(
                'POINT_ID',
                [
                    'validation' => [__CLASS__, 'validatePointId'],
                    'title' => 'ID точки'
                ]
            ),
            new StringField(
                'ZIP',
                [
                    'validation' => [__CLASS__, 'validateZip'],
                    'title' => 'Индекс'
                ]
            ),
            new StringField(
                'COUNTRY_CODE',
                [
                    'validation' => [__CLASS__, 'validateCountryCode'],
                    'title' => 'Код страны'
                ]
            ),
            new StringField(
                'COUNTRY_ID',
                [
                    'validation' => [__CLASS__, 'validateCountryId'],
                    'title' => 'ID страны'
                ]
            ),
            new StringField(
                'CITY_CODE',
                [
                    'validation' => [__CLASS__, 'validateCityCode'],
                    'title' => 'Код города'
                ]
            ),
            new StringField(
                'CITY_NAME',
                [
                    'validation' => [__CLASS__, 'validateCityName'],
                    'title' => 'Название города'
                ]
            ),
            new StringField(
                'CITY_SEARCH',
                [
                    'validation' => [__CLASS__, 'validateCitySearch'],
                    'title' => 'Название для поиска'
                ]
            ),
            new StringField(
                'ADDRESS_REDUCE',
                [
                    'validation' => [__CLASS__, 'validateAddressReduce'],
                    'title' => 'Адрес'
                ]
            ),
            new StringField(
                'PHONE',
                [
                    'validation' => [__CLASS__, 'validatePhone'],
                    'title' => 'Телефон'
                ]
            ),
            new StringField(
                'WORK_SCHEDULE',
                [
                    'validation' => [__CLASS__, 'validateWorkSchedule'],
                    'title' => 'График работы'
                ]
            ),
            new IntegerField(
                'WEIGHT_LIMIT',
                [
                    'title' => 'Ограничение по весу'
                ]
            ),
            new IntegerField(
                'VOLUME_LIMIT',
                [
                    'title' => 'Ограничение по объему'
                ]
            ),
            new StringField(
                'LONGITUDE',
                [
                    'validation' => [__CLASS__, 'validateLongitude'],
                    'title' => 'Долгота'
                ]
            ),
            new StringField(
                'LATITUDE',
                [
                    'validation' => [__CLASS__, 'validateLatitude'],
                    'title' => 'Широта'
                ]
            ),
        ];
    }

    /**
     * Returns validators for POINT_ID field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validatePointId(): array
    {
        return [
            new LengthValidator(null, 15),
        ];
    }

    /**
     * Returns validators for ZIP field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validateZip(): array
    {
        return [
            new LengthValidator(null, 10),
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
     * Returns validators for COUNTRY_ID field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validateCountryId(): array
    {
        return [
            new LengthValidator(null, 3),
        ];
    }

    /**
     * Returns validators for CITY_CODE field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validateCityCode(): array
    {
        return [
            new LengthValidator(null, 15),
        ];
    }

    /**
     * Returns validators for CITY_NAME field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validateCityName(): array
    {
        return [
            new LengthValidator(null, 40),
        ];
    }

    /**
     * Returns validators for CITY_SEARCH field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validateCitySearch(): array
    {
        return [
            new LengthValidator(null, 40),
        ];
    }

    /**
     * Returns validators for ADDRESS_REDUCE field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validateAddressReduce(): array
    {
        return [
            new LengthValidator(null, 300),
        ];
    }

    /**
     * Returns validators for PHONE field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validatePhone(): array
    {
        return [
            new LengthValidator(null, 40),
        ];
    }

    /**
     * Returns validators for WORK_SCHEDULE field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validateWorkSchedule(): array
    {
        return [
            new LengthValidator(null, 150),
        ];
    }

    /**
     * Returns validators for LONGITUDE field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validateLongitude(): array
    {
        return [
            new LengthValidator(null, 15),
        ];
    }

    /**
     * Returns validators for LATITUDE field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validateLatitude(): array
    {
        return [
            new LengthValidator(null, 15),
        ];
    }
}
