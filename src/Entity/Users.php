<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use App\Repository\UsersRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups as AnnotationGroups;

use App\Entity\Groups as EntityGroups;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
#[ORM\EntityListeners(['App\EntityListener\UserListener'])]
#[ApiResource(operations: [
    new Post(
        uriTemplate : "user/create",
        denormalizationContext: [
            'groups' => ['create:user']
        ],
    ),
    new GetCollection(
        uriTemplate : "/users",
        normalizationContext: [
            'groups' => ['get:users']
        ],
    ),
     new Get(
        security: "is_granted('IS_AUTHENTICATED')",
        uriTemplate: '/getuser/{id}/', 
        normalizationContext: [
            'groups' => ['get:user']
        ],
    ), 
    new Patch( 
        security: "is_granted('ROLE_ADMIN')",
        uriTemplate: 'user/update/{id}', 
        output: false, 
        denormalizationContext: [
            'groups' => ['modif:user']
        ],
    ),
    new Patch(
        security: "is_granted('IS_AUTHENTICATED')",
        uriTemplate: 'user/addgroup/{id}', 
        denormalizationContext: [
            'groups' => ['addgroup:user']
        ],
    ), 
    new Delete(
        security: "is_granted('ROLE_ADMIN')",
        uriTemplate: 'user/delete/{id}/', 
        normalizationContext: [
            'groups' => ['delete:user']
        ],
    ), 
    new GetCollection(
        security: "is_granted('ROLE_ADMIN')",
        uriTemplate : "user/getall",
        normalizationContext: [
            'groups' => ['get:allusers']
        ],
    ),
])]

class Users implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    
    #[ORM\Column(length: 180, unique: true)]
    #[AnnotationGroups(['create:user','get:users','get:user','get:allusers','modif:user','update:user'])]
    private ?string $email = null;

    #[ORM\Column]
    #[AnnotationGroups(['update:user'])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[AnnotationGroups(['create:user','modif:user'])]
    private ?string $plainPassword = null;
    #[ORM\Column]
    #[AnnotationGroups(['get:user'])]
    private ?string $password = null;
    
    #[ORM\Column(length: 30, nullable: true)]
    #[AnnotationGroups(['create:user','get:users','get:Userpergroups','get:user','modif:user','update:user'])]
    private ?string $firstname = null;
    
    #[ORM\Column(length: 35, nullable: true)]
    #[AnnotationGroups(['create:user','get:users','get:Userpergroups','get:user','modif:user','update:user'])]
    private ?string $lastname = null;
    
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'Users')]
    #[AnnotationGroups(['create:users','get:user','addgroup:user','modif:user','update:user'])]
    private ?EntityGroups $groupe = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        // $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $password): self
    {
        $this->plainPassword = $password;

        return $this;
    }
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

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

    public function getGroupe(): ?EntityGroups
    {
        return $this->groupe;
    }

    public function setGroupe(?EntityGroups $groupe): self
    {
        $this->groupe = $groupe;

        return $this;
    }
}
