<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use App\Repository\InventoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\MediaBundle\Entity\ImageContainerInterface;
use Nines\MediaBundle\Entity\ImageContainerTrait;
use Nines\UtilBundle\Entity\AbstractEntity;

/**
 * @ORM\Entity(repositoryClass=InventoryRepository::class)
 * @ORM\Table(indexes={
 *     @ORM\Index(name="inventory_ft", columns={"transcription", "modifications", "description", "notes"}, flags={"fulltext"})
 * })
 */
class Inventory extends AbstractEntity implements ImageContainerInterface {
    use DatedTrait;
    use NotesTrait;
    use ImageContainerTrait;

    /**
     * @var string
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $pageNumber;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $transcription;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $modifications;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @var Source
     * @ORM\ManyToOne(targetEntity="App\Entity\Source", inversedBy="inventories")
     * @ORM\JoinColumn(nullable=false)
     */
    private $source;

    /**
     * @var Parish
     * @ORM\ManyToOne(targetEntity="App\Entity\Parish", inversedBy="inventories")
     * @ORM\JoinColumn(nullable=false)
     */
    private $parish;

    /**
     * @var Monarch
     * @ORM\ManyToOne(targetEntity="Monarch", inversedBy="inventories")
     * @ORM\JoinColumn(nullable=true)
     */
    private $monarch;

    /**
     * @var Book[]|Collection
     * @ORM\ManyToMany(targetEntity="App\Entity\Book", inversedBy="inventories")
     */
    private $books;

    public function __construct() {
        parent::__construct();
        $this->books = new ArrayCollection();
    }

    /**
     * {@inheritDoc}
     */
    public function __toString() : string {
        return mb_substr(strip_tags($this->transcription), 0, 50);
    }

    public function getTranscription() : ?string {
        return $this->transcription;
    }

    public function setTranscription(string $transcription) : self {
        $this->transcription = $transcription;

        return $this;
    }

    public function getModifications() : ?string {
        return $this->modifications;
    }

    public function setModifications(string $modifications) : self {
        $this->modifications = $modifications;

        return $this;
    }

    public function getDescription() : ?string {
        return $this->description;
    }

    public function setDescription(?string $description) : self {
        $this->description = $description;

        return $this;
    }

    public function getSource() : ?Source {
        return $this->source;
    }

    public function setSource(?Source $source) : self {
        $this->source = $source;

        return $this;
    }

    public function getParish() : ?Parish {
        return $this->parish;
    }

    public function setParish(?Parish $parish) : self {
        $this->parish = $parish;

        return $this;
    }

    /**
     * @return Book[]|Collection
     */
    public function getBooks() : Collection {
        return $this->books;
    }

    public function addBook(Book $book) : self {
        if ( ! $this->books->contains($book)) {
            $this->books[] = $book;
        }

        return $this;
    }

    public function removeBook(Book $book) : self {
        $this->books->removeElement($book);

        return $this;
    }

    public function getMonarch() : ?Monarch {
        return $this->monarch;
    }

    public function setMonarch(?Monarch $monarch) : self {
        $this->monarch = $monarch;

        return $this;
    }

    public function getPageNumber() : ?string {
        return $this->pageNumber;
    }

    public function setPageNumber(?string $pageNumber) : self {
        $this->pageNumber = $pageNumber;

        return $this;
    }
}
