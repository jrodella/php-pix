<?php

namespace Piggly\Pix\Exceptions;

use Exception;

/**
 * Exception when pix key type is invalid.
 *
 * @package \Piggly\Pix
 * @subpackage \Piggly\Pix\Exceptions
 * @version 1.2.0
 * @since 1.2.0
 * @category Exception
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 * @license MIT
 * @copyright 2021 Piggly Lab <dev@piggly.com.br>
 */
class InvalidPixKeyTypeException extends Exception
{
    /**
     * @since 1.2.0
     * @var string $pixKeyType
     */
    protected $pixKeyType;

    /**
     * Get pix key.
     * @since 1.2.0
     * @var string $pixKeyType
     */
    public function getPixKeyType(): string
    {
        return $this->pixKeyType;
    }

    /**
     * Exception when the Pix Key Type is invalid.
     *
     * @since 1.2.0
     * @param string $keyType
     */
    public function __construct(string $keyType)
    {
        $this->pixKeyType = $keyType;

        parent::__construct(
            \sprintf('O tipo de chave `%s` é desconhecido.', $keyType)
        );
    }
}
