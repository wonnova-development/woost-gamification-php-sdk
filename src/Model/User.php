<?php
namespace Wonnova\SDK\Model;

use JMS\Serializer\Annotation as JMS;
use Wonnova\SDK\Common\WonnovaDateTimeParserTrait;

/**
 * Class User
 * @author Wonnova
 * @link http://www.wonnova.com
 */
class User extends AbstractModel
{
    use WonnovaDateTimeParserTrait;

    /**
     * Used to map virtual to real fields
     *
     * @var array
     * @JMS\Exclude()
     */
    protected $fieldMapping = [
        'points' => 'score'
    ];

    /**
     * @var string
     * @JMS\Type("string")
     */
    private $userId;
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $username;
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $provider;
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $fullName;
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $avatar;
    /**
     * @var \DateTime
     * @JMS\Type("WonnovaDateTime")
     */
    private $dateOfBirth;
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $email;
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $address;
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $city;
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $country;
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $postalCode;
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $phone;
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $gender;
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $locale;
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $timezone;
    /**
     * @var integer
     * @JMS\Type("integer")
     * @JMS\SerializedName("points")
     */
    private $score;

    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param string $userId
     * @return $this
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * @param string $provider
     * @return $this
     */
    public function setProvider($provider)
    {
        $this->provider = $provider;
        return $this;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @param string $fullName
     * @return $this
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;
        return $this;
    }

    /**
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param string $avatar
     * @return $this
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * @param \DateTime|array|string $dateOfBirth
     * @return $this
     */
    public function setDateOfBirth($dateOfBirth)
    {
        $this->dateOfBirth = $this->parseWonnovaDateTime($dateOfBirth);
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return $this
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     * @return $this
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * @param string $postalCode
     * @return $this
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return $this
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     * @return $this
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     * @return $this
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
        return $this;
    }

    /**
     * @return string
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * @param string $timezone
     * @return $this
     */
    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;
        return $this;
    }

    /**
     * @return integer
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * @param integer $score
     * @return $this
     */
    public function setScore($score)
    {
        $this->score = $score;
        return $this;
    }

    /**
     * Returns a copy data array of this object
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'userId' => $this->userId,
            'username' => $this->username,
            'provider' => $this->provider,
            'fullName' => $this->fullName,
            'avatar' => $this->avatar,
            'dateOfBirth' => $this->dateOfBirth instanceof \DateTime
                ? $this->dateOfBirth->format('Y-m-d H:i:s')
                : $this->dateOfBirth,
            'email' => $this->email,
            'address' => $this->address,
            'city' => $this->city,
            'country' => $this->country,
            'postalCode' => $this->postalCode,
            'phone' => $this->phone,
            'gender' => $this->gender,
            'locale' => $this->locale,
            'timezone' => $this->timezone,
            'score' => $this->score
        ];
    }
}
