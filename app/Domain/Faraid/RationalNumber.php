<?php

namespace App\Domain\Faraid;

/**
 * Bilangan rasional immutabel (pecahan eksak, tanpa floating point).
 *
 * Ini fondasi seluruh mesin faraid: setiap bagian warisan direpresentasikan
 * sebagai pasangan (numerator, denominator) bilangan bulat sehingga hasil
 * `1/3` selalu eksak `1/3`, tidak seperti `0.3333...`.
 */
final class RationalNumber implements \Stringable
{
    private function __construct(
        public readonly int $numerator,
        public readonly int $denominator
    ) {
        if ($this->denominator === 0) {
            throw new \DomainException('Penyebut pecahan tidak boleh nol.');
        }

        // Normalisasi tanda: penyebut selalu positif.
        if ($this->denominator < 0) {
            $this->numerator = -$this->numerator;
            $this->denominator = -$this->denominator;
        }
    }

    public static function of(int $numerator, int $denominator = 1): self
    {
        return new self($numerator, $denominator);
    }

    /** Membuat dari teks pecahan, mis. "1/8" atau "2" */
    public static function fromText(string $text): self
    {
        $text = trim($text);

        if (str_contains($text, '/')) {
            [$n, $d] = explode('/', $text, 2);

            return self::of((int) trim($n), (int) trim($d));
        }

        return self::of((int) $text, 1);
    }

    public function add(self $other): self
    {
        return new self(
            $this->numerator * $other->denominator + $other->numerator * $this->denominator,
            $this->denominator * $other->denominator
        );
    }

    public function subtract(self $other): self
    {
        return new self(
            $this->numerator * $other->denominator - $other->numerator * $this->denominator,
            $this->denominator * $other->denominator
        );
    }

    public function multiply(self $other): self
    {
        return new self($this->numerator * $other->numerator, $this->denominator * $other->denominator);
    }

    public function divide(self $other): self
    {
        if ($other->isZero()) {
            throw new \DomainException('Pembagian dengan nol tidak diperbolehkan.');
        }

        return new self($this->numerator * $other->denominator, $this->denominator * $other->numerator);
    }

    public function subtractFromOne(): self
    {
        return new self($this->denominator - $this->numerator, $this->denominator);
    }

    public function isZero(): bool
    {
        return $this->numerator === 0;
    }

    public function isNegative(): bool
    {
        return $this->numerator < 0;
    }

    public function equals(self $other): bool
    {
        return $this->numerator === $other->numerator && $this->denominator === $other->denominator;
    }

    public function greaterThan(self $other): bool
    {
        return $this->numerator * $other->denominator > $other->numerator * $this->denominator;
    }

    /** Penyederhanaan dengan FPB (gcd) */
    public function simplify(): self
    {
        $gcd = $this->gcd($this->numerator, $this->denominator);

        return new self($this->numerator / $gcd, $this->denominator / $gcd);
    }

    private function gcd(int $a, int $b): int
    {
        $a = abs($a);
        $b = abs($b);

        return $b === 0 ? $a : $this->gcd($b, $a % $b);
    }

    /** Tampilan teks pecahan, mis. "1/8" atau "2/3"; bulat tanpa penyebut. */
    public function toText(): string
    {
        $simplified = $this->simplify();

        return $simplified->denominator === 1
            ? (string) $simplified->numerator
            : $simplified->numerator.'/'.$simplified->denominator;
    }

    /** Nilai desimal pembulatan untuk tampilan estimasi uang (bukan untuk hitungan domain). */
    public function toFloat(): float
    {
        return $this->numerator / $this->denominator;
    }

    public function __toString(): string
    {
        return $this->toText();
    }
}
