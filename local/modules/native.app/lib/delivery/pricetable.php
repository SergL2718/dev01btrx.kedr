<?php
/*
 * @updated 18.01.2021, 23:39
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2021, Компания Webco <hello@wbc.cx>
 * @link https://wbc.cx
 */


namespace Native\App\Delivery;

use Bitrix\Main\ArgumentTypeException;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\DatetimeField;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\Validators\LengthValidator;
use Bitrix\Main\SystemException;

/**
 * Class PriceTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> DATE_CREATE datetime optional
 * <li> DELIVERY_CODE string(30) optional
 * <li> DELIVERY_METHOD string(30) optional
 * <li> FROM_ZIP string(8) optional
 * <li> TO_ZIP string(8) optional
 * <li> TO_CITY string(40) optional
 * <li> COUNTRY_CODE string(2) optional
 * <li> COUNTRY_ID string(3) optional
 * <li> WEIGHT string(8) optional
 * <li> DATE_CALCULATE string(8) optional
 * <li> PRICE string(15) optional
 * <li> PRICE_VAT string(15) optional
 * <li> PERIOD_MIN string(5) optional
 * <li> PERIOD_MAX string(5) optional
 * </ul>
 *
 * @package Native\App\Delivery
 **/
class PriceTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName(): string
    {
        return 'app_delivery_price';
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
                'DELIVERY_CODE',
                [
                    'validation' => [__CLASS__, 'validateDeliveryCode'],
                    'title' => 'Код доставки'
                ]
            ),
            new StringField(
                'DELIVERY_METHOD',
                [
                    'validation' => [__CLASS__, 'validateDeliveryMethod'],
                    'title' => 'Метод доставки'
                ]
            ),
            new StringField(
                'FROM_ZIP',
                [
                    'validation' => [__CLASS__, 'validateFromZip'],
                    'title' => 'Индекс отправления'
                ]
            ),
            new StringField(
                'TO_ZIP',
                [
                    'validation' => [__CLASS__, 'validateToZip'],
                    'title' => 'Индекс получателя'
                ]
            ),
            new StringField(
                'TO_CITY',
                [
                    'validation' => [__CLASS__, 'validateToCity'],
                    'title' => 'Город получателя'
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
                'WEIGHT',
                [
                    'validation' => [__CLASS__, 'validateWeight'],
                    'title' => 'Вес'
                ]
            ),
            new StringField(
                'DATE_CALCULATE',
                [
                    'validation' => [__CLASS__, 'validateDateCalculate'],
                    'title' => 'Дата расчета'
                ]
            ),
            new StringField(
                'PRICE',
                [
                    'validation' => [__CLASS__, 'validatePrice'],
                    'title' => 'Цена'
                ]
            ),
            new StringField(
                'PRICE_VAT',
                [
                    'validation' => [__CLASS__, 'validatePriceVat'],
                    'title' => 'Налог'
                ]
            ),
            new StringField(
                'PERIOD_MIN',
                [
                    'validation' => [__CLASS__, 'validatePeriodMin'],
                    'title' => 'Минимальный срок доставки (дней)'
                ]
            ),
            new StringField(
                'PERIOD_MAX',
                [
                    'validation' => [__CLASS__, 'validatePeriodMax'],
                    'title' => 'Максимальный срок доставки (дней)'
                ]
            ),
        ];
    }

    /**
     * Returns validators for DELIVERY_CODE field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validateDeliveryCode(): array
    {
        return [
            new LengthValidator(null, 30),
        ];
    }

    /**
     * Returns validators for DELIVERY_METHOD field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validateDeliveryMethod(): array
    {
        return [
            new LengthValidator(null, 30),
        ];
    }

    /**
     * Returns validators for FROM_ZIP field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validateFromZip(): array
    {
        return [
            new LengthValidator(null, 8),
        ];
    }

    /**
     * Returns validators for TO_ZIP field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validateToZip(): array
    {
        return [
            new LengthValidator(null, 8),
        ];
    }

    /**
     * Returns validators for TO_CITY field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validateToCity(): array
    {
        return [
            new LengthValidator(null, 40),
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
     * Returns validators for WEIGHT field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validateWeight(): array
    {
        return [
            new LengthValidator(null, 8),
        ];
    }

    /**
     * Returns validators for DATE_CALCULATE field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validateDateCalculate(): array
    {
        return [
            new LengthValidator(null, 8),
        ];
    }

    /**
     * Returns validators for PRICE field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validatePrice(): array
    {
        return [
            new LengthValidator(null, 15),
        ];
    }

    /**
     * Returns validators for PRICE_VAT field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validatePriceVat(): array
    {
        return [
            new LengthValidator(null, 15),
        ];
    }

    /**
     * Returns validators for PERIOD_MIN field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validatePeriodMin(): array
    {
        return [
            new LengthValidator(null, 5),
        ];
    }

    /**
     * Returns validators for PERIOD_MAX field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validatePeriodMax(): array
    {
        return [
            new LengthValidator(null, 5),
        ];
    }
}
