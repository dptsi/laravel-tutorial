<?php declare(strict_types = 1);

abstract class Hewan
{
    private int $jumlah_kaki;

    public function __construct(int $jumlah_kaki)
    {
        $this->jumlah_kaki = $jumlah_kaki;
    }

	public abstract function bersuara(): string;

	public function getJumlahKaki(): int {
		return $this->jumlah_kaki;
	}
}

class Kucing extends Hewan {
	public function __construct()
	{
		parent::__construct(4);
	}

	public function bersuara(): string
	{
		return "Meong";
	}
}

class Anjing extends Hewan {
	public function __construct()
	{
		parent::__construct(4);
	}

	public function bersuara(): string
	{
		return "Guk-guk";
	}
}


$kucing = new Kucing();
$anjing = new Anjing();

echo "<p>Kucing bersuara: ".$kucing->bersuara()."</p>";
echo "<p>Jumlah kaki kucing : ".$kucing->getJumlahKaki()."</p>";

echo "<p>Anjing bersuara: ".$anjing->bersuara()."</p>";
echo "<p>Jumlah kaki anjing : ".$anjing->getJumlahKaki()."</p>";