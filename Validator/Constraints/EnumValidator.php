<?php

/*
 * This file is part of the LinkinEnumPropertyBundle package.
 *
 * (c) Viktor Linkin <adrenalinkin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Linkin\Bundle\EnumPropertyBundle\Validator\Constraints;

use Linkin\Bundle\EnumPropertyBundle\Exception\UnsupportedMapperException;

use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\ChoiceValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * @author Viktor Linkin <adrenalinkin@gmail.com>
 */
class EnumValidator extends ChoiceValidator
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @param TranslatorInterface $translator
     */
    public function setTranslator(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Enum) {
            throw new UnexpectedTypeException($constraint, sprintf('%s\Enum', __NAMESPACE__));
        }

        $mapperClass = $constraint->mapperName;

        if (!is_subclass_of($mapperClass, '\Linkin\Bundle\EnumPropertyBundle\Mapper\AbstractEnumPropertyMapper')) {
            throw new UnsupportedMapperException('Mapper class should extends "AbstractEnumPropertyMapper"');
        }

        /** @var \Linkin\Bundle\EnumPropertyBundle\Mapper\AbstractEnumPropertyMapper $mapper */
        $mapper              = new $mapperClass($this->translator, $constraint->domain);
        $constraint->choices = $mapper->getAllowedDbValues($constraint->exclude);
        $allowed             = [];

        foreach ($constraint->choices as $dbValue) {
            $humanValue = $mapper->fromDbToHuman($dbValue);
            $allowed[]  = sprintf('%s (%s)', $dbValue, $this->translator->trans($humanValue, [], $constraint->domain));
        }

        $constraint->message = $this->translator->trans(
            'linkin_enum_property.messages.unexpected_value',
            ['{{ value }}' => $value, '{{ allowed }}' => implode(', ', $allowed), ],
            'validators'
        );

        parent::validate($value, $constraint);
    }
}
