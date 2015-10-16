<?php
/**
 * PHP Version 5.5
 * @category Library
 * @package ChangeLog
 * @author Steve "uru" West <steven.david.west@gmail.com>
 * @license MIT http://opensource.org/licenses/MIT
 * @link https://github.com/stevewest/changelog
 */

namespace ChangeLog;

use InvalidArgumentException;
use ReflectionClass;

/**
 * Basic factory class that can be used to load classes from any given namespace.
 * Gee, would sure be awesome if PHP had generics for this...
 */
class GenericFactory
{

	/**
	 * Base namespace
	 *
	 * @var string
	 */
	protected $baseNamespace;

	/**
	 * Contains any mappings for extra classes.
	 *
	 * @var array
	 */
	protected $addedClasses = [];

	/**
	 * GenericFactory constructor.
	 *
	 * @param string $baseNamespace
	 */
	public function __construct($baseNamespace)
	{
		$this->baseNamespace = $baseNamespace;
	}

	/**
	 * Returns a constructed instance of the named class.
	 *
	 * @param string $name
	 * @param array  $parameters
	 *
	 * @throws InvalidArgumentException If the class cannot be found.
	 */
	public function getInstance($name, $parameters = [])
	{
		$class = $this->baseNamespace . ucfirst($name);

		// If we have a custom class, use that instead.
		if ( ! empty($this->addedClasses[$name]))
		{
			$class = $this->addedClasses[$name];
		}

		// Ensure our class actually exists
		if ( ! class_exists($class))
		{
			throw new InvalidArgumentException("$name is not a known class ($class)");
		}

		$reflectionClass = new ReflectionClass($class);
		return $reflectionClass->newInstanceArgs($parameters);
	}

	/**
	 * Adds a new named class to the factory.
	 *
	 * @param string $name
	 * @param string $class
	 */
	public function addClass($name, $class)
	{
		$this->addedClasses[$name] = $class;
	}

}
