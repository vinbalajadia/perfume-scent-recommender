<?php
class Perfume{
    public $name;
    public $brand;
    public $price;
    public $notes;
    public $types;
    public $image;
    public $rating;
    public $description;

    public function __construct($name, $brand, $price, $notes, $types, $image, $rating, $description) {
        $this->name = $name;
        $this->brand = $brand;
        $this->price = $price;
        $this->notes = $notes;
        $this->types = $types;
        $this->image = $image;
        $this->rating = $rating;
        $this->description = $description;
    }

    public function getName() {
        return $this->name;
    }

    public function getBrand() {
        return $this->brand;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getNotes() {
        return  $this->notes;
    }

    public function getTypes() {
        return $this->types;
    }

    public function getImage() {
        return $this->image;
    }

    public function getRating() {
        return $this->rating;
    }

    public function getDescription() {
        return $this->description;
    }

    public function toArray() {
        return [
            'name' => $this->name,
            'brand' => $this->brand,
            'price' => $this->price,
            'notes' => $this->notes,
            'types' => $this->types,
            'image' => $this->image,
            'rating' => $this->rating,
            'description' => $this->description
        ];
    }

    public static function fromArray($data) {
        return new self(
            $data['name'] ?? '',
            $data['brand'] ?? '',
            $data['price'] ?? '',
            $data['notes'] ?? [],
            $data['types'] ?? [],
            $data['image'] ?? '',
            $data['rating'] ?? 0,
            $data['description'] ?? ''
        );
    }

}

class scentRecommender {
    private $fragranticaURL = "https://www.fragrantica.com";

    public function perfumeSearch($query) {
        $scentReco = $this->getMockReco($query);
        return $scentReco;
    }

    public function getMockReco($query) {
        $mockPerf = [
            [
                'name' => 'Creed Aventus',
                'brand' => 'Creed',
                'price' => '₱11,000',
                'notes' => ['Bergamot', 'Pineapple', 'Blackcurrant'],
                'types' => ['Eau de Parfum'],
                'image' => '<img src="https://fimgs.net/mdimg/perfume-thumbs/375x500.9828.2x.avif" alt="Creed Aventus">',
                'rating' => 4.3,
                'description' => 'Creed Aventus is a fresh, smokey, and fruity fragrance with an ambergris undertone, perfect for confident individuals.'
            ],

            [
                'name' => 'Valentino Coral Fantasy',
                'brand' => 'Valentino',
                'price' => '5,500',
                'notes' => ['Red Apple', 'Tobacco', 'Citrus', 'Woody'],
                'types' => ['Eau de Toilette'],
                'image' => '<img src="https://fimgs.net/mdimg/perfume-thumbs/375x500.71761.2x.avif" alt="Valentino Coral Fantasy">',
                'rating' => 4.6,
                'description' => 'Valentino Coral Fantasy is a vibrant and fruity fragrance with a warm, woody base, ideal for summer days.'
            ]
        ];

        $filtered = array_filter($mockPerf, function($perfume) use ($query) {
            $notesStr = implode(' ', $perfume['notes']);
            $typesStr = implode(' ', $perfume['types']);
            $searchIn = strtolower($perfume['name']) . ' ' . $perfume['brand'] . ' ' . $notesStr . ' ' . $typesStr;
            return strpos($searchIn, strtolower($query)) !== false;
        });

        $scentReco = [];
        foreach($$filtered as $perfumeData) {
            $scentReco[] = Perfume::fromArray($perfumeData);
        }

        return $scentReco;

    }

    public function getFragranticaURL($perfumeName, $brandName) {
        $slug = strtolower(str_replace(' ', '-', $brandName . '-' . $perfumeName));
        return $this->fragranticaURL . '/perfume/' . $slug;
    }
}
?>