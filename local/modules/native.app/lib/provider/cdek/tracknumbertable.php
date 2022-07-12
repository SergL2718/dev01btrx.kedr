<?php
/*
 * @updated 04.02.2021, 14:17
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
use Bitrix\Main\ORM\Fields\Validators\LengthValidator;
use Bitrix\Main\SystemException;

/**
 * Class TrackNumberTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> DATE_CREATE datetime optional
 * <li> ORDER_ID string(15) optional
 * <li> SHIPMENT_ID string(15) optional
 * <li> TRACK_NUMBER string(20) optional
 * </ul>
 *
 * @package Native\App\Provider\Cdek
 **/
class TrackNumberTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName(): string
    {
        return 'app_cdek_track_number';
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
                    'title' => 'DATE_CREATE'
                ]
            ),
            new StringField(
                'ORDER_ID',
                [
                    'validation' => [__CLASS__, 'validateOrderId'],
                    'title' => 'ORDER_ID'
                ]
            ),
            new StringField(
                'SHIPMENT_ID',
                [
                    'validation' => [__CLASS__, 'validateShipmentId'],
                    'title' => 'SHIPMENT_ID'
                ]
            ),
            new StringField(
                'TRACK_NUMBER',
                [
                    'validation' => [__CLASS__, 'validateTrackNumber'],
                    'title' => 'TRACK_NUMBER'
                ]
            ),
        ];
    }

    /**
     * Returns validators for ORDER_ID field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validateOrderId(): array
    {
        return [
            new LengthValidator(null, 15),
        ];
    }

    /**
     * Returns validators for SHIPMENT_ID field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validateShipmentId(): array
    {
        return [
            new LengthValidator(null, 15),
        ];
    }

    /**
     * Returns validators for TRACK_NUMBER field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validateTrackNumber(): array
    {
        return [
            new LengthValidator(null, 20),
        ];
    }
}
