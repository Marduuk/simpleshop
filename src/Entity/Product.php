<?php
declare(strict_types=1);

namespace App\Entity;

use App\Helpers\Helper;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
use DateTime;

/**
 * Class Product
 * @ORM\Entity()
 * @ORM\Table("products")
 */
class Product
{
    /**
     * Product constructor.
     */
    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
        $this->setCreatedAt();
        $this->currency = Helper::getLocaleCurrency();
    }

    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer", unique=true)
     */
    private $id;

    /**
     * @var Uuid
     * @ORM\Column(type="uuid", unique=true)
     */
    private $uuid;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", nullable=false)
     * @Assert\NotNull
     * @Assert\NotBlank(message="Value cant be empty")
     * @Assert\Type("string")
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(name="description", type="string", nullable=false)
     * @Assert\NotNull
     * @Assert\NotBlank(message="Value cant be empty")
     * @Assert\Length(
     *     min = 100,
     *     minMessage = "Description must be atleast 100 characters long",
     *     allowEmptyString = false
     * )
     * @Assert\Type("string")
     */
    private $description;

    /**
     * @var float
     * @ORM\Column(name="price", type="float", nullable=false)
     * @Assert\NotNull
     * @Assert\NotBlank(message="Value cant be empty")
     * @Assert\Type("float")
     */
    private $price;

    /**
     * @var string
     * @ORM\Column(name="currency", type="string", nullable=false)
     * @Assert\NotNull
     * @Assert\NotBlank(message="Value cant be empty")
     * @Assert\Type("string")
     */
    private $currency;

    /**
     * @var DateTime $createdAt
     * @Assert\NotNull
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    protected $createdAt;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getUuid(): ?string
    {
        return $this->uuid->toString();
    }

    /**
     * @return string|null
     */
    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param float $price
     * @return self
     */
    public function setPrice(float $price): self
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @param string $currency
     * @return self
     */
    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param DateTime|null $date
     * @return $this
     */
    public function setCreatedAt(DateTime $date = null): self
    {
        if ($date === null)
            $date = new DateTime("now");

        $this->createdAt = $date;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }
}
