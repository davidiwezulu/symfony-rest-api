<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="base_premium")
 * @ORM\Entity(repositoryClass="App\Repository\BasePremiumRepository")
 */
class BasePremium {
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @ORM\Column(type="decimal", precision=7, scale=2, name="base_premium")
     * @Assert\NotBlank()
     */
    private $basePremium;


    //------ Establish setters and getters -----------//
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getBasePremium()
    {
        return $this->basePremium;
    }

    /**
     * @param mixed $basePremium
     */
    public function setBasePremium($basePremium)
    {
        $this->basePremium  = $basePremium;
    }

    /**
     * @return mixed
     */
    public function getRatingFactor()
    {
        return $this->ratingFactor;
    }

    /**
     * @param mixed $ratingFactor
     */
    public function setRatingFactor($ratingFactor)
    {
        $this->ratingFactor = $ratingFactor;
    }
}