<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="abi_code_rating")
 */
class AbiCodeRating {
    /**
     * @ORM\Column(type="string",  name="abi_code")
     * @Assert\NotBlank()
     * @ORM\Id
     *
     */
    private $abiCode;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, name="rating_factor")
     * @Assert\NotBlank()
     */
    private $ratingFactor;


    //------ Establish setters and getters -----------//

    /**
     * @return mixed
     */
    public function getAbiCode()
    {
        return $this->abiCode;
    }

    /**
     * @param mixed $abiCode
     */
    public function setAbiCode($abiCode)
    {
        $this->abiCode = $abiCode;
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