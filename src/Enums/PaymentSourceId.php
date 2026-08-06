<?php

declare(strict_types=1);

namespace TapCompany\LaravelSdk\Enums;

/**
 * Known `source.id` values accepted by the Tap Charges API.
 *
 * @see https://developers.tap.company/reference/charges.md
 */
enum PaymentSourceId: string
{
    /** Card-only payment on Tap's hosted payment page. */
    case CARD = 'src_card';

    /** All payment methods enabled on the merchant account, shown on Tap's hosted page. */
    case ALL = 'src_all';

    /** Apple Pay. */
    case APPLE_PAY = 'src_apple_pay';

    /** Google Pay. */
    case GOOGLE_PAY = 'src_google_pay';

    /** Samsung Pay. */
    case SAMSUNG_PAY = 'src_samsung_pay';

    /** KNET — Kuwait local payment switch. */
    case KNET = 'src_kw.knet';

    /** mada — Saudi Arabia local payment switch. */
    case MADA = 'src_sa.mada';

    /** STC Pay — Saudi Arabia. */
    case STC_PAY = 'src_sa.stcpay';

    /** Fawry — Egypt local payment method. */
    case FAWRY = 'src_eg.fawry';

    /** Benefit — Bahrain local payment switch. */
    case BENEFIT = 'src_bh.benefit';

    /** BenefitPay — Bahrain (QR / app based). */
    case BENEFIT_PAY = 'src_benefitpay';

    /** NAPS / QPay — Qatar local payment switch. */
    case QPAY = 'src_qa.qpay';

    /** OmanNet — Oman local payment switch. */
    case OMANNET = 'src_omannet';

    /** Tabby — pay in 4 installments. */
    case TABBY_INSTALLMENT = 'src_tabby.installement';

    /**
     * Short, human-readable label for the source (for logs/UI, not sent to Tap).
     */
    public function label(): string
    {
        return match ($this) {
            self::CARD => 'Card',
            self::ALL => 'All payment methods',
            self::APPLE_PAY => 'Apple Pay',
            self::GOOGLE_PAY => 'Google Pay',
            self::SAMSUNG_PAY => 'Samsung Pay',
            self::KNET => 'KNET',
            self::MADA => 'mada',
            self::STC_PAY => 'STC Pay',
            self::FAWRY => 'Fawry',
            self::BENEFIT => 'Benefit',
            self::BENEFIT_PAY => 'BenefitPay',
            self::QPAY => 'QPay',
            self::OMANNET => 'OmanNet',
            self::TABBY_INSTALLMENT => 'Tabby (4 installments)',
        };
    }
}
