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

use YooKassa\Common\AbstractEnum;

/**
 * Класс, представляющий модель CancellationDetailsPartyCode.
 *
 * Возможные инициаторы отмены возврата:
 * - `yoo_money` - ЮKassa
 * - `refund_network` - Любые участники процесса возврата, кроме ЮKassa и вас
 *
 * @category Class
 * @package  YooKassa\Model
 * @author   cms@yoomoney.ru
 * @link     https://yookassa.ru/developers/api
 */
class RefundCancellationDetailsPartyCode extends AbstractEnum
{
    /** ЮKassa */
    const YOO_MONEY = 'yoo_money';

    /**
     * @deprecated Устарел. Оставлен для обратной совместимости
     */
    const YANDEX_CHECKOUT = 'yandex_checkout';

    /** Любые участники процесса возврата, кроме ЮKassa и вас (например, эмитент банковской карты) */
    const REFUND_NETWORK = 'refund_network';

    protected static $validValues = array(
        self::YOO_MONEY => true,
        self::YANDEX_CHECKOUT => false,
        self::REFUND_NETWORK => true,
    );
}
