<?php

declare(strict_types = 1);

namespace App\Form\Type;

use App\Contract\SupportedMimeTypes;
use App\Entity\Document;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class DocumentType extends AbstractType
{
    /**
     * @var array
     */
    private $supportedMimeTypes;

    public function __construct(SupportedMimeTypes $supportedMimeTypes)
    {
        $this->supportedMimeTypes = $supportedMimeTypes->getMimeTypes();
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'documentUpload',
            FileType::class,
            [
                'constraints' => [
                    new NotBlank(),
                    new File([
                        'maxSize'   => '20M',
                        'mimeTypes' => $this->supportedMimeTypes,
                    ]),
                ],
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Document::class,
        ]);
    }
}
