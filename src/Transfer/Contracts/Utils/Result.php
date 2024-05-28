<?php

namespace TransferContracts\Utils;

use Exception;

class Result {
    /**
     * Summary of value
     * @var mixed
     */
    private $value;
    /**
     * Summary of error
     * @var ?\Exception
     */
    private $error;
    /**
     * Summary of isSuccess
     * @var bool
     */
    private $isSuccess;

    private function __construct(mixed $value = null, Exception $error = null, bool $isSuccess = true) {
        $this->value = $value;
        $this->error = $error;
        $this->isSuccess = $isSuccess;
    }

    public static function success(mixed $value): self {
        return new Result($value);
    }

    public static function failure(Exception $error): self {
        return new Result(null, $error, false);
    }

    public function isSuccess(): bool {
        return $this->isSuccess;
    }

    public function isFailure(): bool {
        return !$this->isSuccess;
    }

    public function getValue(): mixed {
        if ($this->isFailure()) {
            throw new Exception('Result is not a success');
        }
        return $this->value;
    }

    public function getError(): Exception {
        if ($this->isSuccess() || is_null($this->error)) {
            throw new Exception('Result is not a failure');
        }
        return $this->error;
    }
}
