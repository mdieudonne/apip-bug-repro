<?php

namespace App\Entity\Core;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Entity\Qual\Qopse\Business;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ApiResource(operations: [
        new Get(),
        new Patch(),
        new Put(),
        new Patch(),
        new Delete(),
        new GetCollection(),
        new Post(),
    ],
    normalizationContext: [
        'groups'           => ['user:output'],
        'enable_max_depth' => true,
    ],
    denormalizationContext: [
        'groups'           => ['user:input'],
        'enable_max_depth' => true,
    ],
)]
#[ORM\Table(name: 'user', schema: 'core')]
#[ORM\Entity(repositoryClass: \App\Repository\Core\UserRepository::class)]
class User implements UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'integer')]
    #[Groups([
      'user:output',
      'business:output',
      'business:input',
    ])]
    private $id;
    #[ORM\Column(type: 'string', length: 180, unique: true, nullable: true)]
    #[Groups(['user:output','user:input','business:output', 'business:input'])]
    private $username;
    #[ORM\OneToMany(targetEntity: \App\Entity\Qual\Qopse\Business::class, mappedBy: 'businessManager')]
    private $qualQopseBusinessManagers;
    #[ORM\ManyToMany(targetEntity: \App\Entity\Qual\Qopse\Business::class, mappedBy: 'attachedUsers')]
    private $qualQopseBusinesses;

    public function __construct()
    {
        $this->qualQopseBusinessManagers      = new ArrayCollection();
        $this->qualQopseBusinesses            = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

  /**
   * A visual identifier that represents this user.
   *
   * @see UserInterface
   */
  public function getUsername(): string
  {
    return (string) $this->username;
  }

  public function setUsername(string $username): self
  {
    $this->username = $username;

    return $this;
  }

    public function getQualQopseBusinessManager(): Collection
    {
        return $this->qualQopseBusinessManagers;
    }

    public function addQualQopseBusinessManager(Business $qualQopseBusinessManager): self
    {
        if (!$this->qualQopseBusinessManagers->contains($qualQopseBusinessManager)) {
            $this->qualQopseBusinessManagers[] = $qualQopseBusinessManager;
            $qualQopseBusinessManager->setBusinessManager($this);
        }

        return $this;
    }

    public function removeQualQopseBusinessManager(Business $qualQopseBusinessManager): self
    {
        if ($this->qualQopseBusinessManagers->contains($qualQopseBusinessManager)) {
            $this->qualQopseBusinessManagers->removeElement($qualQopseBusinessManager);
            // set the owning side to null (unless already changed)
            if ($qualQopseBusinessManager->getBusinessManager() === $this) {
                $qualQopseBusinessManager->setBusinessManager(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Business[]
     */
    public function getQualQopseBusinesses(): Collection
    {
        return $this->qualQopseBusinesses;
    }

    public function addQualQopseBusiness(Business $qualQopseBusiness): self
    {
        if (!$this->qualQopseBusinesses->contains($qualQopseBusiness)) {
            $this->qualQopseBusinesses[] = $qualQopseBusiness;
            $qualQopseBusiness->addAttachedUser($this);
        }

        return $this;
    }

    public function removeQualQopseBusiness(Business $qualQopseBusiness): self
    {
        if ($this->qualQopseBusinesses->contains($qualQopseBusiness)) {
            $this->qualQopseBusinesses->removeElement($qualQopseBusiness);
            $qualQopseBusiness->removeAttachedUser($this);
        }

        return $this;
    }

  public function getRoles(): array
  {
    // TODO: Implement getRoles() method.
  }

  public function eraseCredentials()
  {
    // TODO: Implement eraseCredentials() method.
  }

  public function getUserIdentifier(): string
  {
    // TODO: Implement getUserIdentifier() method.
  }
}
