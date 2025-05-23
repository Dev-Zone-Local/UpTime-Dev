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

namespace YooKassa\Model\Deal;

use YooKassa\Common\AbstractObject;
use YooKassa\Common\Exceptions\EmptyPropertyValueException;
use YooKassa\Common\Exceptions\InvalidPropertyValueException;
use YooKassa\Common\Exceptions\InvalidPropertyValueTypeException;
use YooKassa\Helpers\TypeCast;
use YooKassa\Model\AmountInterface;
use YooKassa\Model\MonetaryAmount;
use YooKassa\Model\SettlementInterface;

/**
 * Class SettlementPayoutRefund
 * @package YooKassa
 *
 * @property string $type Вид оплаты в чеке
 * @property AmountInterface $amount Размер оплаты
 */
class SettlementPayoutRefund extends AbstractObject implements SettlementInterface
{
    /**
     * @var string Тип операции (payout - выплата продавцу)
     */
    private $_type;

    /**
     * @var AmountInterface Сумма, на которую необходимо уменьшить вознаграждение продавца.
     */
    private $_amount;

    /**
     * Возвращает тип операции (payout - выплата продавцу)
     * @return string Тип операции
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * Устанавливает вид оплаты в чеке
     * @param string $value
     */
    public function setType($value)
    {
        if ($value === null || $value === '') {
            throw new EmptyPropertyValueException(
                'Empty value for "type" parameter in Settlement',
                0,
                'settlement.type'
            );
        } elseif (TypeCast::canCastToEnumString($value)) {
            if (SettlementPayoutPaymentType::valueExists($value)) {
                $this->_type = (string)$value;
            } else {
                throw new InvalidPropertyValueException(
                    'Invalid value for "type" parameter in Settlement',
                    0,
                    'settlement.type',
                    $value
                );
            }
        } else {
            throw new InvalidPropertyValueTypeException(
                'Invalid value type for "type" parameter in Settlement',
                0,
                'settlement.type',
                $value
            );
        }
    }

    /**
     * Возвращает размер оплаты
     * @return AmountInterface Размер оплаты
     */
    public function getAmount()
    {
        return $this->_amount;
    }

    /**
     * Устанавливает сумму, на которую необходимо уменьшить вознаграждение продавца.
     * Должна быть меньше суммы возврата или равна ей.
     * @param AmountInterface|array $value Сумма, на которую необходимо уменьшить вознаграждение продавца
     */
    public function setAmount($value)
    {
        if ($value === null || $value === '') {
            throw new EmptyPropertyValueException(
                'Empty value for "amount" parameter in Settlement',
                0,
                'settlement.amount'
            );
        } elseif (is_array($value)) {
            $this->_amount = $this->factoryAmount($value);
        } elseif ($value instanceof AmountInterface) {
            $this->_amount = $value;
        } else {
            throw new InvalidPropertyValueTypeException(
                'Invalid value type for "amount" parameter in Settlement',
                0,
                'settlement.amount',
                $value
            );
        }
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
