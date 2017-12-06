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

use Symfony\Component\Translation\TranslatorInterface;

/**
 * @author Viktor Linkin <adrenalinkin@gmail.com>
 */
class EnumPropertyExtension extends \Twig_Extension
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param string      $db
     * @param string      $mapperClass
     * @param string|null $translationDomain
     *
     * @throws UnsupportedMapperException
     *
     * @return int|string
     */
    public function fromDbToHuman($db, $mapperClass, $translationDomain = null)
    {
        return $this->getMapper($mapperClass, $translationDomain)->fromDbToHuman($db);
    }

    /**
     * @param string $human
     * @param string $mapperClass
     *
     * @throws UnsupportedMapperException
     *
     * @return int|string
     */
    public function fromHumanToDb($human, $mapperClass)
    {
        return $this->getMapper($mapperClass)->fromHumanToDb($human);
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('fromDbToHuman', [$this, 'fromDbToHuman']),
            new \Twig_SimpleFilter('fromHumanToDb', [$this, 'fromHumanToDb']),
        ];
    }

    /**
     * @param string      $mapperClass
     * @param string|null $translationDomain
     *
     * @throws UnsupportedMapperException
     *
     * @return \Linkin\Bundle\EnumPropertyBundle\Mapper\AbstractEnumPropertyMapper
     */
    private function getMapper($mapperClass, $translationDomain = null)
    {
        if (!is_subclass_of($mapperClass, '\Linkin\Bundle\EnumPropertyBundle\Mapper\AbstractEnumPropertyMapper')) {
            throw new UnsupportedMapperException('Mapper class should extends "AbstractEnumPropertyMapper"');
        }

        /** @var \Linkin\Bundle\EnumPropertyBundle\Mapper\AbstractEnumPropertyMapper $mapper */
        $mapper = new $mapperClass();

        return $mapper->setTranslator($this->translator)->setTranslationDomain($translationDomain);
    }
}
