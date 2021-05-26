<?php declare(strict_types=1);

namespace Sergonie\Application;

use Sergonie\Application\Exception\ConfigException;

/**
 * Application's config container.
 * Treats dots as an operator for accessing nested values.
 * If constant name is put in curly braces as a value, it wil be replaced
 * to the constant value.
 *
 * @example:
 * // Example usage.
 * $config = new Config();
 * $config->set('some.key', true);
 * $some = $config->get('some'); // returns ['key' => true]
 *
 * @package Sergonie\Application
 */
class Config
{
    private array $config;

    /**
     * Config constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * Checks if config key exists.
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return $this->lookup($key) !== null;
    }

    /**
     * @todo: optimize me
     * @param  string  $key
     *
     * @return array|mixed|null
     */
    private function lookup(string $key)
    {
        $result = $this->config;
        $keys = explode('.', $key);
        if (!is_array($keys)) {
            return null;
        }

        foreach($keys as $part) {
            if (!is_array($result) || !isset($result[$part])) {
                return null;
            }
            $result = $result[$part];
        }

        return $result;
    }

    /**
     * Gets value behind the key, or returns $default value if path does not exists.
     *
     * @param string $key
     * @param null $default
     * @return null|string|string[]
     */
    public function get(string $key, $default = null)
    {
        $result = $this->lookup($key);

        return is_null($result) ? $default : $this->fetchConstants($result);
    }

    /**
     * Merges one instance of Config class into current one and
     * returns current instance.
     *
     * @param Config $config
     * @return Config
     */
    public function merge(Config $config): Config
    {
        $this->config = array_merge_recursive($this->config, $config->config);

        return $this;
    }

    /**
     * Returns new instance of the config containing only values from the
     * given namespace.
     *
     * @param string $namespace
     * @return Config
     */
    public function extract(string $namespace): Config
    {
        $extracted = $this->get($namespace);
        if (!is_array($extracted)) {
            throw ConfigException::forExtractionFailure($namespace);
        }

        return new self($extracted);
    }

    /**
     * Sets new value.
     *
     * @param string $key
     * @param mixed $value
     *
     * @todo: duplicate. optimize me
     */
    public function set(string $key, $value): void
    {
        $keys = explode('.', $key);
        if (!is_array($keys)) {
            return;
        }

        $last = array_pop($keys);
        $result = &$this->config;

        foreach ($keys as $part) {
            if (!isset($result[$part]) || !is_array($result[$part])) {
                $result[$part] = [];
            }
            $result = &$result[$part];
        }
        $result[$last] = $value;
    }

    /**
     * Returns array representation of the config.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->config;
    }

    /**
     * Returns flat array representation of the config, all nested values are stored
     * in keys containing path separated by dot.
     *
     * @return array
     */
    public function toFlatArray(): array
    {
        return self::flatten($this->config);
    }

    /** @todo: optimize me */
    private static function flatten(array &$array, string $prefix = ''): array
    {
        $values = [];
        foreach ($array as $key => &$value) {
            if (is_array($value) && !empty($value)) {
                $values = array_merge($values, self::flatten($value, $prefix . $key . '.'));
            } else {
                $values[$prefix . $key] = $value;
            }
        }

        return $values;
    }

    private function fetchConstants($value)
    {
        if (!is_string($value)) {
            return $value;
        }
        return preg_replace_callback(
            '#\$\{([^{}]*)\}#',
            static function ($matches) {
                if (defined($matches[1])) {
                    return constant($matches[1]);
                }
                return $matches[0];
            },
            $value
        );
    }
}
