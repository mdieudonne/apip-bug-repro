<?php

namespace App\Entity\Qual\Qopse;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Entity\Core\User;
use App\Repository\Qual\Qopse\BusinessRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ApiResource(
    shortName: 'qual_qopse_business',
    operations: [
        new GetCollection(
            uriTemplate: 'qual/qopse/businesses', forceEager: false
        ),
        new Post(uriTemplate: 'qual/qopse/businesses'),
        new Get(uriTemplate: 'qual/qopse/businesses/{id}'),
        new Patch(uriTemplate: 'qual/qopse/businesses/{id}'),
        new Put(uriTemplate: 'qual/qopse/businesses/{id}'),
        new Delete(uriTemplate: 'qual/qopse/businesses/{id}'),
    ],
    normalizationContext: [
        'groups' => ['business:output'],
        'enable_max_depth' => true,
    ],
    denormalizationContext: [
        'groups' => ['business:input'],
        'enable_max_depth' => true,
    ],
)]
#[ORM\Table(name: 'qopse_business', schema: 'qual')]
#[ORM\Entity(repositoryClass: BusinessRepository::class)]
class Business
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'integer')]
    #[Groups(['business:output','user:output','user:input'])]
    private $id;
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['business:output','business:input','user:output','user:input'])]
    private $name;
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'qualQopseBusinessManagers')]
    #[Groups(['business:output', 'business:input','user:output','user:input'])]
    #[MaxDepth(2)]
    private $businessManager;
    #[ORM\JoinTable(name: 'qopse_user_business', schema: 'qual')]
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'qualQopseBusinesses')]
    #[Groups(['business:output', 'business:input','user:output','user:input'])]
    #[MaxDepth(2)]
    private $attachedUsers;

    public function __construct()
    {
        $this->attachedUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getBusinessManager(): ?User
    {
        return $this->businessManager;
    }

    public function setBusinessManager(?User $businessManager): self
    {
        $this->businessManager = $businessManager;

        return $this;
    }

    public function getAttachedUsers(): Collection
    {
        return $this->attachedUsers;
    }

    public function addAttachedUser(User $attachedUser): self
    {
        if (!$this->attachedUsers->contains($attachedUser)) {
            $this->attachedUsers[] = $attachedUser;
        }

        return $this;
    }

    public function removeAttachedUser(User $attachedUser): self
    {
        if ($this->attachedUsers->contains($attachedUser)) {
            $this->attachedUsers->removeElement($attachedUser);
        }

        return $this;
    }
}
