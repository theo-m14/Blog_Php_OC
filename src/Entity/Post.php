<?php

namespace App\Entity;

use App\Exception\PropertyNotFoundException;
use DateTime;

class Post
{
    private int $id;
    private string $author;
    private DateTime $date;
    private string $title;
    private string $caption;
    private string $content;

    public function __construct(int $id = null, string $author = null, DateTime $date = null, string $title = null, string $caption = null, string $content = null)
    {
        $this->id = $id;
        $this->author = $author;
        $this->date = $date;
        $this->title = $title;
        $this->caption = $caption;
        $this->content = $content;
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

    public function getId() : int
    {
        return $this->id;
    }

    public function getAuthor() : string
    {
        return $this->author;
    }

    public function getDate() : DateTime
    {
        return $this->date;
    }

    public function getTitle() : string
    {
        return $this->title;
    }

    public function getCaption() : string
    {
        return $this->caption;
    }

    public function getContent() : string
    {
        return $this->content;
    }

}
