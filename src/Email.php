<?php

declare(strict_types=1);

namespace Postboy\Email;

use InvalidArgumentException;
use Postboy\Email\Exception\InvalidEmailException;

class Email
{
    private string $address;
    private ?string $name;

    final public static function createFromString(string $string): self
    {
        preg_match('/^(?<name>.*)<(?<email>[^>]+)>$/ui', trim($string), $matches);
        if (empty($matches)) {
            return new self($string, null);
        }
        if (!array_key_exists('email', $matches)) {
            throw new InvalidArgumentException(sprintf('%s is not a email header', $string));
        }
        $name = null;
        if (array_key_exists('name', $matches)) {
            $name = trim($matches['name']);
            if (substr($name, 0, 1) === '"' && substr($name, -1, 1) === '"') {
                $name = trim(substr($name, 1, -1));
            }
            if ($name === '') {
                $name = null;
            }
        }
        $email = $matches['email'];
        return new static($email, $name);
    }

    final public function __construct(string $address, ?string $name = null)
    {
        $this->checkEmail(trim($address));
        $this->address = $address;
        if (!is_null($name)) {
            $name = preg_replace('/[^\pL\s,.\d]/ui', '', $name);
            $name = preg_replace('/\s/ui', ' ', $name);
            $name = trim($name);
            if ($name === '') {
                $name = null;
            }
        }
        $this->name = $name;
    }

    final public function getAddress(): string
    {
        return $this->address;
    }

    final public function getName(): ?string
    {
        return $this->name;
    }

    final public function __toString(): string
    {
        if (is_null($this->name)) {
            return sprintf('<%s>', $this->address);
        }
        $name = $this->name;
        if (strpos($name, ' ') !== false) {
            $name = '"' . $name . '"';
        }
        return $name . ' ' . sprintf('<%s>', $this->address);
    }

    private function checkEmail($email)
    {
        $arr = explode('@', $email);
        if (count($arr) !== 2) {
            throw new InvalidEmailException($email);
        }
        [$u, $h] = $arr;
        if (empty($u) || empty($h)) {
            throw new InvalidEmailException($email);
        }
        if (strpos($h, ' ') !== false) {
            throw new InvalidEmailException($email);
        }
        $user = trim($u);
        $host = trim($h);
        if ($user !== $u) {
            throw new InvalidEmailException($email);
        }
        if ($host !== $h) {
            throw new InvalidEmailException($email);
        }
    }
}
