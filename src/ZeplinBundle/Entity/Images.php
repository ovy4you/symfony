<?php

namespace ZeplinBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Images
 *
 * @ORM\Table(name="images")
 * @ORM\Entity(repositoryClass="ZeplinBundle\Repository\ImagesRepository")
 */
class Images
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, unique=true)
     * @Assert\NotBlank(message="Please, upload the a image.")
     * @Assert\File(mimeTypes={ "image/png", "image/jpg", "image/jpeg","image/gif"})
     */
    private $image;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime",options={"default": 0})
     */
    private $time;


    /**
     * @var int
     *
     * @ORM\Column(name="user_id", type="integer")
     *
     **/

    private $userId;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return Images
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }


    /**
     * @param $userId
     * @return mixed
     */
    public function setUserId($userId)
    {
        return $this->userId = $userId;
    }


    /**
     * Set time
     *
     * @param \DateTime $time
     * @return Images
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->time;
    }
}
