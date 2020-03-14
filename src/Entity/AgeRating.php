<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="age_rating")
 */
class AgeRating {

    /**
     * @ORM\Column(type="integer", name="age")
     * @ORM\Id
     *
     */
    private $age;

    /**
     * @ORM\Column(type="decimal", precision=7, scale=2, name="rating_factor")
     * @Assert\NotBlank()
     */
    private $ratingFactor;


    //------ Establish setters and getters -----------//
    /**
     * @return mixed
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @param mixed $age
     */
    public function setAge($age)
    {
        $this->age = $age;
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