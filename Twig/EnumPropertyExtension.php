<?php

/*
 * This file is part of the LinkinEnumPropertyBundle package.
 *
 * (c) Viktor Linkin <adrenalinkin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Linkin\Bundle\EnumPropertyBundle\Twig;

use Linkin\Bundle\EnumPropertyBundle\Exception\UnsupportedMapperException;

/**
 * @author Viktor Linkin <adrenalinkin@gmail.com>
 */
class EnumPropertyExtension extends \Twig_Extension
{
    /**
     * List of the all already called mappers
     *
     * @var \Linkin\Component\EnumMapper\Mapper\AbstractEnumMapper[]
     */
    private $mapperCache = [];

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('enum_to_human', [$this, 'fromDbToHuman']),
            new \Twig_SimpleFilter('enum_to_db', [$this, 'fromHumanToDb']),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('enum_allowed_db', [$this, 'getAllowedDbValues']),
            new \Twig_SimpleFunction('enum_allowed_human', [$this, 'getAllowedHumanValues']),
            new \Twig_SimpleFunction('enum_map', [$this, 'getMap']),
            new \Twig_SimpleFunction('enum_random_db', [$this, 'getRandomDbValue']),
            new \Twig_SimpleFunction('enum_random_human', [$this, 'getRandomHumanValue']),
        ];
    }

    /**
     * Returns humanized value by received database value
     * @see \Linkin\Component\EnumMapper\Mapper\AbstractEnumMapper::fromDbToHuman
     *
     * @param string $dbValue     Database value
     * @param string $mapperClass Full name of the mapper class
     *
     * @return int|string
     *
     * @throws UnsupportedMapperException
     */
    public function fromDbToHuman($dbValue, $mapperClass)
    {
        return $this->getMapper($mapperClass)->fromDbToHuman($dbValue);
    }

    /**
     * Returns database value by received humanized value
     * @see \Linkin\Component\EnumMapper\Mapper\AbstractEnumMapper::fromHumanToDb
     *
     * @param string $humanValue  Humanized value
     * @param string $mapperClass Full name of the mapper class
     *
     * @return int|string
     *
     * @throws UnsupportedMapperException
     */
    public function fromHumanToDb($humanValue, $mapperClass)
    {
        return $this->getMapper($mapperClass)->fromHumanToDb($humanValue);
    }

    /**
     * Returns list of the all registered database values
     * @see \Linkin\Component\EnumMapper\Mapper\AbstractEnumMapper::getAllowedDbValues
     *
     * @param string $mapperClass Full name of the mapper class
     * @param array  $except      List of the database values which should be excluded
     *
     * @return array
     */
    public function getAllowedDbValues($mapperClass, array $except = [])
    {
        return $this->getMapper($mapperClass)->getAllowedDbValues($except);
    }

    /**
     * Returns list of the all registered humanized values
     * @see \Linkin\Component\EnumMapper\Mapper\AbstractEnumMapper::getAllowedHumanValues
     *
     * @param string $mapperClass Full name of the mapper class
     * @param array  $except      List of the humanized values which should be excluded
     *
     * @return array
     */
    public function getAllowedHumanValues($mapperClass, array $except = [])
    {
        return $this->getMapper($mapperClass)->getAllowedHumanValues($except);
    }

    /**
     * Returns map of the all registered values in the 'key' => 'value' pairs.
     * @see \Linkin\Component\EnumMapper\Mapper\AbstractEnumMapper::getMap
     *
     * @param string $mapperClass Full name of the mapper class
     *
     * @return array
     */
    public function getMap($mapperClass)
    {
        return $this->getMapper($mapperClass)->getMap();
    }

    /**
     * Returns random database value
     * @see \Linkin\Component\EnumMapper\Mapper\AbstractEnumMapper::getRandomDbValue
     *
     * @param string $mapperClass Full name of the mapper class
     * @param array  $except      List of the database values which should be excluded
     *
     * @return string|int
     */
    public function getRandomDbValue($mapperClass, array $except = [])
    {
        return $this->getMapper($mapperClass)->getRandomDbValue($except);
    }

    /**
     * Returns random humanized value
     * @see \Linkin\Component\EnumMapper\Mapper\AbstractEnumMapper::getRandomHumanValue
     *
     * @param string $mapperClass Full name of the mapper class
     * @param array  $except      List of the humanized values which should be excluded
     *
     * @return string|int
     */
    public function getRandomHumanValue($mapperClass, array $except = [])
    {
        return $this->getMapper($mapperClass)->getRandomHumanValue($except);
    }

    /**
     * Returns instance of the mapper
     *
     * @param string $mapperClass Full name of the mapper class
     *
     * @return \Linkin\Component\EnumMapper\Mapper\AbstractEnumMapper
     *
     * @throws UnsupportedMapperException
     */
    private function getMapper($mapperClass)
    {
        if (isset($this->mapperCache[$mapperClass])) {
            return $this->mapperCache[$mapperClass];
        }

        if (!is_subclass_of($mapperClass, 'Linkin\Component\EnumMapper\Mapper\AbstractEnumMapper')) {
            throw new UnsupportedMapperException();
        }

        /** @var \Linkin\Component\EnumMapper\Mapper\AbstractEnumMapper $mapper */
        $mapper = new $mapperClass();

        $this->mapperCache[$mapperClass] = $mapper;

        return $mapper;
    }
}
