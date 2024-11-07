<?php



namespace App\Entity;

use App\Repository\TableRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass=TableRepository::class)
 * @ORM\Table(name="tables")
 */
class Table
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $num;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $maxGuests;

    /**
     * @ORM\OneToMany(targetEntity=GuestList::class, mappedBy="table")
     */
    private $guestLists;
    /**
     * @ORM\OneToMany(targetEntity=Guest::class, mappedBy="table")
     */
    private Collection $guests;
    public function __construct()
    {
        $this->guestLists = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNum(): ?int
    {
        return $this->num;
    }

    public function setNum(int $num): self
    {
        $this->num = $num;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getMaxGuests(): ?int
    {
        return $this->maxGuests;
    }

    public function setMaxGuests(?int $maxGuests): self
    {
        $this->maxGuests = $maxGuests;

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
            $guestList->setTable($this);
        }

        return $this;
    }

    public function removeGuestList(GuestList $guestList): self
    {
        if ($this->guestLists->removeElement($guestList)) {
           
            if ($guestList->getTable() === $this) {
                $guestList->setTable(null);
            }
        }

        return $this;
    }
    public function getGuestsDef(): ?int
    {
        return $this->maxGuests; 
    }

    public function getGuestsNow(): int
    {
        return count($this->guestLists);
    }
    public function getGuests(): Collection
    {
    return $this->guests; 
    }
    public function __toString(): string
    {
        return $this->getNum() . ' - ' . $this->getDescription(); 
    }
}
