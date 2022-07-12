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
use Bitrix\Main\ORM\Fields\DatetimeField;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\TextField;
use Bitrix\Main\ORM\Fields\Validators\LengthValidator;
use Bitrix\Main\SystemException;

/**
 * Class ApiTokenTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> DATE_CREATE datetime optional
 * <li> EXPIRE_AT datetime optional
 * <li> JTI string(100) optional
 * <li> SCOPE string(100) optional
 * <li> TOKEN_TYPE string(6) optional
 * <li> ACCESS_TOKEN text mandatory
 * </ul>
 *
 * @package Native\App\Provider\Cdek
 **/
class ApiTokenTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName(): string
    {
        return 'app_cdek_api_token';
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
            new DatetimeField(
                'EXPIRE_AT',
                [
                    'title' => 'Срок годности'
                ]
            ),
            new StringField(
                'JTI',
                [
                    'validation' => [__CLASS__, 'validateJti'],
                    'title' => 'ID токена'
                ]
            ),
            new StringField(
                'SCOPE',
                [
                    'validation' => [__CLASS__, 'validateScope'],
                    'title' => 'Область действий'
                ]
            ),
            new StringField(
                'TOKEN_TYPE',
                [
                    'validation' => [__CLASS__, 'validateTokenType'],
                    'title' => 'Тип токена'
                ]
            ),
            new TextField(
                'ACCESS_TOKEN',
                [
                    'required' => true,
                    'title' => 'Токен'
                ]
            ),
        ];
    }

    /**
     * Returns validators for JTI field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validateJti(): array
    {
        return [
            new LengthValidator(null, 100),
        ];
    }

    /**
     * Returns validators for SCOPE field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validateScope(): array
    {
        return [
            new LengthValidator(null, 100),
        ];
    }

    /**
     * Returns validators for TOKEN_TYPE field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validateTokenType(): array
    {
        return [
            new LengthValidator(null, 6),
        ];
    }
}
