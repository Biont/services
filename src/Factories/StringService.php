<?php

declare(strict_types=1);

namespace Dhii\Services\Factories;

use Dhii\Services\Service;
use Psr\Container\ContainerInterface;

/**
 * A factory for string values. Supports interpolation with dependent service values.
 *
 * Example usage:
 *  ```
 *  [
 *      'service_a' => new FormatStr('John Smith'),
 *      'service_b' => new FormatStr('User name is: {0}', ['service_a']),
 *      'service_c' => new FormatStr('{day} {month}', [
 *          'day'   => 'date/day',
 *          'month' => 'date/month',
 *      ]),
 *  ]
 *  ```
 *
 * @since [*next-version*]
 */
class StringService extends Service
{
    /**
     * @since [*next-version*]
     *
     * @var string
     */
    protected $format;

    /**
     * @inheritDoc
     *
     * @since [*next-version*]
     *
     * @param string $format The format string. Substrings wrapped in curly braces will be interpolated with the
     *                       string value of the resolved dependency at the index indicated by that substring. The index
     *                       may be either numerical (for positional dependency arrays), or a string (for associative
     *                       dependency arrays).
     */
    public function __construct(string $format, array $dependencies = [])
    {
        parent::__construct($dependencies);

        $this->format = $format;
    }

    /**
     * @inheritDoc
     *
     * @since [*next-version*]
     */
    public function __invoke(ContainerInterface $c)
    {
        if (empty($this->dependencies)) {
            return $this->format;
        }

        $replace = [];
        foreach ($this->dependencies as $idx => $dependency) {
            $replace['{' . $idx . '}'] = strval($c->get($dependency));
        }

        return strtr($this->format, $replace);
    }
}
