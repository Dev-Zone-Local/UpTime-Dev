<?php

/*
 * The MIT License
 *
 * Copyright (c) 2025 "YooMoney", NBСO LLC
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace YooKassa\Model;

use YooKassa\Common\AbstractObject;
use YooKassa\Common\Exceptions\EmptyPropertyValueException;
use YooKassa\Common\Exceptions\InvalidPropertyValueException;
use YooKassa\Common\Exceptions\InvalidPropertyValueTypeException;
use YooKassa\Helpers\TypeCast;

/**
 * Класс объекта распределения денег в магазин
 *
 * Данные о распределении денег — сколько и в какой магазин нужно перевести.
 * Присутствует, если вы используете решение ЮKassa для платформ.
 *
 * @property AmountInterface $amount Сумма, которую необходимо перечислить магазину
 * @property AmountInterface $platform_fee_amount Комиссия за проданные товары и услуги, которая удерживается с магазина в вашу пользу
 * @property string $accountId Идентификатор магазина, в пользу которого вы принимаете оплату
 * @property string $status Статус распределения денег между магазинами. Возможные значения: `pending`, `waiting_for_capture`, `succeeded`, `canceled`
 * @property string $description Описание транзакции, которое продавец увидит в личном кабинете ЮKassa. (например: «Заказ маркетплейса №72»)
 * @property Metadata $metadata Любые дополнительные данные, которые нужны вам для работы с платежами (например, ваш внутренний идентификатор заказа)
 *
 * @package YooKassa
 */
class Transfer extends AbstractObject implements TransferInterface
{
    /** Максимальная длина строки описания транзакции */
    const MAX_LENGTH_DESCRIPTION = 128;

    /**
     * @var string Идентификатор магазина, в пользу которого вы принимаете оплату. Выдается ЮKassa, отображается в разделе Продавцы личного кабинета (столбец shopId).
     */
    private $_accountId;

    /**
     * @var AmountInterface Сумма, которую необходимо перечислить магазину.
     */
    private $_amount;

    /**
     * @var AmountInterface Комиссия за проданные товары и услуги, которая удерживается с магазина в вашу пользу.
     */
    private $_platform_fee_amount;

    /**
     * @var string Статус распределения денег между магазинами. Возможные значения: pending, waiting_for_capture, succeeded, canceled.
     */
    private $_status;

    /**
     * @var string Описание транзакции (не более 128 символов), которое продавец увидит в личном кабинете ЮKassa. Например: «Заказ маркетплейса №72».
     */
    private $_description;

    /**
     * @var string Любые дополнительные данные, которые нужны вам для работы (например, ваш внутренний идентификатор заказа).
     */
    private $_metadata;

    /**
     * @inheritDoc
     */
    public function setAccountId($value)
    {
        if ($value === null || $value === '') {
            throw new EmptyPropertyValueException(
                'Empty value for "accountId" parameter in Transfer',
                0,
                'transfer.accountId'
            );
        } elseif (!TypeCast::canCastToString($value)) {
            throw new InvalidPropertyValueTypeException(
                'Invalid value type for "accountId" parameter in Transfer',
                0,
                'transfer.accountId'
            );
        } else {
            $this->_accountId = (string)$value;
        }
    }

    /**
     * @inheritDoc
     */
    public function getAccountId()
    {
        return $this->_accountId;
    }

    /**
     * @inheritDoc
     */
    public function getAmount()
    {
        return $this->_amount;
    }

    /**
     * @inheritDoc
     */
    public function hasAmount()
    {
        return !empty($this->_amount);
    }

