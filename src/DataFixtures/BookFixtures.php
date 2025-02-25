<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BookFixtures extends Fixture implements DependentFixtureInterface {
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em) : void {
        for ($i = 1; $i <= 4; $i++) {
            $fixture = new Book();
            $fixture->setTitle("This is paragraph {$i}");
            $fixture->setUniformTitle("This is paragraph {$i}");
            $fixture->setVariantTitles(['VariantTitles ' . $i]);
            $fixture->setAuthor('Author ' . $i);
            $fixture->setImprint('Imprint ' . $i);
            $fixture->setDate('Date ' . $i);
            $fixture->setDescription("<p>This is paragraph {$i}</p>");
            $fixture->setFormat($this->getReference('format.' . $i));
            $em->persist($fixture);
            $this->setReference('book.' . $i, $fixture);
        }
        $em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies() {
        return [
            FormatFixtures::class,
        ];
    }
}
