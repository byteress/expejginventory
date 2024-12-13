<?php

namespace ExpenseManagementContracts\Exceptions;

class InvalidDomainException extends \Exception
{
    /**
     * Summary of errors
     * @var array<string, string>
     */
    private array $errors;
    /**
     * Summary of data
     * @var array<string, mixed>
     */
    private ?array $data;

    /**
     * Summary of __construct
     * @param string $message
     * @param array<string, string> $errors
     * @param int $code
     * @param null|array<string, mixed> $data
     * @param \Exception|null $previous
     */
    public function __construct(
        string $message,
        array $errors,
        int $code = 0,
        ?array $data = null,
        \Exception $previous = null,
    ) {
        parent::__construct($message, $code, $previous);

        $this->errors = $errors;
        $this->data = $data;
    }

    /**
     * Summary of getErrors
     * @return array<string, string>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Summary of getData
     * @return null|array<string, mixed>
     */
    public function getData(): ?array
    {
        return $this->data;
    }
}