    /**
     * @inheritDoc
     */
    public function setAmount($value)
    {
        if ($value === null || $value === '') {
            throw new EmptyPropertyValueException(
                'Empty value for "amount" parameter in Transfer',
                0,
                'transfer.amount'
            );
        } elseif (is_array($value)) {
            $this->_amount = $this->factoryAmount($value);
        } elseif ($value instanceof AmountInterface) {
            $this->_amount = $value;
        } else {
            throw new InvalidPropertyValueTypeException(
                'Invalid value type for "amount" parameter in Transfer',
                0,
                'transfer.amount',
                $value
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function getPlatformFeeAmount()
    {
        return $this->_platform_fee_amount;
    }

    /**
     * @inheritDoc
     */
    public function hasPlatformFeeAmount()
    {
        return !empty($this->_platform_fee_amount);
    }

    /**
     * @inheritDoc
     */
    public function setPlatformFeeAmount($value)
    {
        if ($value === null || $value === '') {
            throw new EmptyPropertyValueException(
                'Empty value for "platform_fee_amount" parameter in Transfer',
                0,
                'transfer.platform_fee_amount'
            );
        } elseif (is_array($value)) {
            $this->_platform_fee_amount = $this->factoryAmount($value);
        } elseif ($value instanceof AmountInterface) {
            $this->_platform_fee_amount = $value;
        } else {
            throw new InvalidPropertyValueTypeException(
                'Invalid value type for "platform_fee_amount" parameter in Transfer',
                0,
                'transfer.platform_fee_amount',
                $value
            );
        }
    }

    /**
     * @param $value
     */
    public function setStatus($value)
    {
        if (!TypeCast::canCastToEnumString($value)) {
            throw new InvalidPropertyValueTypeException(
                'Invalid "status" value type',
                0,
                'transfer.status',
                $value
            );
        } elseif (!TransferStatus::valueExists((string)$value)) {
            throw new InvalidPropertyValueException(
                'Invalid "status" value',
                0,
                'transfer.status',
                $value
            );
        } else {
            $this->_status = (string)$value;
        }
    }

    /**
     * @inheritDoc
     */
    public function getStatus()
    {
        return $this->_status;
    }

    /**
     * @inheritDoc
     */
    public function getDescription()
    {
        return $this->_description;
    }

    /**
     * @inheritDoc
     */
    public function setDescription($value)
    {
        if ($value === null || $value === '') {
            $this->_description = null;
            return;
        }

        if (!TypeCast::canCastToString($value)) {
            throw new InvalidPropertyValueTypeException(
                'Invalid description value type',
                0,
                'Transfer.description',
                $value
            );
        }

        $length = mb_strlen((string)$value, 'utf-8');
        if ($length > self::MAX_LENGTH_DESCRIPTION) {
            throw new InvalidPropertyValueException(
                'The value of the description parameter is too long. Max length is ' . self::MAX_LENGTH_DESCRIPTION,
                0,
                'Transfer.description',
                $value
            );
        }
        $this->_description = (string)$value;
    }

    /**
     * @inheritDoc
     */
    public function hasDescription()
    {
        return !empty($this->_description);
    }

    /**
     * @inheritDoc
     */
    public function setMetadata($value)
    {
        if ($value === null || $value === '') {
            $this->_metadata = null;
        } elseif (is_array($value)) {
            $this->_metadata = new Metadata($value);
        } elseif ($value instanceof Metadata) {
            $this->_metadata = $value;
        } else {
            throw new InvalidPropertyValueTypeException(
                'Invalid value type for "metadata" parameter in Transfer',
                0,
                'transfer.metadata',
                $value
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function getMetadata()
    {
        return $this->_metadata;
    }

    /**
     * @inheritDoc
     */
    public function hasMetadata()
    {
        return !empty($this->_metadata);
    }

    /**
     * Фабричный метод создания суммы
     *
     * @param array $options Сумма в виде ассоциативного массива
     *
     * @return AmountInterface Созданный инстанс суммы
     */
    private function factoryAmount($options)
    {
        $amount = new MonetaryAmount(null, $options['currency']);
        if ($options['value'] > 0) {
            $amount->setValue($options['value']);
        }

        return $amount;
    }
}
