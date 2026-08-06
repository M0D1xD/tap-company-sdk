<?php

declare(strict_types=1);

namespace TapCompany\LaravelSdk\Data;

use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;
use TapCompany\LaravelSdk\Enums\PaymentSourceId;

/**
 * Typed builder for the charge/authorization `source` object.
 *
 * Drop an instance straight into a payload's `source` key — it implements
 * Arrayable/JsonSerializable so it serializes exactly like the plain array
 * it replaces, e.g. `'source' => PaymentSource::knet()`.
 *
 * @implements Arrayable<string, mixed>
 */
final class PaymentSource implements Arrayable, JsonSerializable
{
    private function __construct(
        private readonly ?string $id = null,
        private readonly ?bool $onFile = null,
        private readonly ?string $card = null,
    ) {
    }

    /**
     * Any source id known to Tap: a {@see PaymentSourceId}, a raw `src_*` string,
     * a token id (`tok_...`), or a saved card id (`card_...`).
     */
    public static function of(PaymentSourceId|string $id): self
    {
        return new self($id instanceof PaymentSourceId ? $id->value : $id);
    }

    public static function card(): self
    {
        return self::of(PaymentSourceId::CARD);
    }

    public static function all(): self
    {
        return self::of(PaymentSourceId::ALL);
    }

    public static function applePay(): self
    {
        return self::of(PaymentSourceId::APPLE_PAY);
    }

    public static function googlePay(): self
    {
        return self::of(PaymentSourceId::GOOGLE_PAY);
    }

    public static function samsungPay(): self
    {
        return self::of(PaymentSourceId::SAMSUNG_PAY);
    }

    public static function knet(): self
    {
        return self::of(PaymentSourceId::KNET);
    }

    public static function mada(): self
    {
        return self::of(PaymentSourceId::MADA);
    }

    public static function stcPay(): self
    {
        return self::of(PaymentSourceId::STC_PAY);
    }

    public static function fawry(): self
    {
        return self::of(PaymentSourceId::FAWRY);
    }

    public static function benefit(): self
    {
        return self::of(PaymentSourceId::BENEFIT);
    }

    public static function benefitPay(): self
    {
        return self::of(PaymentSourceId::BENEFIT_PAY);
    }

    public static function qpay(): self
    {
        return self::of(PaymentSourceId::QPAY);
    }

    public static function omannet(): self
    {
        return self::of(PaymentSourceId::OMANNET);
    }

    public static function tabbyInstallment(): self
    {
        return self::of(PaymentSourceId::TABBY_INSTALLMENT);
    }

    /**
     * A previously created token (from Tokens::create or a client-side token).
     */
    public static function token(string $tokenId): self
    {
        return self::of($tokenId);
    }

    /**
     * A previously saved card id, e.g. from `save_card` on an earlier charge.
     */
    public static function savedCard(string $cardId, bool $onFile = true): self
    {
        return new self($cardId, $onFile);
    }

    /**
     * PCI-scope flow: an already client-side-encrypted card payload.
     */
    public static function encryptedCard(string $encryptedCard, bool $onFile = false): self
    {
        return new self(card: $encryptedCard, onFile: $onFile);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = [];

        if ($this->id !== null) {
            $data['id'] = $this->id;
        }

        if ($this->card !== null) {
            $data['card'] = $this->card;
        }

        if ($this->onFile !== null) {
            $data['on_file'] = $this->onFile;
        }

        return $data;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
