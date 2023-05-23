<?php

namespace App\Entity;

use DateTime;
use App\Exception\PropertyNotFoundException;

class Post
{
    private ?int $id;
    private ?string $author;
    private ?string $date;
    private ?string $title;
    private ?string $caption;
    private ?string $content;
    private ?int $user_id;

    public function __construct(int $id = null, string $author = null, string $date = null, string $title = null, string $caption = null, string $content = null, ?int $userId = null)
    {
        $this->id = $id;
        $this->author = $author;
        $this->date = $date;
        $this->title = $title;
        $this->caption = $caption;
        $this->content = $content;
        $this->user_id = $userId;
    }

    public function generalGetter(string $paramName) : mixed
    {
        if(property_exists($this,$paramName))
			{
				return $this->$paramName;
			}
			else
			{
				throw new PropertyNotFoundException($this->$this,$paramName);
			}
    }

    public function getId() : ?int
    {
        return $this->id;
    }

    public function setAuthor(string $author) : void
    {
        $this->author = $author;
    }

    public function getDate() : ?string
    {
        return $this->date;
    }

    public function getTitle() : ?string
    {
        return $this->title;
    }

    public function getCaption() : ?string
    {
        return $this->caption;
    }

    public function getContent() : ?string
    {
        return $this->content;
    }


    public function getUserId()
    {
        return $this->user_id;
    }

    public function getAuthor()
    {
        return $this->author;
    }
}
