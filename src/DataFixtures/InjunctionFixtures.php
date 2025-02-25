<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use App\Entity\Injunction;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class InjunctionFixtures extends Fixture {
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em) : void {
        for ($i = 1; $i <= 4; $i++) {
            $fixture = new Injunction();
            $fixture->setTitle('Title ' . $i);
            $fixture->setUniformTitle("<p>This is paragraph {$i}</p>");
            $fixture->setVariantTitles(['VariantTitles ' . $i]);
            $fixture->setAuthor('Author ' . $i);
            $fixture->setImprint("<p>This is paragraph {$i}</p>");
            $fixture->setVariantImprint("<p>This is paragraph {$i}</p>");
            $fixture->setDate('Date ' . $i);
            $fixture->setDescription("<p>This is paragraph {$i}</p>");
            $fixture->setEstc('Estc ' . $i);

            $em->persist($fixture);
            $this->setReference('injunction.' . $i, $fixture);
        }
        $em->flush();
    }
}
