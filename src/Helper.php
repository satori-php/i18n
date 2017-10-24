<?php

/**
 * @author    Yuriy Davletshin <yuriy.davletshin@gmail.com>
 * @copyright 2017 Yuriy Davletshin
 * @license   MIT
 */

declare(strict_types=1);

namespace Satori\I18n;

/**
 * Extendable helper for i18n.
 */
class Helper
{
    /**
     * @var string Original lang ID.
     */
    protected $lang;

    /**
     * @var string Locale ID.
     */
    protected $locale;

    /**
     * @var array<string, mixed> Contains parameters for custom methods.
     */
    protected $params = [];

    /**
     * @var array<string, callable> Contains custom methods.
     */
    protected $methods = [];

    /**
     * Constructor.
     *
     * @param string               $lang   The original lang ID.
     * @param string               $locale The locale ID.
     * @param array<string, mixed> $params The parameters for custom methods.
     */
    public function __construct(string $lang, string $locale, array $params)
    {
        $this->lang = $lang;
        $this->locale = $locale;
        $this->params = $params;
    }

    /**
     * Returns a value of the lang or the locale property.
     *
     * @param string $name The unique name of the property.
     *
     * @throws \LogicException If the property is not `lang` or `locale`.
     *
     * @return mixed
     */
    public function __get(string $name)
    {
        if ($name === 'lang' || $name === 'locale' ) {
            return $this->$name;
        }
        throw new \LogicException(sprintf('Property "%s" is forbidden.', $name));
    }

    /**
     * Calls a custom method.
     *
     * @param string       $name The unique name of the custom method.
     * @param array<mixed> $args The arguments of the custom method.
     *
     * @throws \LogicException If the custom method is not defined.
     *
     * @return mixed
     */
    public function __call(string $name, array $args)
    {
        if (isset($this->methods[$name])) {
            return $this->methods[$name](...$args);
        }
        throw new \LogicException(sprintf('Custom method "%s" is not defined.', $name));
    }

    /**
     * Sets a custom method.
     *
     * @param string   $name           The unique name of the custom method.
     * @param \Closure $implementation The implementation of the custom method.
     */
    public function addMethod(string $name, \Closure $implementation)
    {
        $this->methods[$name] = \Closure::bind($implementation, $this, get_class());
    }
}
