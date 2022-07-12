<?php
/*
 * @updated 18.01.2021, 22:44
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2021, Компания Webco <hello@wbc.cx>
 * @link https://wbc.cx
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
 * Class PostalCodeTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> POSTAL_CODE string(15) optional
 * <li> COUNTRY_CODE string(2) optional
 * <li> LOCATION_ID int optional
 * </ul>
 *
 * @package Native\App\Provider\Cdek
 **/
class PostalCodeTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName(): string
    {
        return 'app_cdek_postal_code';
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
                'POSTAL_CODE',
                [
                    'validation' => [__CLASS__, 'validatePostalCode'],
                    'title' => 'POSTAL_CODE'
                ]
            ),
            new StringField(
                'COUNTRY_CODE',
                [
                    'validation' => [__CLASS__, 'validateCountryCode'],
                    'title' => 'COUNTRY_CODE'
                ]
            ),
            new FloatField(
                'LOCATION_ID',
                [
                    'title' => 'LOCATION_ID'
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
}
