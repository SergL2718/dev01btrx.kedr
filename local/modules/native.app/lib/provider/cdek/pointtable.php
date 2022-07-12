<?php
/*
 * @updated 31.01.2021, 20:29
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2021, Компания Webco <hello@wbc.cx>
 * @link https://wbc.cx
 */


namespace Native\App\Provider\Cdek;

use Bitrix\Main\ArgumentTypeException;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\BooleanField;
use Bitrix\Main\ORM\Fields\FloatField;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\TextField;
use Bitrix\Main\ORM\Fields\Validators\LengthValidator;
use Bitrix\Main\SystemException;


/**
 * Class PointTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> CODE string(15) optional
 * <li> NAME string(255) optional
 * <li> COUNTRY_CODE string(2) optional
 * <li> POSTAL_CODE string(15) optional
 * <li> CITY_CODE string(50) optional
 * <li> CITY string(255) optional
 * <li> CITY_SEARCH string(255) optional
 * <li> ADDRESS string(255) optional
 * <li> ADDRESS_FULL string(255) optional
 * <li> LONGITUDE double optional
 * <li> LATITUDE double optional
 * <li> TYPE string(255) optional
 * <li> OWNER_CODE string(255) optional
 * <li> SCHEDULE string(255) optional
 * <li> NEAREST_STATION string(255) optional
 * <li> TAKE_ONLY bool ('N', 'Y') optional default 'N'
 * <li> IS_HANDOUT bool ('N', 'Y') optional default 'N'
 * <li> IS_DRESSING_ROOM bool ('N', 'Y') optional default 'N'
 * <li> HAVE_CASHLESS bool ('N', 'Y') optional default 'N'
 * <li> HAVE_CASH bool ('N', 'Y') optional default 'N'
 * <li> ALLOWED_COD bool ('N', 'Y') optional default 'N'
 * <li> EMAIL string(100) optional
 * <li> PHONES text optional
 * <li> WORK_TIME text optional
 * <li> IMAGES text optional
 * <li> NOTE text optional
 * <li> ADDRESS_COMMENT text optional
 * <li> WEIGHT_MAX int optional
 * <li> DIMENSION_WIDTH int optional
 * <li> DIMENSION_HEIGHT int optional
 * <li> DIMENSION_DEPTH int optional
 * </ul>
 *
 * @package Native\App\Provider\Cdek
 **/
class PointTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName(): string
    {
        return 'app_cdek_point';
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
            new StringField(
                'CODE',
                [
                    'validation' => [__CLASS__, 'validateCode'],
                    'title' => 'CODE'
                ]
            ),
            new StringField(
                'NAME',
                [
                    'validation' => [__CLASS__, 'validateName'],
                    'title' => 'NAME'
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
                'POSTAL_CODE',
                [
                    'validation' => [__CLASS__, 'validatePostalCode'],
                    'title' => 'POSTAL_CODE'
                ]
            ),
            new StringField(
                'CITY_CODE',
                [
                    'validation' => [__CLASS__, 'validateCityCode'],
                    'title' => 'CITY_CODE'
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
                'CITY_SEARCH',
                [
                    'validation' => [__CLASS__, 'validateCitySearch'],
                    'title' => 'CITY_SEARCH'
                ]
            ),
            new StringField(
                'ADDRESS',
                [
                    'validation' => [__CLASS__, 'validateAddress'],
                    'title' => 'ADDRESS'
                ]
            ),
            new StringField(
                'ADDRESS_FULL',
                [
                    'validation' => [__CLASS__, 'validateAddressFull'],
                    'title' => 'ADDRESS_FULL'
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
                'TYPE',
                [
                    'validation' => [__CLASS__, 'validateType'],
                    'title' => 'TYPE'
                ]
            ),
            new StringField(
                'OWNER_CODE',
                [
                    'validation' => [__CLASS__, 'validateOwnerCode'],
                    'title' => 'OWNER_CODE'
                ]
            ),
            new StringField(
                'SCHEDULE',
                [
                    'validation' => [__CLASS__, 'validateSchedule'],
                    'title' => 'SCHEDULE'
                ]
            ),
            new StringField(
                'NEAREST_STATION',
                [
                    'validation' => [__CLASS__, 'validateNearestStation'],
                    'title' => 'NEAREST_STATION'
                ]
            ),
            new BooleanField(
                'TAKE_ONLY',
                [
                    'values' => ['N', 'Y'],
                    'default' => 'N',
                    'title' => 'TAKE_ONLY'
                ]
            ),
            new BooleanField(
                'IS_HANDOUT',
                [
                    'values' => ['N', 'Y'],
                    'default' => 'N',
                    'title' => 'IS_HANDOUT'
                ]
            ),
            new BooleanField(
                'IS_DRESSING_ROOM',
                [
                    'values' => ['N', 'Y'],
                    'default' => 'N',
                    'title' => 'IS_DRESSING_ROOM'
                ]
            ),
            new BooleanField(
                'HAVE_CASHLESS',
                [
                    'values' => ['N', 'Y'],
                    'default' => 'N',
                    'title' => 'HAVE_CASHLESS'
                ]
            ),
            new BooleanField(
                'HAVE_CASH',
                [
                    'values' => ['N', 'Y'],
                    'default' => 'N',
                    'title' => 'HAVE_CASH'
                ]
            ),
            new BooleanField(
                'ALLOWED_COD',
                [
                    'values' => ['N', 'Y'],
                    'default' => 'N',
                    'title' => 'ALLOWED_COD'
                ]
            ),
            new StringField(
                'EMAIL',
                [
                    'validation' => [__CLASS__, 'validateEmail'],
                    'title' => 'EMAIL'
                ]
            ),
            new TextField(
                'PHONES',
                [
                    'title' => 'PHONES'
                ]
            ),
            new TextField(
                'WORK_TIME',
                [
                    'title' => 'WORK_TIME'
                ]
            ),
            new TextField(
                'IMAGES',
                [
                    'title' => 'IMAGES'
                ]
            ),
            new TextField(
                'NOTE',
                [
                    'title' => 'NOTE'
                ]
            ),
            new TextField(
                'ADDRESS_COMMENT',
                [
                    'title' => 'ADDRESS_COMMENT'
                ]
            ),
            new IntegerField(
                'WEIGHT_MAX',
                [
                    'title' => 'WEIGHT_MAX'
                ]
            ),
            new IntegerField(
                'DIMENSION_WIDTH',
                [
                    'title' => 'DIMENSION_WIDTH'
                ]
            ),
            new IntegerField(
                'DIMENSION_HEIGHT',
                [
                    'title' => 'DIMENSION_HEIGHT'
                ]
            ),
            new IntegerField(
                'DIMENSION_DEPTH',
                [
                    'title' => 'DIMENSION_DEPTH'
                ]
            ),
        ];
    }

    /**
     * Returns validators for CODE field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validateCode(): array
    {
        return [
            new LengthValidator(null, 15),
        ];
    }

    /**
     * Returns validators for NAME field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validateName(): array
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
     * Returns validators for CITY_CODE field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validateCityCode(): array
    {
        return [
            new LengthValidator(null, 50),
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
     * Returns validators for CITY_SEARCH field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validateCitySearch(): array
    {
        return [
            new LengthValidator(null, 255),
        ];
    }

    /**
     * Returns validators for ADDRESS field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validateAddress(): array
    {
        return [
            new LengthValidator(null, 255),
        ];
    }

    /**
     * Returns validators for ADDRESS_FULL field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validateAddressFull(): array
    {
        return [
            new LengthValidator(null, 255),
        ];
    }

    /**
     * Returns validators for TYPE field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validateType(): array
    {
        return [
            new LengthValidator(null, 255),
        ];
    }

    /**
     * Returns validators for OWNER_CODE field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validateOwnerCode(): array
    {
        return [
            new LengthValidator(null, 255),
        ];
    }

    /**
     * Returns validators for SCHEDULE field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validateSchedule(): array
    {
        return [
            new LengthValidator(null, 255),
        ];
    }

    /**
     * Returns validators for NEAREST_STATION field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validateNearestStation(): array
    {
        return [
            new LengthValidator(null, 255),
        ];
    }

    /**
     * Returns validators for EMAIL field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validateEmail(): array
    {
        return [
            new LengthValidator(null, 100),
        ];
    }
}
