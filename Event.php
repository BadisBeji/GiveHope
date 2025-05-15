<?php
if (!class_exists('Event')) {
    class Event
    {
        private $id;
        private $name;
        private $description;
        private $start_datetime;
        private $end_datetime;
        private $location;
        private $category;
        private $organizer;
        private $status;

        // Constructeur
        public function __construct($name, $description, $start_datetime, $end_datetime, $location, $category, $organizer, $status)
        {
            $this->name = $name;
            $this->description = $description;
            $this->start_datetime = new DateTime($start_datetime);
            $this->end_datetime = new DateTime($end_datetime);
            $this->location = $location;
            $this->category = $category;
            $this->organizer = $organizer;
            $this->status = $status;
        }

        // Getters et Setters
        public function getId()
        {
            return $this->id;
        }

        public function setId($id)
        {
            $this->id = $id;
        }

        public function getName()
        {
            return $this->name;
        }

        public function setName($name)
        {
            $this->name = $name;
        }

        public function getDescription()
        {
            return $this->description;
        }

        public function setDescription($description)
        {
            $this->description = $description;
        }

        public function getStartDatetime()
        {
            return $this->start_datetime;
        }

        public function setStartDatetime($start_datetime)
        {
            $this->start_datetime = new DateTime($start_datetime);
        }

        public function getEndDatetime()
        {
            return $this->end_datetime;
        }

        public function setEndDatetime($end_datetime)
        {
            $this->end_datetime = new DateTime($end_datetime);
        }

        public function getLocation()
        {
            return $this->location;
        }

        public function setLocation($location)
        {
            $this->location = $location;
        }

        public function getCategory()
        {
            return $this->category;
        }

        public function setCategory($category)
        {
            $this->category = $category;
        }

        public function getOrganizer()
        {
            return $this->organizer;
        }

        public function setOrganizer($organizer)
        {
            $this->organizer = $organizer;
        }

        public function getStatus()
        {
            return $this->status;
        }

        public function setStatus($status)
        {
            $this->status = $status;
        }
    }
}
?>
