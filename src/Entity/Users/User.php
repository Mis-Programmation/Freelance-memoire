<?php

declare(strict_types=1);

namespace App\Entity\Users;

use App\Entity\IdTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Valid;
use App\Repository\Users\UserRepository;
/**
 * @method string getUserIdentifier()
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity("email",repositoryMethod="findByUniqueEmail",groups={"Default","create:user"})
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator", type="string")
 * @ORM\DiscriminatorMap({"enterprise" = "Enterprise", "individual" = "Individual","freelancer" = "Freelancer"})
 */
abstract class User implements UserInterface, PasswordAuthenticatedUserInterface
{

    use IdTrait;
    /**
     * @ORM\Column(type="string",length=255,nullable=false)
     */
    #[NotBlank(groups: ['create:user',"edit:profile"])]
    #[Groups(["Default","read:enterprise","read:profile:freelancer"])]
    protected ?string $firstName = '';

    /**
     * @ORM\Column(type="string",length=255,nullable=false)
     */
    #[NotBlank(groups: ['create:user',"edit:profile"])]
    #[Groups(["Default","read:enterprise","read:profile:freelancer"])]
    protected ?string $lastName = '';

    /**
     * @ORM\Column(type="string",length=255,nullable=false)
     */
    #[Groups(["Default","read:enterprise","read:profile:freelancer"])]
    #[NotBlank(groups: ['create:user',"edit:profile"]),Email(groups: ['create:user',"edit:profile"])]
    protected ?string $email = '';

    /**
     * @ORM\Column(type="string",length=255,nullable=false)
     */
    protected ?string $password = '';

    #[NotBlank(groups: ['create:user'])]
    #[Length(min: 8, max: 4096, groups: ['create:user'])]

    public ?string $plainPassword = null;

    /**
     * @ORM\Column(type="datetime",nullable=false)
     */
    #[Groups(["Default","read:enterprise","read:offer","read:profile:freelancer"])]
    protected ?\DateTimeInterface $createdAt = null;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Job\Offer", mappedBy="owner")
         */
    #[Valid(groups: ['profile'])]
    #[Groups(["Default","read:enterprise"])]
    protected Collection $offers;

    public function __construct()
    {
        $this->offers = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }


    public function getPassword():string
    {
       return $this->password;
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    #[Groups(["Default","read:enterprise","read:profile:freelancer"])]
    public function getUsername():string
    {
       return $this->email;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string|null $firstName
     */
    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string|null $lastName
     */
    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }


    /**
     * @param string|null $password
     */
    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return \DateTime|\DateTimeInterface
     */
    public function getCreatedAt(): \DateTime|\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime|\DateTimeInterface $createdAt
     */
    public function setCreatedAt(\DateTime|\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    #[Groups(["Default","read:offer","read:profile:freelancer"])]
    public function getFullName(): string
    {
        return sprintf('%s %s',$this->firstName,$this->lastName);
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function getOffers(): ArrayCollection|Collection
    {
        return $this->offers;
    }

}
