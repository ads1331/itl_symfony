<?php


namespace App\Entity;

use App\Repository\GuestRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass=GuestRepository::class)
 */
class Guest
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_present = false;

    /**
     * @ORM\OneToMany(targetEntity=GuestList::class, mappedBy="guest")
     */
    private $guestLists;

     /**
     * @ORM\ManyToOne(targetEntity=Table::class, inversedBy="guests")
     * @ORM\JoinColumn(name="tables_id", referencedColumnName="id", nullable=false)
     */
    private ?Table $table;
    

    public function getTable(): ?Table
    {
        return $this->table;
    }

    public function __construct()
    {
        $this->guestLists = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function isIsPresent(): ?bool
    {
        return $this->is_present;
    }

    public function setIsPresent(bool $is_present): self
    {
        $this->is_present = $is_present;

        return $this;
    }

    /**
     * @return Collection|GuestList[]
     */
    public function getGuestLists(): Collection
    {
        return $this->guestLists;
    }

    public function addGuestList(GuestList $guestList): self
    {
        if (!$this->guestLists->contains($guestList)) {
            $this->guestLists[] = $guestList;
            $guestList->setGuest($this);
        }

        return $this;
    }

    public function removeGuestList(GuestList $guestList): self
    {
        if ($this->guestLists->removeElement($guestList)) {
            if ($guestList->getGuest() === $this) {
                $guestList->setGuest(null);
            }
        }

        return $this;
    }
    public function getIsPresent(): ?bool
    {
        return $this->is_present;
    }
    public function setTable(?Table $table): self
    {
        $this->table = $table;
        return $this;
    }
}
