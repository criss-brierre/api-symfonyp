<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Put;
use App\Repository\GroupsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups as AnnotationGroups;


#[ORM\Entity(repositoryClass: GroupsRepository::class)]
#[ORM\EntityListeners(['App\EntityListener\GroupListener'])]
#[ApiResource(operations: [
    new GetCollection(
        uriTemplate : "/groups",
        normalizationContext: [
            'groups' => ['get:Group']
        ],
    ),
    new Delete(
        security: "is_granted('ROLE_ADMIN')",
        uriTemplate: 'group/{id}/', 
        normalizationContext: [
            'groups' => ['delete:group']
        ],
    ), 
    new Patch(
        security: "is_granted('ROLE_ADMIN')",
        uriTemplate: 'group/update/{id}/', 
        denormalizationContext: [
            'groups' => ['modif:group']
        ],
    ), 
    new Post(
        security: "is_granted('ROLE_ADMIN')",
        uriTemplate: 'group/create', 
        denormalizationContext: [
            'groups' => ['add:group']
        ],
    ), 
    new GetCollection(
        uriTemplate : "group/users",
        normalizationContext: [
            'groups' => ['get:Userpergroups']
        ],
    ),
    new Patch(
        security: "is_granted('ROLE_ADMIN')",
        uriTemplate : "group/updateusers/{id}/",
        denormalizationContext: [
            'groups' => ['modif:usersgroup']
        ],
    ),
    
])]
class Groups
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10)]
    #[AnnotationGroups(['get:Group','get:Userpergroups','modif:group','add:group'])]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;
    
    #[AnnotationGroups(['get:Userpergroups','modif:usersgroup'])]
    #[ORM\OneToMany(mappedBy: 'groupe', targetEntity: Users::class)]
    private Collection $Users;

    public function __construct()
    {
        $this->Users = new ArrayCollection();
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function addUser(Users $user): self
    {
        if (!$this->Users->contains($user)) {
            $this->Users->add($user);
            $user->setGroupe($this);
        }

        return $this;
    }

    public function removeUser(Users $user): self
    {

        if ($this->Users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getGroupe() === $this) {
                $user->setGroupe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Users>
     */
    public function getUsers(): Collection
    {
        return $this->Users;
    }

    // public function setUsers(Collection $users): self
    // {
    //     $this->Users = $users;

    //     return $this;
    // }
}
