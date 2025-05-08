<?php

declare(strict_types=1);

namespace App\Form\DTO;

use App\DTO\ForecastDTO;
use App\Entity\Location;
use App\Enum\ShortWeatherDescription;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class ForecastDTOForm extends AbstractType
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'location',
            EntityType::class,
            [
                'class' => Location::class,
                'choice_label' => fn (Location $location) => "{$location->getName()} ({$location->getCountry()})",
            ]
        )->add(
            'day',
            DateType::class,
            [
                'input' => 'datetime_immutable',
                'widget' => 'choice',
            ]
        )->add(
            'shortWeatherDescription',
            ChoiceType::class,
            [
                'choices' => ShortWeatherDescription::cases(),
                'choice_label' => fn (ShortWeatherDescription $shortWeatherDescription) => $this->translator->trans('weather_description.'.$shortWeatherDescription->name),
            ]
        )->add('windSpeedKmh')
            ->add('humidityPercentage')
            ->add('temperatureSpan', TemperatureSpanDTOForm::class)
            ->add(
                'submit',
                SubmitType::class
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', ForecastDTO::class);
    }
}
