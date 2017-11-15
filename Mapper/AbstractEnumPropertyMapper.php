<?php

/*
 * This file is part of the LinkinEnumPropertyBundle package.
 *
 * (c) Viktor Linkin <adrenalinkin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Linkin\Bundle\EnumPropertyBundle\Mapper;

use Linkin\Component\EnumMapper\Mapper\AbstractEnumMapper;

use Symfony\Component\Translation\TranslatorInterface;

/**
 * @TODO improve back conversion. Attention to the translation
 *
 * @author Viktor Linkin <adrenalinkin@gmail.com>
 */
abstract class AbstractEnumPropertyMapper extends AbstractEnumMapper
{
    /**
     * @var string|null
     */
    protected $translationDomain;

    /**
     * @var TranslatorInterface|null
     */
    protected $translator;

    /**
     * @param TranslatorInterface|null $translator
     * @param string|null              $translationDomain
     */
    public function __construct(TranslatorInterface $translator = null, $translationDomain = null)
    {
        $this->translationDomain = $translationDomain;
        $this->translator        = $translator;
    }

    /**
     * @return null|string
     */
    public function getTranslationDomain()
    {
        return $this->translationDomain;
    }

    /**
     * @param string $translationDomain
     *
     * @return $this
     */
    public function setTranslationDomain($translationDomain)
    {
        $this->translationDomain = $translationDomain;

        return $this;
    }

    /**
     * @return TranslatorInterface|null
     */
    public function getTranslator()
    {
        return $this->translator;
    }

    /**
     * @param TranslatorInterface|null $translator
     *
     * @return $this
     */
    public function setTranslator(TranslatorInterface $translator = null)
    {
        $this->translator = $translator;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function getAppropriateConstValue($prefixFrom, $constName)
    {
        $value = parent::getAppropriateConstValue($prefixFrom, $constName);

        return $prefixFrom === self::PREFIX_DB && $this->translator
            ? $this->translator->trans($value, [], $this->translationDomain)
            : $value;
    }
}
