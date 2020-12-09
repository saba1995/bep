<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use App\Repository\ProvinceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;
use Nines\MediaBundle\Entity\LinkableInterface;
use Nines\MediaBundle\Entity\LinkableTrait;

/**
 * @ORM\Entity(repositoryClass=ProvinceRepository::class)
 */
class Province extends AbstractTerm  implements LinkableInterface {

    use LinkableTrait {
        LinkableTrait::__construct as linkable_construct;
    }
    /**
     * @var Collection|Diocese[]
     * @ORM\OneToMany(targetEntity="App\Entity\Diocese", mappedBy="province")
     */
    private $dioceses;

    public function __construct() {
        parent::__construct();
        $this->linkable_construct();
        $this->dioceses = new ArrayCollection();
    }

    /**
     * @return Collection|Diocese[]
     */
    public function getDioceses() : Collection {
        return $this->dioceses;
    }

    public function addDiocese(Diocese $diocese) : self {
        if ( ! $this->dioceses->contains($diocese)) {
            $this->dioceses[] = $diocese;
            $diocese->setProvince($this);
        }

        return $this;
    }

    public function removeDiocese(Diocese $diocese) : self {
        if ($this->dioceses->removeElement($diocese)) {
            // set the owning side to null (unless already changed)
            if ($diocese->getProvince() === $this) {
                $diocese->setProvince(null);
            }
        }

        return $this;
    }
}
