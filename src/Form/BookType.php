<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Form;

use App\Entity\Book;
use App\Entity\Format;
use App\Entity\Monarch;
use App\Form\Partial\NotesType;
use Nines\MediaBundle\Form\LinkableType;
use Nines\MediaBundle\Form\Mapper\LinkableMapper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

/**
 * Book form.
 */
class BookType extends AbstractType {
    private LinkableMapper $mapper;

    /**
     * Add form fields to $builder.
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->add('title', TextareaType::class, [
            'label' => 'Title',
            'required' => false,
            'attr' => [
                'help_block' => 'As it appears in the ESTC',
                'class' => '',
            ],
        ]);
        $builder->add('uniformTitle', TextareaType::class, [
            'label' => 'Uniform Title',
            'required' => false,
            'attr' => [
                'help_block' => '',
                'class' => '',
            ],
        ]);
        $builder->add('variantTitles', CollectionType::class, [
            'label' => 'Variant Titles',
            'required' => false,
            'allow_add' => true,
            'allow_delete' => true,
            'delete_empty' => true,
            'entry_type' => TextType::class,
            'entry_options' => [
                'label' => false,
            ],

            'attr' => [
                'class' => 'collection collection-simple',
                'help_block' => "Year, followed by the title in modern English. Eg. '1631, A thanksgiving, and prayer for the safe child bearing of the queen's majesty'. Also add any other variant titles listed in the ESTC.",
            ],
        ]);
        $builder->add('author', TextType::class, [
            'label' => 'Author',
            'required' => false,
            'attr' => [
                'help_block' => '',
            ],
        ]);
        $builder->add('imprint', TextareaType::class, [
            'label' => 'Imprint',
            'required' => false,
            'attr' => [
                'help_block' => 'Imprint as noted on the ESTC',
            ],
        ]);
        $builder->add('variantImprint', TextareaType::class, [
            'label' => 'Imprint, Modern English”',
            'required' => false,
            'attr' => [
                'help_block' => 'Original spelling imprint and any variations of it',
            ],
        ]);
        $builder->add('date', TextType::class, [
            'label' => 'Date',
            'required' => false,
            'attr' => [
                'help_block' => '',
            ],
        ]);

        $builder->add('estc', TextType::class, [
            'label' => 'Estc',
            'required' => false,
            'attr' => [
                'help_block' => '',
            ],
        ]);

        $builder->add('monarch', Select2EntityType::class, [
            'label' => 'Monarch',
            'required' => false,
            'class' => Monarch::class,
            'remote_route' => 'monarch_typeahead',
            'attr' => [
                'help_block' => '',
                'add_path' => 'monarch_new_popup',
                'add_label' => 'Add Monarch',
            ],
        ]);

        $builder->add('physicalDescription', TextareaType::class, [
            'label' => 'Physical Description',
            'required' => false,
            'attr' => [
                'help_block' => 'Public description of the physicality of the item.',
                'class' => 'tinymce',
            ],
        ]);

        $builder->add('description', TextareaType::class, [
            'label' => 'Description',
            'required' => false,
            'attr' => [
                'help_block' => 'Public description of the item.',
                'class' => 'tinymce',
            ],
        ]);
        NotesType::add($builder, $options);

        $builder->add('format', Select2EntityType::class, [
            'label' => 'Format',
            'class' => Format::class,
            'remote_route' => 'format_typeahead',
            'allow_clear' => true,
            'attr' => [
                'help_block' => '',
                'add_path' => 'format_new_popup',
                'add_label' => 'Add Format',
            ],
        ]);
        LinkableType::add($builder, $options);
        $builder->setDataMapper($this->mapper);
    }

    /**
     * @required
     */
    public function setMapper(LinkableMapper $mapper) : void {
        $this->mapper = $mapper;
    }

    /**
     * Define options for the form.
     *
     * Set default, optional, and required options passed to the
     * buildForm() method via the $options parameter.
     */
    public function configureOptions(OptionsResolver $resolver) : void {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
